<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\Testimonial;
use App\Http\Requests\Cms\TestimonialRequest;

class TestimonialController extends Controller {

    protected $route = 'testimonials';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'testimonial_category' => [
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
            'testimonial_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleTestimonials'),
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
                        'selector' => $this->route . '.country',
                        'id' => 'country',
                        'name' => trans(\Locales::getNamespace() . '/datatables.country'),
                        'search' => true,
                    ],
                    [
                        'selector' => $this->route . '.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => $this->route . '.is_active',
                        'id' => 'is_active',
                        'name' => trans(\Locales::getNamespace() . '/datatables.status'),
                        'class' => 'text-center',
                        'status' => [
                            'class' => 'change-status',
                            'queue' => 'async-change-status',
                            'route' => $this->route . '/change-status',
                            'rules' => [
                                0 => [
                                    'status' => 1,
                                    'icon' => 'off.gif',
                                    'title' => trans(\Locales::getNamespace() . '/datatables.statusOff'),
                                ],
                                1 => [
                                    'status' => 0,
                                    'icon' => 'on.gif',
                                    'title' => trans(\Locales::getNamespace() . '/datatables.statusOn'),
                                ],
                            ],
                        ],
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

    public function index(DataTable $datatable, Testimonial $testimonial, Request $request, $slugs = null)
    {
        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $testimonials = Testimonial::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
            $testimonials = \App\Helpers\arrayToTree($testimonials);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $testimonials)) { // match slugs against the testimonials array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $testimonials);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs
                if ($row['is_category']) { // it's a category
                    $request->session()->put($testimonial->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $testimonial = $testimonial->where('parent', $row['id']);
                    $datatable->setup($testimonial, 'testimonial_page', $this->datatables['testimonial_page']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($testimonial->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs
            $testimonial = $testimonial->where('parent', null);
            $datatable->setup($testimonial, 'testimonial_category', $this->datatables['testimonial_category']);
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

    public function store(DataTable $datatable, Testimonial $testimonial, TestimonialRequest $request)
    {
        $parent = $request->session()->get($testimonial->getTable() . 'Parent', null);

        $testimonial = $testimonial->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $testimonial->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $testimonial;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newTestimonial = Testimonial::create($request->all());

        if ($newTestimonial->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityTestimonials'), 1)]);

            $datatable->setup($testimonial, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityTestimonials'), 1)]);
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

    public function destroy(DataTable $datatable, Testimonial $testimonial, Request $request)
    {
        $count = count($request->input('id'));

        if ($count > 0 && $testimonial->destroy($request->input('id'))) {
            $parent = $request->session()->get($testimonial->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $testimonial->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);

            $testimonial = $testimonial->where('parent', $parent);

            $datatable->setup($testimonial, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $testimonial = Testimonial::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($testimonial->is_category ? '-category' : ''), compact('testimonial', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Testimonial $testimonialOrder, TestimonialRequest $request)
    {
        $testimonial = Testimonial::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $testimonial->order;
        } elseif ($order) {
            $testimonialOrder = $testimonialOrder->where('parent', $testimonial->parent);
            $maxOrder = $testimonialOrder->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $testimonial->order) {
                $testimonialOrder->where('order', '>=', $order)->where('order', '<', $testimonial->order)->increment('order');
            } elseif ($order > $testimonial->order) {
                $testimonialOrder->where('order', '<=', $order)->where('order', '>', $testimonial->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($testimonial->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($testimonial->is_category ? 'entityCategories' : 'entityTestimonials'), 1)]);

            $testimonial = $testimonial->where('parent', $testimonial->parent);

            $datatable->setup($testimonial, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($testimonial->is_category ? 'entityCategories' : 'entityTestimonials'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->is_active = $status;
        $testimonial->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['testimonial_page']['columns'] as $column) {
            if ($column['id'] == 'is_active') {
                foreach ($column['status']['rules'] as $key => $value) {
                    if ($key == $status) {
                        $href = \Locales::route($column['status']['route'], [$id, $value['status']]);
                        $img = \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/' . $value['icon']), $value['title']);
                        break 2;
                    }
                }
            }
        }

        return response()->json(['success' => true, 'href' => $href, 'img' => $img]);
    }

}
