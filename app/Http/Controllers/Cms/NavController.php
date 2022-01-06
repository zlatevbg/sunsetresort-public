<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Nav;
use App\NavImage;
use Storage;
use App\Http\Requests\Cms\NavRequest;
use App\Http\Requests\Cms\NavImageRequest;

class NavController extends Controller {

    protected $route = 'nav';
    protected $uploadDirectory = 'nav';
    protected $datatables;
    protected $multiselect;

    public function __construct()
    {
        $this->datatables = [
            'nav_category' => [
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
            'nav_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titlePages'),
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
                            'selector' => [$this->route . '.is_category'],
                            'rules' => [
                                0 => [
                                    'column' => 'is_category',
                                    'value' => 0,
                                    'icon' => 'file',
                                ],
                                1 => [
                                    'column' => 'is_category',
                                    'value' => 1,
                                    'icon' => 'folder-open',
                                ],
                            ],
                            'route' => $this->route,
                            'routeParameter' => 'slug',
                        ],
                    ],
                    [
                        'selector' => $this->route . '.slug',
                        'id' => 'slug',
                        'name' => trans(\Locales::getNamespace() . '/datatables.slug'),
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
                        'url' => \Locales::route($this->route . '/create-category'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createCategoryButton'),
                    ],
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
            'nav_images' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleImages'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-thumbnails popup-gallery',
                'uploadDirectory' => $this->uploadDirectory,
                'columns' => [
                    [
                        'selector' => 'nav_images.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'nav_images.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'nav_images.is_active',
                        'id' => 'is_active',
                        'name' => trans(\Locales::getNamespace() . '/datatables.status'),
                        'class' => 'text-center',
                        'status' => [
                            'class' => 'change-status',
                            'queue' => 'async-change-status',
                            'route' => $this->route . '/change-image-status',
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
                    [
                        'selector' => 'nav_images.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'nav_images.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.image'),
                        'order' => false,
                        'class' => 'text-center',
                        'thumbnail' => [
                            'selector' => ['nav_images.uuid', 'nav_images.title'],
                            'title' => 'title',
                            'id' => 'uuid',
                        ],
                    ],
                    [
                        'selector' => 'nav_images.size',
                        'id' => 'size',
                        'name' => trans(\Locales::getNamespace() . '/datatables.size'),
                        'filesize' => true,
                    ],
                ],
                'orderByColumn' => 'order',
                'order' => 'asc',
                'buttons' => [
                    'upload' => [
                        'upload' => true,
                        'id' => 'fine-uploader-upload',
                        'url' => \Locales::route($this->route . '/upload'),
                        'class' => 'btn-primary js-upload',
                        'icon' => 'upload',
                        'name' => trans(\Locales::getNamespace() . '/forms.uploadButton'),
                    ],
                    [
                        'reupload' => true,
                        'id' => 'fine-uploader-reupload',
                        'url' => \Locales::route($this->route . '/upload'),
                        'class' => 'btn-primary disabled js-reupload',
                        'icon' => 'refresh',
                        'name' => trans(\Locales::getNamespace() . '/forms.replaceImageButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/edit-image'),
                        'class' => 'btn-warning disabled js-edit',
                        'icon' => 'edit',
                        'name' => trans(\Locales::getNamespace() . '/forms.editButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/delete-image'),
                        'class' => 'btn-danger disabled js-destroy',
                        'icon' => 'trash',
                        'name' => trans(\Locales::getNamespace() . '/forms.deleteButton'),
                    ],
                ],
            ],
        ];

        $this->multiselect = [
            'type' => [
                'id' => 'id',
                'name' => 'name',
            ],
        ];
    }

