<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Partner;
use App\PartnerLogo;
use Storage;
use App\Http\Requests\Cms\PartnerRequest;
use App\Http\Requests\Cms\PartnerLogoRequest;

class PartnerController extends Controller {

    protected $route = 'partners';
    protected $uploadDirectory = 'partners';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            $this->route => [
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
                            'icon' => 'file',
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
                ],
                'orderByColumn' => 1,
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
            'partner_logo' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titlePartners'),
                'url' => \Locales::route($this->route, true),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-thumbnails popup-gallery',
                'uploadDirectory' => $this->uploadDirectory,
                'expandDirectory' => \Config::get('upload.partnerDirectory') . '/',
                'columns' => [
                    [
                        'selector' => 'partner_logo.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'partner_logo.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'partner_logo.is_active',
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
                    [
                        'selector' => 'partner_logo.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                        'link' => [
                            'selector' => ['partner_logo.url'],
                            'url' => 'url',
                        ],
                    ],
                    [
                        'selector' => 'partner_logo.file',
                        'id' => 'file',
                        'name' => trans(\Locales::getNamespace() . '/datatables.logo'),
                        'order' => false,
                        'class' => 'text-center',
                        'thumbnail' => [
                            'selector' => ['partner_logo.uuid'],
                            'title' => 'name',
                            'id' => 'uuid',
                        ],
                    ],
                    [
                        'selector' => 'partner_logo.size',
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
                        'url' => \Locales::route($this->route . '/edit-logo'),
                        'class' => 'btn-warning disabled js-edit',
                        'icon' => 'edit',
                        'name' => trans(\Locales::getNamespace() . '/forms.editButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/delete-logo'),
                        'class' => 'btn-danger disabled js-destroy',
                        'icon' => 'trash',
                        'name' => trans(\Locales::getNamespace() . '/forms.deleteButton'),
                    ],
                ],
            ],
        ];
    }

    public function index(DataTable $datatable, Partner $partner, Request $request, $slug = null)
    {
        $uploadDirectory = $this->uploadDirectory;
        if (!Storage::disk('local-public')->exists($uploadDirectory)) {
            Storage::disk('local-public')->makeDirectory($uploadDirectory);
        }

        $breadcrumbs = [];
        if ($slug) {
            $row = $partner->where('slug', $slug)->firstOrFail();
            $breadcrumbs = [$row->toArray()];

            $request->session()->put('routeSlugs', explode('/', $slug)); // save current slug for proper file upload actions
            $datatable->setup(PartnerLogo::where('partner_id', $row['id']), 'partner_logo', $this->datatables['partner_logo']);
            $datatable->setOption('pageId', $row['id']);
        } else {
            $request->session()->put('routeSlugs', []); // save current slugs for proper file upload actions
            $datatable->setup($partner, $this->route, $this->datatables[$this->route]);
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

    public function store(DataTable $datatable, Partner $partner, PartnerRequest $request)
    {
        $newPartner = Partner::create($request->all());

        if ($newPartner->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $newPartner->slug;
            if (!Storage::disk('local-public')->exists($uploadDirectory)) {
                Storage::disk('local-public')->makeDirectory($uploadDirectory);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPages', 1)]);

            $datatable->setup($partner, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPages', 1)]);
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

    public function destroy(DataTable $datatable, Partner $partner, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Partner::find($request->input('id'))->lists('slug');

        if ($count > 0 && $partner->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            foreach ($directories as $directory) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $directory);
            }

            $datatable->setup($partner, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $partner = Partner::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('partner', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, PartnerRequest $request)
    {
        $partner = Partner::findOrFail($request->input('id'))->first();
        $oldPartner = $partner->replicate();

        if ($partner->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $uploadDirectory = $this->uploadDirectory . DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            if ($oldPartner->slug == $partner->slug) {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $partner->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $partner->slug);
                }
            } else {
                if (!Storage::disk('local-public')->exists($uploadDirectory . $oldPartner->slug)) {
                    Storage::disk('local-public')->makeDirectory($uploadDirectory . $oldPartner->slug);
                }

                Storage::disk('local-public')->move($uploadDirectory . $oldPartner->slug, $uploadDirectory . $partner->slug);
            }

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPages', 1)]);

            $datatable->setup($partner, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPages', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function upload(Request $request, FineUploader $uploader, $chunk = null)
    {
        $uploader->partner = true;

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

                $logo = PartnerLogo::findOrFail($request->input('row'));
                $status = $logo->is_active;

                Storage::disk('local-public')->deleteDirectory($uploader->uploadDirectory . '/' . $logo->uuid);
            } else {
                $status = 1;
                $logo = new PartnerLogo;
                $logo->partner_id = $request->input('id');
                $logo->order = PartnerLogo::where('partner_id', $request->input('id'))->max('order') + 1;
            }

            $logo->file = $response['fileName'];
            $logo->uuid = $response['uuid'];
            $logo->extension = $response['fileExtension'];
            $logo->size = $response['fileSize'];
            $logo->save();

            $directory = asset('upload/' . str_replace(DIRECTORY_SEPARATOR, '/', $uploader->uploadDirectory) . '/' . $response['uuid']);

            $statusData = [];
            foreach ($this->datatables['partner_logo']['columns'] as $column) {
                if ($column['id'] == 'is_active') {
                    $statusData = $column['status'];
                    break;
                }
            }

            $response['data'] = [
                'id' => $logo->id,
                'order' => $logo->order,
                'is_active' => '<a class="' . $statusData['class'] . '" data-ajax-queue="' . $statusData['queue'] . '" href="' . \Locales::route($statusData['route'], [$logo->id, $statusData['rules'][$status]['status']]) . '">' . \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/' . $statusData['rules'][$status]['icon']), $statusData['rules'][$status]['title']) . '</a>',
                'name' => $logo->name,
                'file' => '<a class="popup" href="' . asset($directory . '/' . $this->datatables['partner_logo']['expandDirectory'] . $response['fileName']) . '">' . \HTML::image($directory . '/' . \Config::get('upload.thumbnailDirectory') . '/' . $response['fileName']) . '</a>',
                'size' => \App\Helpers\formatBytes($response['fileSize']),
            ];
        }

        return response()->json($response, $uploader->getStatus())->header('Content-Type', 'text/plain');
    }

    public function deleteLogo(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.delete-logo', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function destroyLogo(DataTable $datatable, PartnerLogo $logo, Request $request)
    {
        $count = count($request->input('id'));

        $uuids = PartnerLogo::find($request->input('id'))->lists('partner_id', 'uuid');

        if ($count > 0 && $logo->destroy($request->input('id'))) {
            $slugs = $request->session()->get('routeSlugs', []);
            $path = DIRECTORY_SEPARATOR . trim(implode(DIRECTORY_SEPARATOR, $slugs), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . \Config::get('upload.imagesDirectory') . DIRECTORY_SEPARATOR;
            foreach ($uuids as $uuid => $partner) {
                Storage::disk('local-public')->deleteDirectory($this->uploadDirectory . $path . $uuid);
            }

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $logo->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE partner_id = ? ORDER BY `order`', [$partner]);

            $datatable->setup(PartnerLogo::where('partner_id', $partner), $request->input('table'), $this->datatables[$request->input('table')], true);
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

    public function editLogo(Request $request, $id = null)
    {
        $logo = PartnerLogo::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.edit-logo', compact('logo', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function updateLogo(DataTable $datatable, PartnerLogo $partnerLogo, PartnerLogoRequest $request)
    {
        $logo = PartnerLogo::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $logo->order;
        } elseif ($order) {
            $partnerLogo = $partnerLogo->where('partner_id', $logo->partner_id);
            $maxOrder = $partnerLogo->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $logo->order) {
                $partnerLogo->where('order', '>=', $order)->where('order', '<', $logo->order)->increment('order');
            } elseif ($order > $logo->order) {
                $partnerLogo->where('order', '<=', $order)->where('order', '>', $logo->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
            'is_video' => $request->input('is_video', 0),
        ]);

        if ($logo->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);

            $logo = $logo->where('partner_id', $logo->partner_id);

            $datatable->setup($logo, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityImages', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $logo = PartnerLogo::findOrFail($id);

        $logo->is_active = $status;
        $logo->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['partner_logo']['columns'] as $column) {
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
