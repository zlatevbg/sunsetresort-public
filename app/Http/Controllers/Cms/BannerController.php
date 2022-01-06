<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Banner;
use App\BannerImage;
use App\BannerFile;
use Storage;
use App\Http\Requests\Cms\BannerRequest;
use App\Http\Requests\Cms\BannerImageRequest;
use App\Http\Requests\Cms\BannerFileRequest;

class BannerController extends Controller {

    protected $route = 'banners';
    protected $uploadDirectory = 'banners';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'banners_category' => [
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
            'banners_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleBanners'),
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
                        'url' => \Locales::route($this->route . '/create'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createBannerButton'),
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
            'banners_image' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleImages'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-thumbnails popup-gallery',
                'uploadDirectory' => $this->uploadDirectory,
                'expandDirectory' => \Config::get('upload.bannerDirectory') . '/',
                'columns' => [
                    [
                        'selector' => 'banner_images.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'banner_images.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'banner_images.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.image'),
                        'order' => false,
                        'class' => 'text-center',
                        'thumbnail' => [
                            'selector' => ['banner_images.uuid', 'banner_images.title'],
                            'title' => 'title',
                            'id' => 'uuid',
                        ],
                    ],
                    [
                        'selector' => 'banner_images.size',
                        'id' => 'size',
                        'name' => trans(\Locales::getNamespace() . '/datatables.size'),
                        'filesize' => true,
                    ],
                ],
                'orderByColumn' => 0,
                'order' => 'asc',
                'buttons' => [
                    [
                        'upload' => true,
                        'single' => true,
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
            'banners_file' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleFiles'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'uploadDirectory' => $this->uploadDirectory,
                'columns' => [
                    [
                        'selector' => 'banner_file.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'banner_file.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'banner_file.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.file'),
                        'order' => false,
                        'class' => 'text-center',
                        'file' => [
                            'selector' => ['banner_file.title', 'banner_file.extension'],
                            'extension' => 'extension',
                            'title' => 'title',
                            'route' => $this->route . '/download',
                        ],
                    ],
                    [
                        'selector' => 'banner_file.size',
                        'id' => 'size',
                        'name' => trans(\Locales::getNamespace() . '/datatables.size'),
                        'filesize' => true,
                    ],
                ],
                'orderByColumn' => 0,
                'order' => 'asc',
                'buttons' => [
                    [
                        'upload-file' => true,
                        'upload' => true,
                        'single' => true,
                        'id' => 'fine-uploader-upload-file',
                        'url' => \Locales::route($this->route . '/upload-file'),
                        'class' => 'btn-primary js-upload',
                        'icon' => 'upload',
                        'name' => trans(\Locales::getNamespace() . '/forms.uploadButton'),
                    ],
                    [
                        'upload-file' => true,
                        'reupload' => true,
                        'id' => 'fine-uploader-reupload-file',
                        'url' => \Locales::route($this->route . '/upload-file'),
                        'class' => 'btn-primary disabled js-reupload',
                        'icon' => 'refresh',
                        'name' => trans(\Locales::getNamespace() . '/forms.replaceFileButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/edit-file'),
                        'class' => 'btn-warning disabled js-edit',
                        'icon' => 'edit',
                        'name' => trans(\Locales::getNamespace() . '/forms.editButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/delete-file'),
                        'class' => 'btn-danger disabled js-destroy',
                        'icon' => 'trash',
                        'name' => trans(\Locales::getNamespace() . '/forms.deleteButton'),
                    ],
                ],
            ],
        ];
    }

    public function index(DataTable $datatable, Banner $page, Request $request, $slugs = null)
    {
        $uploadDirectory = $this->uploadDirectory;
        $request->session()->put('ckfinderBaseUrl', $uploadDirectory . '/');
        if (!Storage::disk('local-public')->exists($uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploadDirectory);
        }

        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $pages = Banner::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
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
                    $datatable->setup($page, 'banners_page', $this->datatables['banners_page']);
                } else { // it's a page
                    $datatable->setup(BannerImage::where('banner_id', $row['id']), 'banners_image', $this->datatables['banners_image']);
                    $datatable->setOption('pageId', $row['id']);

                    $datatable->setup(BannerFile::where('banner_id', $row['id']), 'banners_file', $this->datatables['banners_file']);
                    $datatable->setOption('pageId', $row['id']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($page->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs for proper file upload actions
            $page = $page->where('parent', null);
            $datatable->setup($page, 'banners_category', $this->datatables['banners_category']);
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

    public function store(DataTable $datatable, Banner $page, BannerRequest $request)
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

        $newBanner = Banner::create($request->all());

        if ($newBanner->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newBanner->slug;
            if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                Storage::disk('local-public')->makeDirectory($uploadDirectory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityBanners'), 1)]);

            $datatable->setup($page, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
                'resetEditor' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityBanners'), 1)]);
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

    public function destroy(DataTable $datatable, Banner $page, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Banner::find($request->input('id'))->lists('slug');

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
                'closePopup' => true
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
        $page = Banner::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($page->is_category ? '-category' : ''), compact('page', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Banner $banner, BannerRequest $request)
    {
        $page = Banner::findOrFail($request->input('id'))->first();
        $oldBanner = $page->replicate();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $page->order;
        } elseif ($order) {
            $banner = $banner->where('parent', $page->parent);
            $maxOrder = $banner->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $page->order) {
                $banner->where('order', '>=', $order)->where('order', '<', $page->order)->increment('order');
            } elseif ($order > $page->order) {
                $banner->where('order', '<=', $order)->where('order', '>', $page->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($page->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($oldBanner->slug == $page->slug) {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $page->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $page->slug);
                }
            } else {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $oldBanner->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $oldBanner->slug);
                }

                Storage::disk('local-public')->move($uploadDirectory . $oldBanner->slug, $uploadDirectory . $page->slug);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($page->is_category ? 'entityCategories' : 'entityBanners'), 1)]);

            $page = $page->where('parent', $page->parent);

            $datatable->setup($page, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($page->is_category ? 'entityCategories' : 'entityBanners'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function upload(Request $request, FineUploader $uploader, $chunk = null)
    {
        $uploader->banner = true;
        $uploader->bannerSmall = true;
        $uploader->bannerMedium = true;
        $uploader->bannerLarge = true;

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

                $image = BannerImage::findOrFail($request->input('row'));

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $image->uuid);
            } else {
                $image = new BannerImage;
                $image->banner_id = $request->input('id');
            }

            $image->file = $response['fileName'];
            $image->uuid = $response['uuid'];
            $image->extension = $response['fileExtension'];
            $image->size = $response['fileSize'];
            $image->save();

            $directory = asset('upload/' . str_replace(DIRECTORY_SEPARATOR, '/', $uploader->uploadDirectory) . '/' . $response['uuid']);

            $response['data'] = [
                'id' => $image->id,
                'name' => $image->name,
                'file' => '<a class="popup" href="' . asset($directory . '/' . $this->datatables['banners_image']['expandDirectory'] . $response['fileName']) . '">' . \HTML::image($directory . '/' . \Config::get('upload.thumbnailDirectory') . '/' . $response['fileName']) . '</a>',
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

    public function destroyImage(DataTable $datatable, BannerImage $image, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = BannerImage::find($request->input('id'))->lists('banner_id', 'uuid');

        if ($count > 0 && $image->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.imagesDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid => $page) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            $datatable->setup(BannerImage::where('banner_id', $page), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            $enable = [];
            foreach ($this->datatables[$request->input('table')]['buttons'] as $button) {
                if (isset($button['upload'])) {
                    array_push($enable, $button['id']);
                    break;
                }
            }

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
        $image = BannerImage::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-image', compact('image', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateImage(DataTable $datatable, BannerImageRequest $request)
    {
        $image = BannerImage::findOrFail($request->input('id'))->first();

        $request->merge([
            'is_video' => $request->input('is_video', 0),
        ]);

        if ($image->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);

            $image = $image->where('banner_id', $image->banner_id);

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

    public function uploadFile(Request $request, FineUploader $uploader, $chunk = null)
    {
        $uploader->isImage = false;
        $uploader->isFile = true;
        $uploader->allowedExtensions = \Config::get('upload.fileExtensions');

        $slugs = $request->session()->get('routeSlugs', []);
        $uploader->uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory');
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

                $file = BannerFile::findOrFail($request->input('row'));

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $file->uuid);
            } else {
                $file = new BannerFile;
                $file->banner_id = $request->input('id');
            }

            $file->file = $response['fileName'];
            $file->uuid = $response['uuid'];
            $file->extension = $response['fileExtension'];
            $file->size = $response['fileSize'];
            $file->save();

            $route = '';
            foreach ($this->datatables['banners_file']['columns'] as $column) {
                if ($column['id'] == 'file') {
                    $route = $column['file']['route'];
                    break;
                }
            }

            $response['data'] = [
                'id' => $file->id,
                'name' => $file->name,
                'file' => '<a href="' . \Locales::route($route, $file->id) . '">' . \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/ext/' . $response['fileExtension'] . '.png'), $response['fileName']) . '</a>',
                'size' => \App\Helpers\formatBytes($response['fileSize']),
            ];
        }

        return response()->json($response, $uploader->getStatus())->header('Content-Type', 'text/plain');
    }

    public function deleteFile(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.delete-file', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function destroyFile(DataTable $datatable, BannerFile $file, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = BannerFile::find($request->input('id'))->lists('banner_id', 'uuid');

        if ($count > 0 && $file->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid => $page) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            $datatable->setup(BannerFile::where('banner_id', $page), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            $enable = [];
            foreach ($this->datatables[$request->input('table')]['buttons'] as $button) {
                if (isset($button['upload'])) {
                    array_push($enable, $button['id']);
                    break;
                }
            }

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

    public function editFile(Request $request, $id = null)
    {
        $file = BannerFile::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-file', compact('file', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateFile(DataTable $datatable, BannerFileRequest $request)
    {
        $file = BannerFile::findOrFail($request->input('id'))->first();

        if ($file->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityFiles', 1)]);

            $file = $file->where('banner_id', $file->banner_id);

            $datatable->setup($file, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityFiles', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $banner = Banner::findOrFail($id);

        $banner->is_active = $status;
        $banner->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['banners_page']['columns'] as $column) {
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

    public function download(Request $request, $id)
    {
        $file = BannerFile::findOrFail($id);

        $slugs = $request->session()->get('routeSlugs', []);
        $uploadDirectory = public_path('upload') . DIRECTORY_SEPARATOR . $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.filesDirectory') . DIRECTORY_SEPARATOR . $file->uuid . DIRECTORY_SEPARATOR . $file->file;

        return response()->download($uploadDirectory);
    }

}