    public function index(DataTable $datatable, Nav $page, Request $request, $slugs = null)
    {
        $uploadDirectory = $this->uploadDirectory;
        $request->session()->put('ckfinderBaseUrl', $uploadDirectory . '/');
        if (!Storage::disk('local-public')->exists($uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploadDirectory);
        }

        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $pages = Nav::select('id', 'parent', 'slug', 'is_category', 'name', 'is_multi_page')->get()->toArray();
            $pages = \App\Helpers\arrayToTree($pages);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $pages)) { // match slugs against the pages array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $pages);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs for proper file upload actions

                foreach($slugsArray as $slug) { // ensure the list of the current subdirectories exist for proper upload actions
                    $uploadDirectory .= DIRECTORY_SEPARATOR . $slug;
                    if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                        Storage::disk('local-public')->makeDirectory($uploadDirectory);
                    }
                }

                if ($row['is_category']) { // it's a category
                    $request->session()->put($page->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $page = $page->where('parent', $row['id']);
                    $datatable->setup($page, 'nav_page', $this->datatables['nav_page']);

                    if ($row['is_multi_page']) {
                        $datatable->setOption('expandDirectory', \Config::get('upload.sliderDirectory') . '/', 'nav_images');
                        $datatable->setup(NavImage::where('nav_id', $row['id']), 'nav_images', $this->datatables['nav_images']);
                        $datatable->setOption('pageId', $row['id']);
                    }
                } else { // it's a page
                    end($breadcrumbs);
                    $parent = prev($breadcrumbs);

                    if ($parent && $parent['is_multi_page']) {
                        $datatable->setOption('buttons', ['upload' => ['single' => 'true']], 'nav_images');
                        $datatable->setOption('expandDirectory', \Config::get('upload.pageDirectory') . '/', 'nav_images');
                    } else {
                        $datatable->setOption('expandDirectory', \Config::get('upload.sliderDirectory') . '/', 'nav_images');
                    }

                    $datatable->setup(NavImage::where('nav_id', $row['id']), 'nav_images', $this->datatables['nav_images']);
                    $datatable->setOption('pageId', $row['id']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($page->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs for proper file upload actions
            $page = $page->where('parent', null);
            $datatable->setup($page, 'nav_category', $this->datatables['nav_category']);
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

        $options = [];
        foreach (trans(\Locales::getNamespace() . '/multiselect.pageTypes') as $key => $value) {
            array_push($options, [
                'id' => $key,
                'name' =>  $value,
            ]);
        }

        $this->multiselect['type']['options'] = $options;
        $this->multiselect['type']['selected'] = '';

        $multiselect = $this->multiselect;

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'multiselect'));
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

    public function store(DataTable $datatable, Nav $page, NavRequest $request)
    {
        $parent = $request->session()->get($page->getTable() . 'Parent', null);

        $page = $page->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $page->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $page;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newNav = Nav::create($request->all());

        if ($newNav->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newNav->slug;
            if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                Storage::disk('local-public')->makeDirectory($uploadDirectory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityPages'), 1)]);

            $datatable->setup($page, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
                'resetEditor' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityPages'), 1)]);
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

    public function destroy(DataTable $datatable, Nav $page, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Nav::find($request->input('id'))->lists('slug');

        if ($count > 0 && $page->destroy($request->input('id'))) {
            $parent = $request->session()->get($page->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $page->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            foreach ($directories as $directory) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $directory);
            }

            $page = $page->where('parent', $parent);

            $datatable->setup($page, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $page = Nav::findOrFail($id);

        $table = $request->input('table');

        $options = [];
        foreach (trans(\Locales::getNamespace() . '/multiselect.pageTypes') as $key => $value) {
            array_push($options, [
                'id' => $key,
                'name' =>  $value,
            ]);
        }

        $this->multiselect['type']['options'] = $options;
        $this->multiselect['type']['selected'] = $page->type;

        $multiselect = $this->multiselect;

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($page->is_category ? '-category' : ''), compact('page', 'table', 'multiselect'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Nav $nav, NavRequest $request)
    {
        $page = Nav::findOrFail($request->input('id'))->first();
        $oldNav = $page->replicate();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $page->order;
        } elseif ($order) {
            $nav = $nav->where('parent', $page->parent);
            $maxOrder = $nav->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $page->order) {
                $nav->where('order', '>=', $order)->where('order', '<', $page->order)->increment('order');
            } elseif ($order > $page->order) {
                $nav->where('order', '<=', $order)->where('order', '>', $page->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($page->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($oldNav->slug == $page->slug) {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $page->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $page->slug);
                }
            } else {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $oldNav->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $oldNav->slug);
                }

                Storage::disk('local-public')->move($uploadDirectory . $oldNav->slug, $uploadDirectory . $page->slug);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($page->is_category ? 'entityCategories' : 'entityPages'), 1)]);

            $page = $page->where('parent', $page->parent);

            $datatable->setup($page, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($page->is_category ? 'entityCategories' : 'entityPages'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function upload(Request $request, Nav $nav, FineUploader $uploader, $chunk = null)
    {
        $parentModel = \Cache::remember('upload-' . $nav->getTable() . '-' . $request->input('id'), 5, function() use ($nav, $request) { // store for 5 minutes
            return $nav->select('join.is_multi_page')->leftJoin($nav->getTable() . ' as join', $nav->getTable() . '.parent', '=', 'join.id')->findOrFail($request->input('id'));
        });

        if ($parentModel->is_multi_page) {
            $uploader->page = true;
            $expandDirectory = \Config::get('upload.pageDirectory');
        } else {
            $uploader->slider = true;
            $uploader->sliderSmall = true;
            $uploader->sliderMedium = true;
            $uploader->sliderLarge = true;
            $expandDirectory = \Config::get('upload.sliderDirectory');
        }

        $slugs = $request->session()->get('routeSlugs', []);
        $uploader->uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.imagesDirectory');
        if (!Storage::disk('local-public')->exists($uploader->uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploader->uploadDirectory);
        }

        if ($chunk) {
            $response = $uploader->combineChunks();
        } else {
            $response = $uploader->handleUpload();
        }

        $reupload = filter_var($request->input('reupload'), FILTER_VALIDATE_BOOLEAN);
        if (isset($response['success']) && $response['success'] && isset($response['fileName'])) {
            if ($reupload) {
                $response['reupload'] = true;
                $response['row'] = $request->input('row');

                $image = NavImage::findOrFail($request->input('row'));
                $status = $image->is_active;

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $image->uuid);
            } else {
                $status = 1;
                $image = new NavImage;
                $image->nav_id = $request->input('id');
                $image->order = NavImage::where('nav_id', $request->input('id'))->max('order') + 1;
            }

            $image->file = $response['fileName'];
            $image->uuid = $response['uuid'];
            $image->extension = $response['fileExtension'];
            $image->size = $response['fileSize'];
            $image->save();

            $directory = asset('upload/' . str_replace(DIRECTORY_SEPARATOR, '/', $uploader->uploadDirectory) . '/' . $response['uuid']);

            $statusData = [];
            foreach ($this->datatables['nav_images']['columns'] as $column) {
                if ($column['id'] == 'is_active') {
                    $statusData = $column['status'];
                    break;
                }
            }

            $response['data'] = [
                'id' => $image->id,
                'order' => $image->order,
                'is_active' => '<a class="' . $statusData['class'] . '" data-ajax-queue="' . $statusData['queue'] . '" href="' . \Locales::route($statusData['route'], [$image->id, $statusData['rules'][$status]['status']]) . '">' . \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/' . $statusData['rules'][$status]['icon']), $statusData['rules'][$status]['title']) . '</a>',
                'name' => $image->name,
                'file' => '<a class="popup" href="' . asset($directory . '/' . $expandDirectory . '/' . $response['fileName']) . '">' . \HTML::image($directory . '/' . \Config::get('upload.thumbnailDirectory') . '/' . $response['fileName']) . '</a>',
                'size' => \App\Helpers\formatBytes($response['fileSize']),
            ];
        }

        return response()->json($response, $uploader->getStatus())->header('Content-Type', 'text/plain');
    }

    public function deleteImage(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.delete-image', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function destroyImage(DataTable $datatable, Nav $nav, NavImage $image, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = NavImage::find($request->input('id'))->lists('nav_id', 'uuid');

        if ($count > 0 && $image->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.imagesDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid => $page) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $image->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE nav_id = ? ORDER BY `order`', [$page]);

            $parentModel = $nav->select('join.is_multi_page')->leftJoin($nav->getTable() . ' as join', $nav->getTable() . '.parent', '=', 'join.id')->find($page);

            $enable = [];
            if ($parentModel->is_multi_page) {
                array_push($enable, $this->datatables[$request->input('table')]['buttons']['upload']['id']);
                $datatable->setOption('buttons', ['upload' => ['single' => 'true']], $request->input('table'));
                $datatable->setOption('expandDirectory', \Config::get('upload.pageDirectory') . '/', $request->input('table'));
            } else {
                $datatable->setOption('expandDirectory', \Config::get('upload.sliderDirectory') . '/', $request->input('table'));
            }

            $datatable->setup(NavImage::where('nav_id', $page), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => trans(\Locales::getNamespace() . '/forms.destroyedSuccessfully'),
                'closePopup' => true,
                'enable' => $enable,
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

    public function editImage(Request $request, $id = null)
    {
        $image = NavImage::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-image', compact('image', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateImage(DataTable $datatable, Nav $nav, NavImage $navImage, NavImageRequest $request)
    {
        $image = NavImage::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $image->order;
        } elseif ($order) {
            $navImage = $navImage->where('nav_id', $image->nav_id);
            $maxOrder = $navImage->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $image->order) {
                $navImage->where('order', '>=', $order)->where('order', '<', $image->order)->increment('order');
            } elseif ($order > $image->order) {
                $navImage->where('order', '<=', $order)->where('order', '>', $image->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($image->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);

            $parentModel = $nav->select('join.is_multi_page')->leftJoin($nav->getTable() . ' as join', $nav->getTable() . '.parent', '=', 'join.id')->find($image->nav_id);

            $image = $image->where('nav_id', $image->nav_id);

            if ($parentModel->is_multi_page) {
                $datatable->setOption('expandDirectory', \Config::get('upload.pageDirectory') . '/', $request->input('table'));
            } else {
                $datatable->setOption('expandDirectory', \Config::get('upload.sliderDirectory') . '/', $request->input('table'));
            }

            $datatable->setup($image, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $nav = Nav::findOrFail($id);

        $nav->is_active = $status;
        $nav->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['nav_page']['columns'] as $column) {
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

    public function changeImageStatus($id, $status)
    {
        $image = NavImage::findOrFail($id);

        $image->is_active = $status;
        $image->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['nav_images']['columns'] as $column) {
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
