<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Gallery;
use App\GalleryImage;
use Storage;
use App\Http\Requests\Cms\GalleryRequest;
use App\Http\Requests\Cms\GalleryImageRequest;

class GalleryController extends Controller {

    protected $route = 'galleries';
    protected $uploadDirectory = 'galleries';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'gallery_category' => [
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
            'gallery_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleGalleries'),
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
                        'selector' => $this->route . '.directory',
                        'id' => 'directory',
                        'name' => trans(\Locales::getNamespace() . '/datatables.directory'),
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
            'gallery_images' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleImages'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-thumbnails popup-gallery',
                'uploadDirectory' => $this->uploadDirectory,
                'columns' => [
                    [
                        'selector' => 'gallery_images.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'gallery_images.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'gallery_images.is_active',
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
                        'selector' => 'gallery_images.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'gallery_images.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.image'),
                        'order' => false,
                        'class' => 'text-center',
                        'thumbnail' => [
                            'selector' => ['gallery_images.uuid', 'gallery_images.title', 'gallery_images.directory'],
                            'title' => 'title',
                            'id' => 'uuid',
                            'folder' => 'directory',
                        ],
                    ],
                    [
                        'selector' => 'gallery_images.size',
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
    }

    public function index(DataTable $datatable, Gallery $gallery, Request $request, $slugs = null)
    {
        $uploadDirectory = $this->uploadDirectory;
        $request->session()->put('ckfinderBaseUrl', $uploadDirectory . '/');
        if (!Storage::disk('local-public')->exists($uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploadDirectory);
        }

        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $galleries = Gallery::select('id', 'parent', 'slug', 'is_category', 'directory', 'name')->get()->toArray();
            $galleries = \App\Helpers\arrayToTree($galleries);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $galleries)) { // match slugs against the galleries array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $galleries);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs for proper datatables links

                if ($row['is_category']) { // it's a category
                    $request->session()->put($gallery->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $gallery = $gallery->where('parent', $row['id']);
                    $datatable->setup($gallery, 'gallery_page', $this->datatables['gallery_page']);
                }

                if ($row['directory']) { // it's a gallery
                    if ($row['is_category']) {
                        $datatable->setOption('buttons', ['upload' => ['single' => 'true']], 'gallery_images');
                    }

                    $datatable->setup(GalleryImage::where('directory', $row['directory']), 'gallery_images', $this->datatables['gallery_images']);
                    $datatable->setOption('pageId', $row['id']);

                    $request->session()->put('uploadDirectory', $row['directory']); // save current directory for proper file upload actions

                    $uploadDirectory .= DIRECTORY_SEPARATOR . $row['directory'];
                    if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                        Storage::disk('local-public')->makeDirectory($uploadDirectory);
                    }
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($gallery->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs for proper datatables links
            $request->session()->put('uploadDirectory', null); // save current directory for proper file upload actions
            $gallery = $gallery->where('parent', null);
            $datatable->setup($gallery, 'gallery_category', $this->datatables['gallery_category']);
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

    public function createCategory(Request $request, Gallery $gallery)
    {
        $table = $request->input('table');

        $parent = $request->session()->get($gallery->getTable() . 'Parent', null);

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create-category', compact('table', 'parent'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function store(DataTable $datatable, Gallery $gallery, GalleryRequest $request)
    {
        $parent = $request->session()->get($gallery->getTable() . 'Parent', null);

        $gallery = $gallery->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $gallery->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $gallery;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newGallery = Gallery::create($request->all());

        if ($newGallery->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . $newGallery->directory;
            if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                Storage::disk('local-public')->makeDirectory($uploadDirectory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityGalleries'), 1)]);

            $datatable->setup($gallery, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
                'resetEditor' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityGalleries'), 1)]);
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

    public function destroy(DataTable $datatable, Gallery $gallery, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Gallery::whereNotNull('directory')->find($request->input('id'))->lists('directory');

        if ($count > 0 && $gallery->destroy($request->input('id'))) {
            $parent = $request->session()->get($gallery->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $gallery->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);

            foreach ($directories as $directory) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . DIRECTORY_SEPARATOR . $directory);
            }

            $gallery = $gallery->where('parent', $parent);

            $datatable->setup($gallery, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $gallery = Gallery::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($gallery->is_category ? '-category' : ''), compact('gallery', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Gallery $galleryOrder, GalleryRequest $request)
    {
        $gallery = Gallery::findOrFail($request->input('id'))->first();
        $oldGallery = $gallery->replicate();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $gallery->order;
        } elseif ($order) {
            $galleryOrder = $galleryOrder->where('parent', $gallery->parent);
            $maxOrder = $galleryOrder->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $gallery->order) {
                $galleryOrder->where('order', '>=', $order)->where('order', '<', $gallery->order)->increment('order');
            } elseif ($order > $gallery->order) {
                $galleryOrder->where('order', '<=', $order)->where('order', '>', $gallery->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($gallery->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR;
            if (!$oldGallery->directory || $oldGallery->directory == $gallery->directory) {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $gallery->directory)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $gallery->directory);
                }
            } else {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $oldGallery->directory)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $oldGallery->directory);
                }

                Storage::disk('local-public')->move($uploadDirectory . $oldGallery->directory, $uploadDirectory . $gallery->directory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($gallery->is_category ? 'entityCategories' : 'entityGalleries'), 1)]);

            $gallery = $gallery->where('parent', $gallery->parent);

            $datatable->setup($gallery, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($gallery->is_category ? 'entityCategories' : 'entityGalleries'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function upload(Request $request, FineUploader $uploader, $chunk = null)
    {
        $uploader->resize = true;
        $uploader->watermark = true;
        $uploader->gallery = true;

        $uploader->uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . $request->session()->get('uploadDirectory');
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

                $image = GalleryImage::findOrFail($request->input('row'));
                $status = $image->is_active;

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $image->uuid);
            } else {
                $status = 1;
                $image = new GalleryImage;
                $image->directory = $request->session()->get('uploadDirectory');
                $image->order = GalleryImage::where('directory', $request->session()->get('uploadDirectory'))->max('order') + 1;
            }

            $image->file = $response['fileName'];
            $image->uuid = $response['uuid'];
            $image->extension = $response['fileExtension'];
            $image->size = $response['fileSize'];
            $image->save();

            $directory = asset('upload/' . str_replace(DIRECTORY_SEPARATOR, '/', $uploader->uploadDirectory) . '/' . $response['uuid']);

            $statusData = [];
            foreach ($this->datatables['gallery_images']['columns'] as $column) {
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
                'file' => '<a class="popup" href="' . asset($directory . '/' . $response['fileName']) . '">' . \HTML::image($directory . '/' . \Config::get('upload.thumbnailDirectory') . '/' . $response['fileName']) . '</a>',
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

    public function destroyImage(DataTable $datatable, Gallery $gallery, GalleryImage $image, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = GalleryImage::find($request->input('id'))->lists('uuid');

        if ($count > 0 && $image->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . $request->session()->get('uploadDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $image->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE directory = ? ORDER BY `order`', [$request->session()->get('uploadDirectory')]);

            $model = $gallery->select('is_category')->where('directory', $request->session()->get('uploadDirectory'))->first();

            $enable = [];
            if ($model->is_category) {
                array_push($enable, $this->datatables[$request->input('table')]['buttons']['upload']['id']);
                $datatable->setOption('buttons', ['upload' => ['single' => 'true']], $request->input('table'));
            }

            $datatable->setup(GalleryImage::where('directory', $request->session()->get('uploadDirectory')), $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $image = GalleryImage::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-image', compact('image', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateImage(DataTable $datatable, GalleryImage $galleryImage, GalleryImageRequest $request)
    {
        $image = GalleryImage::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $image->order;
        } elseif ($order) {
            $galleryImage = $galleryImage->where('directory', $image->directory);
            $maxOrder = $galleryImage->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $image->order) {
                $galleryImage->where('order', '>=', $order)->where('order', '<', $image->order)->increment('order');
            } elseif ($order > $image->order) {
                $galleryImage->where('order', '<=', $order)->where('order', '>', $image->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($image->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);

            $image = $image->where('directory', $image->directory);

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
        $gallery = Gallery::findOrFail($id);

        $gallery->is_active = $status;
        $gallery->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['gallery_page']['columns'] as $column) {
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
        $image = GalleryImage::findOrFail($id);

        $image->is_active = $status;
        $image->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['gallery_images']['columns'] as $column) {
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
