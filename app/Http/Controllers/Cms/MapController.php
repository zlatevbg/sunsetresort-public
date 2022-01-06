<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\Map;
use App\Http\Requests\Cms\MapRequest;

class MapController extends Controller {

    protected $route = 'map';
    protected $datatables;
    protected $multiselect;

    public function __construct()
    {
        $this->datatables = [
            'map_category' => [
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
            'map_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleMapsLocations'),
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
                        'color' => [
                            'selector' => [$this->route . '.color'],
                            'id' => 'color',
                        ],
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
                        'name' => trans(\Locales::getNamespace() . '/forms.addLocationButton'),
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

        $this->multiselect = [
            'color' => [
                'id' => 'id',
                'name' => 'name',
            ],
        ];
    }

    public function index(DataTable $datatable, Map $map, Request $request, $slugs = null)
    {
        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $maps = Map::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
            $maps = \App\Helpers\arrayToTree($maps);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $maps)) { // match slugs against the maps array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $maps);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs
                if ($row['is_category']) { // it's a category
                    $request->session()->put($map->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $map = $map->where('parent', $row['id']);
                    $datatable->setup($map, 'map_page', $this->datatables['map_page']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($map->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs
            $map = $map->where('parent', null);
            $datatable->setup($map, 'map_category', $this->datatables['map_category']);
        }

        $datatables = $datatable->getTables();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($datatables);
        } else {
            return view(\Locales::getNamespace() . '.' . $this->route . '.index', compact('datatables', 'breadcrumbs'));
        }
    }

    public function create(Request $request, Map $map)
    {
        $table = $request->input('table');

        $parent = $request->session()->get($map->getTable() . 'Parent', null);
        $markers = $map->select(['lat', 'lng', 'name', 'content', 'color', 'order'])->where('parent', $parent)->orderBy('order')->get()->toArray();

        $options = [];
        foreach (trans(\Locales::getNamespace() . '/multiselect.mapColors') as $key => $value) {
            array_push($options, [
                'id' => $key,
                'name' =>  $value,
            ]);
        }

        $this->multiselect['color']['options'] = $options;
        $this->multiselect['color']['selected'] = '';

        $multiselect = $this->multiselect;

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'markers', 'multiselect'));
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

    public function store(DataTable $datatable, Map $map, MapRequest $request)
    {
        $parent = $request->session()->get($map->getTable() . 'Parent', null);

        $map = $map->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $map->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $map;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newMap = Map::create($request->all());

        if ($newMap->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityMapLocations'), 1)]);

            $datatable->setup($map, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityMapLocations'), 1)]);
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

    public function destroy(DataTable $datatable, Map $map, Request $request)
    {
        $count = count($request->input('id'));

        if ($count > 0 && $map->destroy($request->input('id'))) {
            $parent = $request->session()->get($map->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $map->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);

            $map = $map->where('parent', $parent);

            $datatable->setup($map, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $map = Map::findOrFail($id);

        $table = $request->input('table');

        $parent = $request->session()->get($map->getTable() . 'Parent', null);
        $markers = $map->select(['lat', 'lng', 'name', 'content', 'color', 'order'])->where('parent', $parent)->where('id', '!=', $id)->orderBy('order')->get()->toArray();

        $options = [];
        foreach (trans(\Locales::getNamespace() . '/multiselect.mapColors') as $key => $value) {
            array_push($options, [
                'id' => $key,
                'name' =>  $value,
            ]);
        }

        $this->multiselect['color']['options'] = $options;
        $this->multiselect['color']['selected'] = $map->color;

        $multiselect = $this->multiselect;

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($map->is_category ? '-category' : ''), compact('map', 'table', 'markers', 'multiselect'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Map $mapOrder, MapRequest $request)
    {
        $map = Map::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $map->order;
        } elseif ($order) {
            $mapOrder = $mapOrder->where('parent', $map->parent);
            $maxOrder = $mapOrder->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $map->order) {
                $mapOrder->where('order', '>=', $order)->where('order', '<', $map->order)->increment('order');
            } elseif ($order > $map->order) {
                $mapOrder->where('order', '<=', $order)->where('order', '>', $map->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($map->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($map->is_category ? 'entityCategories' : 'entityMapLocations'), 1)]);

            $map = $map->where('parent', $map->parent);

            $datatable->setup($map, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($map->is_category ? 'entityCategories' : 'entityMapLocations'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $map = Map::findOrFail($id);

        $map->is_active = $status;
        $map->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['map_page']['columns'] as $column) {
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
