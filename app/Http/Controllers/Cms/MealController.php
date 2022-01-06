<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\Meal;
use App\Http\Requests\Cms\MealRequest;

class MealController extends Controller {

    protected $route = 'meals';
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
            'meal_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleMeals'),
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
                        'selectRaw' => 'IF (' . $this->route . '.description IS NOT NULL, CONCAT(' . $this->route . '.name, " (", ' . $this->route . '.description, ")"), ' . $this->route . '.name) as name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => $this->route . '.price_adult',
                        'id' => 'price_adult',
                        'name' => trans(\Locales::getNamespace() . '/datatables.priceAdult'),
                        'search' => true,
                        'order' => false,
                        'class' => 'text-right',
                    ],
                    [
                        'selector' => $this->route . '.price_child',
                        'id' => 'price_child',
                        'name' => trans(\Locales::getNamespace() . '/datatables.priceChild'),
                        'search' => true,
                        'order' => false,
                        'class' => 'text-right',
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

    public function index(DataTable $datatable, Meal $meal, Request $request, $slugs = null)
    {
        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $meals = Meal::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
            $meals = \App\Helpers\arrayToTree($meals);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $meals)) { // match slugs against the meals array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $meals);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs
                if ($row['is_category']) { // it's a category
                    $request->session()->put($meal->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $meal = $meal->where('parent', $row['id']);
                    $datatable->setup($meal, 'meal_page', $this->datatables['meal_page']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($meal->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs
            $meal = $meal->where('parent', null);
            $datatable->setup($meal, 'view_category', $this->datatables['view_category']);
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

    public function store(DataTable $datatable, Meal $meal, MealRequest $request)
    {
        $parent = $request->session()->get($meal->getTable() . 'Parent', null);

        $meal = $meal->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $meal->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $meal;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newMeal = Meal::create($request->all());

        if ($newMeal->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityMeals'), 1)]);

            $datatable->setup($meal, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityMeals'), 1)]);
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

    public function destroy(DataTable $datatable, Meal $meal, Request $request)
    {
        $count = count($request->input('id'));

        if ($count > 0 && $meal->destroy($request->input('id'))) {
            $parent = $request->session()->get($meal->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $meal->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);

            $meal = $meal->where('parent', $parent);

            $datatable->setup($meal, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $meal = Meal::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($meal->is_category ? '-category' : ''), compact('meal', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Meal $mealOrder, MealRequest $request)
    {
        $meal = Meal::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $meal->order;
        } elseif ($order) {
            $mealOrder = $mealOrder->where('parent', $meal->parent);
            $maxOrder = $mealOrder->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $meal->order) {
                $mealOrder->where('order', '>=', $order)->where('order', '<', $meal->order)->increment('order');
            } elseif ($order > $meal->order) {
                $mealOrder->where('order', '<=', $order)->where('order', '>', $meal->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($meal->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($meal->is_category ? 'entityCategories' : 'entityMeals'), 1)]);

            $meal = $meal->where('parent', $meal->parent);

            $datatable->setup($meal, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($meal->is_category ? 'entityCategories' : 'entityMeals'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

}
