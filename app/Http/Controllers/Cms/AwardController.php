<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Award;
use App\AwardImage;
use Storage;
use App\Http\Requests\Cms\AwardRequest;
use App\Http\Requests\Cms\AwardImageRequest;

class AwardController extends Controller {

    protected $route = 'awards';
    protected $uploadDirectory = 'awards';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'award_category' => [
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
            'award_page' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleYears'),
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
                        'name' => trans(\Locales::getNamespace() . '/datatables.year'),
                        'search' => true,
                        'link' => [
                            'selector' => [$this->route . '.is_category', $this->route . '.slug'],
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
                'orderByColumn' => 1,
                'order' => 'desc',
                'buttons' => [
                    [
                        'url' => \Locales::route($this->route . '/create'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createYearButton'),
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
            'award_images' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleAwards'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-thumbnails popup-gallery',
                'uploadDirectory' => $this->uploadDirectory,
                'columns' => [
                    [
                        'selector' => 'award_images.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'award_images.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'award_images.is_active',
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
                        'selector' => 'award_images.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'award_images.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.image'),
                        'order' => false,
                        'class' => 'text-center',
                        'thumbnail' => [
                            'selector' => ['award_images.uuid'],
                            'id' => 'uuid',
                        ],
                    ],
                    [
                        'selector' => 'award_images.size',
                        'id' => 'size',
                        'name' => trans(\Locales::getNamespace() . '/datatables.size'),
                        'filesize' => true,
                    ],
                ],
                'orderByColumn' => 'order',
                'order' => 'asc',
                'buttons' => [
                    [
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

    public function index(DataTable $datatable, Award $award, Request $request, $slugs = null)
    {
        $uploadDirectory = $this->uploadDirectory;
        if (!Storage::disk('local-public')->exists($uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploadDirectory);
        }

        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $awards = Award::select('id', 'parent', 'slug', 'is_category', 'name')->get()->toArray();
            $awards = \App\Helpers\arrayToTree($awards);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $awards)) { // match slugs against the awards array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $awards);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs for proper file upload actions

                foreach($slugsArray as $slug) { // ensure the list of the current subdirectories exist for proper upload actions
                    $uploadDirectory .= DIRECTORY_SEPARATOR . $slug;
                    if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                        Storage::disk('local-public')->makeDirectory($uploadDirectory);
                    }
                }

                if ($row['is_category']) { // it's a category
                    $request->session()->put($award->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                    $award = $award->where('parent', $row['id']);
                    $datatable->setup($award, 'award_page', $this->datatables['award_page']);
                } else { // it's a page
                    $datatable->setup(AwardImage::where('award_id', $row['id']), 'award_images', $this->datatables['award_images']);
                    $datatable->setOption('pageId', $row['id']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($award->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs for proper file upload actions
            $award = $award->where('parent', null);
            $datatable->setup($award, 'award_category', $this->datatables['award_category']);
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

    public function store(DataTable $datatable, Award $award, AwardRequest $request)
    {
        $parent = $request->session()->get($award->getTable() . 'Parent', null);

        $award = $award->where('parent', $parent);

        $request->merge([
            'parent' => $parent,
        ]);

        $newAward = Award::create($request->all());

        if ($newAward->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newAward->slug;
            if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                Storage::disk('local-public')->makeDirectory($uploadDirectory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityAwards'), 1)]);

            $datatable->setup($award, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityAwards'), 1)]);
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

    public function destroy(DataTable $datatable, Award $award, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Award::find($request->input('id'))->lists('slug');

        if ($count > 0 && $award->destroy($request->input('id'))) {
            $parent = $request->session()->get($award->getTable() . 'Parent', null);

            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            foreach ($directories as $directory) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $directory);
            }

            $award = $award->where('parent', $parent);

            $datatable->setup($award, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $award = Award::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create' . ($award->is_category ? '-category' : ''), compact('award', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, AwardRequest $request)
    {
        $award = Award::findOrFail($request->input('id'))->first();
        $oldAward = $award->replicate();

        if ($award->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($oldAward->slug == $award->slug) {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $award->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $award->slug);
                }
            } else {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $oldAward->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $oldAward->slug);
                }

                Storage::disk('local-public')->move($uploadDirectory . $oldAward->slug, $uploadDirectory . $award->slug);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($award->is_category ? 'entityCategories' : 'entityAwards'), 1)]);

            $award = $award->where('parent', $award->parent);

            $datatable->setup($award, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($award->is_category ? 'entityCategories' : 'entityAwards'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function upload(Request $request, FineUploader $uploader, $chunk = null)
    {
        $uploader->resize = true;
        $uploader->award = true;

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

                $image = AwardImage::findOrFail($request->input('row'));
                $status = $image->is_active;

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $image->uuid);
            } else {
                $status = 1;
                $image = new AwardImage;
                $image->award_id = $request->input('id');
                $image->order = AwardImage::where('award_id', $request->input('id'))->max('order') + 1;
            }

            $image->file = $response['fileName'];
            $image->uuid = $response['uuid'];
            $image->extension = $response['fileExtension'];
            $image->size = $response['fileSize'];
            $image->save();

            $directory = asset('upload/' . str_replace(DIRECTORY_SEPARATOR, '/', $uploader->uploadDirectory) . '/' . $response['uuid']);

            $statusData = [];
            foreach ($this->datatables['award_images']['columns'] as $column) {
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

    public function destroyImage(DataTable $datatable, AwardImage $image, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = AwardImage::find($request->input('id'))->lists('award_id', 'uuid');

        if ($count > 0 && $image->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.imagesDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid => $award) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $image->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE award_id = ? ORDER BY `order`', [$award]);

            $datatable->setup(AwardImage::where('award_id', $award), $request->input('table'), $this->datatables[$request->input('table')], true);
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

    public function editImage(Request $request, $id = null)
    {
        $image = AwardImage::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-image', compact('image', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateImage(DataTable $datatable, AwardImage $awardImage, AwardImageRequest $request)
    {
        $image = AwardImage::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $image->order;
        } elseif ($order) {
            $awardImage = $awardImage->where('award_id', $image->award_id);
            $maxOrder = $awardImage->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $image->order) {
                $awardImage->where('order', '>=', $order)->where('order', '<', $image->order)->increment('order');
            } elseif ($order > $image->order) {
                $awardImage->where('order', '<=', $order)->where('order', '>', $image->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($image->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);

            $image = $image->where('award_id', $image->award_id);

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
        $award = Award::findOrFail($id);

        $award->is_active = $status;
        $award->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['award_page']['columns'] as $column) {
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
        $image = AwardImage::findOrFail($id);

        $image->is_active = $status;
        $image->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['award_images']['columns'] as $column) {
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
