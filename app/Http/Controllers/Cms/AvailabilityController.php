<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\AvailabilityPeriod;
use App\Availability;
use App\Room;
use App\View as Views;
use App\Http\Requests\Cms\AvailabilityRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AvailabilityController extends Controller {

    protected $route = 'availability';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            $this->route => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleAvailability'),
                'url' => \Locales::route($this->route),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'columns' => [
                    [
                        'selector' => 'availability_periods.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                        'with' => 'availability',
                    ],
                    [
                        'selector' => 'availability_periods.dfrom',
                        'id' => 'dfrom',
                        'name' => trans(\Locales::getNamespace() . '/datatables.date'),
                        'search' => true,
                        'data' => [
                            'type' => 'sort',
                            'id' => 'dfrom',
                            'date' => 'YYmmdd',
                        ],
                    ],/*
                    [
                        'selector' => 'availability_periods.dto',
                        'id' => 'dto',
                        'name' => trans(\Locales::getNamespace() . '/datatables.dto'),
                        'search' => true,
                        'data' => [
                            'type' => 'sort',
                            'id' => 'dto',
                            'date' => 'YYmmdd',
                        ],
                    ],*/
                    [
                        'id' => 'sv1',
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv1'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilitySV1',
                    ],
                    [
                        'id' => 'pv1',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv1'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityPV1',
                    ],
                    [
                        'id' => 'sv2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv2'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilitySV2',
                    ],
                    [
                        'id' => 'pv2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv2'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityPV2',
                    ],
                    [
                        'id' => 'sv3',
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv3'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilitySV3',
                    ],
                    [
                        'id' => 'pv3',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv3'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityPV3',
                    ],
                    [
                        'id' => 'studio',
                        'name' => trans(\Locales::getNamespace() . '/datatables.studio'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityStudio',
                    ],
                    [
                        'id' => 'e1',
                        'name' => trans(\Locales::getNamespace() . '/datatables.e1'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityE1',
                    ],
                    [
                        'id' => 'e2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.e2'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'AvailabilityE2',
                    ],/*
                    [
                        'id' => 'total',
                        'name' => trans(\Locales::getNamespace() . '/datatables.availability'),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'Availability',
                    ],*/
                ],
                'orderByColumn' => 1,
                'order' => 'asc',
                'buttons' => [
                    [
                        'save' => true,
                        'class' => 'btn-success js-save',
                        'icon' => 'save',
                        'name' => trans(\Locales::getNamespace() . '/forms.storeButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/create'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createButton'),
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

    public function index(DataTable $datatable, AvailabilityPeriod $period, Request $request)
    {
        $table = $request->input('table') ?: $this->route;

        $datatable->setup($period->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $this->route, $this->datatables[$this->route]);

        $datatables = $datatable->getTables();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($datatables);
        } else {
            return view(\Locales::getNamespace() . '.' . $this->route . '.index', compact('datatables', 'table'));
        }
    }

    public function create(Request $request)
    {
        $table = $request->input('table') ?: $this->route;

        $views = Views::where('parent', function ($query) {
            $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
        })->get();

        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->get();

        $dates = AvailabilityPeriod::select('dfrom', 'dto')->whereDate('dto', '>=', Carbon::now()->startOfYear())->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'views', 'rooms', 'dates'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function store(DataTable $datatable, AvailabilityPeriod $period, AvailabilityRequest $request)
    {
        $availability = [];
        $error = false;
        $dates = CarbonPeriod::create(Carbon::parse($request->input('dfrom')), Carbon::parse($request->input('dto')));
        foreach ($dates as $date) {
            $newPeriod = AvailabilityPeriod::create([
                'dfrom' => $date,
                'dto' => $date,
            ]);

            if ($newPeriod->id) {
                foreach ($request->input('availability') as $room => $rooms) {
                    foreach ($rooms as $view => $a) {
                        array_push($availability, [
                            'period_id' => $newPeriod->id,
                            'room' => $room,
                            'view' => $view,
                            'availability' => $a ?: 0,
                            'min_stay' => $request->input('min_stay')[$room][$view] ?: 0,
                        ]);
                    }
                }
            } else {
                $error = true;
            }
        }

        // $newPeriod = AvailabilityPeriod::create($request->all());

        // if ($newPeriod->id) {
        if (!$error) {
            // foreach ($request->input('availability') as $room => $rooms) {
                // foreach ($rooms as $view => $a) {
                    // array_push($availability, [
                        // 'period_id' => $newPeriod->id,
                        // 'room' => $room,
                        // 'view' => $view,
                        // 'availability' => $a ?: 0,
                    // ]);
                // }
            // }

            Availability::insert($availability);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);

            $datatable->setup($period->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function delete(Request $request)
    {
        $table = $request->input('table') ?: $this->route;

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.delete', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function destroy(DataTable $datatable, AvailabilityPeriod $period, Request $request)
    {
        $count = count($request->input('id'));

        if ($count > 0 && $period->destroy($request->input('id'))) {
            $datatable->setup($period->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
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
        /*$period = AvailabilityPeriod::with('availability')->findOrFail($id);

        $availability = [];
        foreach ($period->availability as $a) {
            $availability[$a->room][$a->view] = $a->availability;
        }

        $table = $request->input('table') ?: $this->route;

        $views = Views::where('parent', function ($query) {
            $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
        })->get();

        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->get();

        $dates = AvailabilityPeriod::select('dfrom', 'dto')->where('id', '!=', $period->id)->whereDate('dto', '>=', Carbon::now()->startOfYear())->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('period', 'table', 'availability', 'views', 'rooms', 'dates'));
        $sections = $view->renderSections();
        return $sections['content'];*/

        $period = AvailabilityPeriod::with('availability')->findOrFail($id);

        $availability = [];
        $min_stay = [];
        foreach ($period->availability as $a) {
            $availability[$a->room][$a->view] = $a->availability;
            $min_stay[$a->room][$a->view] = $a->min_stay;
        }

        $table = $request->input('table') ?: $this->route;

        $views = Views::where('parent', function ($query) {
            $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
        })->get();

        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->get();

        $dates = AvailabilityPeriod::select('dfrom', 'dto')->whereDate('dto', '>=', Carbon::now()->startOfYear())->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'views', 'rooms', 'dates', 'period', 'availability', 'min_stay'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, AvailabilityRequest $request)
    {
        /*$period = AvailabilityPeriod::findOrFail($request->input('id'))->first();

        if ($period->update($request->all())) {
            Availability::where('period_id', $period->id)->delete();

            $availability = [];
            foreach ($request->input('availability') as $room => $rooms) {
                foreach ($rooms as $view => $a) {
                    array_push($availability, [
                        'period_id' => $period->id,
                        'room' => $room,
                        'view' => $view,
                        'availability' => $a ?: 0,
                    ]);
                }
            }

            Availability::insert($availability);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);

            $datatable->setup($period->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }*/

        $availability = [];
        $error = false;
        $dates = CarbonPeriod::create(Carbon::parse($request->input('dfrom')), Carbon::parse($request->input('dto')));
        foreach ($dates as $date) {
            $newPeriod = AvailabilityPeriod::firstOrNew(['dfrom' => $date]);
            $newPeriod->fill([
                'dfrom' => $date,
                'dto' => $date,
            ])->save();

            if ($newPeriod->id) {
                Availability::where('period_id', $newPeriod->id)->delete();
                foreach ($request->input('availability') as $room => $rooms) {
                    foreach ($rooms as $view => $a) {
                        array_push($availability, [
                            'period_id' => $newPeriod->id,
                            'room' => $room,
                            'view' => $view,
                            'availability' => $a ?: 0,
                            'min_stay' => $request->input('min_stay')[$room][$view] ?: 0,
                        ]);
                    }
                }
            } else {
                $error = true;
            }
        }

        // $newPeriod = AvailabilityPeriod::create($request->all());

        // if ($newPeriod->id) {
        if (!$error) {
            /*foreach ($request->input('availability') as $room => $rooms) {
                foreach ($rooms as $view => $a) {
                    array_push($availability, [
                        'period_id' => $newPeriod->id,
                        'room' => $room,
                        'view' => $view,
                        'availability' => $a ?: 0,
                    ]);
                }
            }*/

            Availability::insert($availability);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);

            $datatable->setup((new AvailabilityPeriod())->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function save(DataTable $datatable, Request $request)
    {
        $availability = [];
        Availability::whereIn('period_id', array_keys($request->input('dates')))->delete();
        foreach ($request->input('dates') as $id => $date) {
            foreach ($date as $room => $rooms) {
                foreach ($rooms as $view => $a) {
                    array_push($availability, [
                        'period_id' => $id,
                        'room' => $room,
                        'view' => $view,
                        'availability' => $a ?: 0,
                        'min_stay' => $request->input('min_stay')[$id][$room][$view] ?: 0,
                    ]);
                }
            }
        }

        Availability::insert($availability);

        $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityAvailability', 1)]);

        $datatable->setup((new AvailabilityPeriod())->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
        $datatables = $datatable->getTables();

        return response()->json($datatables + [
            'success' => $successMessage,
        ]);
    }

}
