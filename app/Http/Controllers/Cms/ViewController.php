<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\View as Views;
use App\Http\Requests\Cms\ViewRequest;

class ViewController extends Controller {

    protected $route = 'views';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'view_category' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleLanguages'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'columns' => [
                    [
                        'selector' => $this->route . '.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => $this->route . '.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                        'link' => [
                            'selector' => [$this->route . '.slug'],
                            'icon' => 'folder-open',
                            'route' => $this->route,
                            'routeParameter' => 'slug',
                        ],
                    ],
                ],
                'orderByColumn' => 1,
                'order' => 'asc',
                'buttons' => [
                    [
                        'url' => \Locales::route($this->route . '/create-category'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createCategoryButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/edit'),
                        'class' => 'btn-warning disabled js-edit',
                        'icon' => 'edit',
                        'name' => trans(\Locales::getNamespace() . '/forms.editButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/delete'),
                        'class' => 'btn-danger disabled js-destroy',
                        'icon' => 'trash',
                        'name' => trans(\Locales::getNamespace() . '/forms.deleteButton'),
                    ],
                ],
            ],
            'view_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleViews'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'columns' => [
                    [
                        'selector' => $this->route . '.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => $this->route . '.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => $this->route . '.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                ],
                'orderByColumn' => 'order',
                'order' => 'asc',
                'buttons' => [
                    [
                        'url' => \Locales::route($this->route . '/create'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createPageButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/edit'),
                        'class' => 'btn-warning disabled js-edit',
                        'icon' => 'edit',
                        'name' => trans(\Locales::getNamespace() . '/forms.editButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/delete'),
                        'class' => 'btn-danger disabled js-destroy',
                        'icon' => 'trash',
                        'name' => trans(\Locales::getNamespace() . '/forms.deleteButton'),
                    ],
                ],
            ],
        ];
    }

    public function index(DataTable $datatable, Views $view, Request $request, $slugs = null)
    {
        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $views = Views::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
            $views = \App\Helpers\arrayToTree($views);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $views)) { // match slugs against the views array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $views);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs
                if ($row['is_category']) { // it's a category
                    $request->session()->put($view->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $view = $view->where('parent', $row['id']);
                    $datatable->setup($view, 'view_page', $this->datatables['view_page']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($view->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs
            $view = $view->where('parent', null);
            $datatable->setup($view, 'view_category', $this->datatables['view_category']);
        }

        $datatables = $datatable->getTables();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($datatables);
        } else {
            return view(\Locales::getNamespace() . '.' . $this->route . '.index', compact('datatables', 'breadcrumbs'));
        }
    }

    public function create(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function createCategory(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create-category', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function store(DataTable $datatable, Views $view, ViewRequest $request)
    {
        $parent = $request->session()->get($view->getTable() . 'Parent', null);

        $view = $view->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $view->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $view;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newView = Views::create($request->all());

        if ($newView->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityViews'), 1)]);

            $datatable->setup($view, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityViews'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function delete(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.delete', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function destroy(DataTable $datatable, Views $view, Request $request)
    {
        $count = count($request->input('id'));

        if ($count > 0 && $view->destroy($request->input('id'))) {
            $parent = $request->session()->get($view->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $view->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);

            $view = $view->where('parent', $parent);

            $datatable->setup($view, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => trans(\Locales::getNamespace() . '/forms.destroyedSuccessfully'),
                'closePopup' => true,
            ]);
        } else {
            if ($count > 0) {
                $errorMessage = trans(\Locales::getNamespace() . '/forms.deleteError');
            } else {
                $errorMessage = trans(\Locales::getNamespace() . '/forms.countError');
            }

            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function edit(Request $request, $id = null)
    {
        $view = Views::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($view->is_category ? '-category' : ''), compact('view', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Views $viewOrder, ViewRequest $request)
    {
        $view = Views::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $view->order;
        } elseif ($order) {
            $viewOrder = $viewOrder->where('parent', $view->parent);
            $maxOrder = $viewOrder->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $view->order) {
                $viewOrder->where('order', '>=', $order)->where('order', '<', $view->order)->increment('order');
            } elseif ($order > $view->order) {
                $viewOrder->where('order', '<=', $order)->where('order', '>', $view->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($view->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($view->is_category ? 'entityCategories' : 'entityViews'), 1)]);

            $view = $view->where('parent', $view->parent);

            $datatable->setup($view, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($view->is_category ? 'entityCategories' : 'entityViews'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

}
