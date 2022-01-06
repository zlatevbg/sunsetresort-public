<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\PricePeriod;
use App\Price;
use App\Meal;
use App\Room;
use App\View as Views;
use App\Http\Requests\Cms\PriceRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PriceController extends Controller {

    protected $route = 'prices';
    protected $datatables;

    public function __construct()
    {
        $meals = Meal::where('parent', function ($query) {
            $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
        })->get();

        $meals->transform(function ($item, $key) {
            $item->slug = strtoupper($item->slug);
            return $item;
        });

        $meals = '<span class="form-control-discount">' . $meals->implode('slug', '</span><span class="form-control-discount">') . '</span>';

        $this->datatables = [
            $this->route => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titlePrices'),
                'url' => \Locales::route($this->route),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'columns' => [
                    [
                        'selector' => 'price_periods.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center vertical-center',
                        'with' => 'prices',
                    ],
                    [
                        'selector' => 'price_periods.dfrom',
                        'id' => 'dfrom',
                        'name' => trans(\Locales::getNamespace() . '/datatables.date'),
                        'search' => true,
                        'class' => 'text-center vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'dfrom',
                            'date' => 'YYmmdd',
                        ],
                    ],/*
                    [
                        'selector' => 'price_periods.dto',
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
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv1') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesSV1',
                    ],
                    [
                        'id' => 'pv1',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv1') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesPV1',
                    ],
                    [
                        'id' => 'sv2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv2') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesSV2',
                    ],
                    [
                        'id' => 'pv2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv2') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesPV2',
                    ],
                    [
                        'id' => 'sv3',
                        'name' => trans(\Locales::getNamespace() . '/datatables.sv3') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesSV3',
                    ],
                    [
                        'id' => 'pv3',
                        'name' => trans(\Locales::getNamespace() . '/datatables.pv3') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesPV3',
                    ],
                    [
                        'id' => 'studio',
                        'name' => trans(\Locales::getNamespace() . '/datatables.studio') . '<br>' . str_replace('<span class="form-control-discount">SC</span>', '', $meals),
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesStudio',
                    ],
                    [
                        'id' => 'e1',
                        'name' => trans(\Locales::getNamespace() . '/datatables.e1') . '<br><span class="form-control-discount">SC</span>',
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesE1',
                    ],
                    [
                        'id' => 'e2',
                        'name' => trans(\Locales::getNamespace() . '/datatables.e2') . '<br><span class="form-control-discount">SC</span>',
                        'search' => false,
                        'order' => false,
                        'class' => 'text-center',
                        'process' => 'PricesE2',
                    ],
                ],
                'orderByColumn' => 1,
                'order' => 'desc',
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

    public function index(DataTable $datatable, PricePeriod $period, Request $request)
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

        $meals = Meal::where('parent', function ($query) {
            $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
        })->get();

        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->get();

        $dates = PricePeriod::select('dfrom', 'dto')->whereDate('dto', '>=', Carbon::now()->startOfYear())->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'views', 'meals', 'rooms', 'dates'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function store(DataTable $datatable, PricePeriod $period, PriceRequest $request)
    {
        /*$newPeriod = PricePeriod::create($request->all());

        if ($newPeriod->id) {
            $prices = [];
            foreach ($request->input('prices') as $room => $rooms) {
                foreach ($rooms as $view => $views) {
                    foreach ($views as $meal => $price) {
                        array_push($prices, [
                            'period_id' => $newPeriod->id,
                            'room' => $room,
                            'view' => $view,
                            'meal' => $meal,
                            'price' => $price['price'] ?: 0,
                        ]);
                    }
                }
            }

            Price::insert($prices);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);

            $datatable->setup($period, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }*/

        $prices = [];
        $error = false;
        $dates = CarbonPeriod::create(Carbon::parse($request->input('dfrom')), Carbon::parse($request->input('dto')));
        foreach ($dates as $date) {
            $newPeriod = PricePeriod::firstOrNew(['dfrom' => $date]);
            $newPeriod->fill([
                'dfrom' => $date,
                'dto' => $date,
            ])->save();

            if ($newPeriod->id) {
                Price::where('period_id', $newPeriod->id)->delete();
                foreach ($request->input('prices') as $room => $rooms) {
                     foreach ($rooms as $view => $views) {
                        foreach ($views as $meal => $price) {
                            array_push($prices, [
                                'period_id' => $newPeriod->id,
                                'room' => $room,
                                'view' => $view,
                                'meal' => $meal,
                                'price' => $price['price'] ?: null,
                            ]);
                        }
                    }
                }
            } else {
                $error = true;
            }
        }

        if (!$error) {
            Price::insert($prices);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);

            $datatable->setup($period->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);
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

    public function destroy(DataTable $datatable, PricePeriod $period, Request $request)
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
        $period = PricePeriod::with('prices')->findOrFail($id);

        $prices = [];
        foreach ($period->prices as $price) {
            $prices[$price->room][$price->view][$price->meal]['price'] = $price->price;
        }

        $table = $request->input('table') ?: $this->route;

        $views = Views::where('parent', function ($query) {
            $query->select('id')->from('views')->where('slug', \Locales::getCurrent());
        })->get();

        $meals = Meal::where('parent', function ($query) {
            $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
        })->get();

        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->get();

        $dates = PricePeriod::select('dfrom', 'dto')->whereDate('dto', '>=', Carbon::now()->startOfYear())->where('id', '!=', $period->id)->orderBy('dfrom')->get()->toArray();
        array_walk($dates, function (&$date) {
            $date['dfrom'] = Carbon::parse($date['dfrom'])->format('Ymd');
            $date['dto'] = Carbon::parse($date['dto'])->format('Ymd');
        });

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('table', 'period', 'prices', 'views', 'meals', 'rooms', 'dates'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, PriceRequest $request)
    {
        /*$period = PricePeriod::findOrFail($request->input('id'))->first();

        if ($period->update($request->all())) {
            Price::where('period_id', $period->id)->delete();

            $prices = [];
            foreach ($request->input('prices') as $room => $rooms) {
                foreach ($rooms as $view => $views) {
                    foreach ($views as $meal => $price) {
                        array_push($prices, [
                            'period_id' => $period->id,
                            'room' => $room,
                            'view' => $view,
                            'meal' => $meal,
                            'price' => $price['price'] ?: 0,
                        ]);
                    }
                }
            }

            Price::insert($prices);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);

            $datatable->setup($period, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }*/

        $prices = [];
        $error = false;
        $dates = CarbonPeriod::create(Carbon::parse($request->input('dfrom')), Carbon::parse($request->input('dto')));

        $periods = PricePeriod::with('prices')->whereBetween('dfrom', [Carbon::parse($request->input('dfrom')), Carbon::parse($request->input('dto'))])->get();

        $discounts = [];
        foreach ($periods as $period) {
            foreach ($period->prices as $price) {
                $discounts[$period->id][$price->room][$price->view][$price->meal]['discount'] = $price->discount;
            }
        }

        foreach ($dates as $date) {
            $newPeriod = PricePeriod::firstOrNew(['dfrom' => $date]);
            $newPeriod->fill([
                'dfrom' => $date,
                'dto' => $date,
            ])->save();

            if ($newPeriod->id) {
                Price::where('period_id', $newPeriod->id)->delete();
                foreach ($request->input('prices') as $room => $rooms) {
                     foreach ($rooms as $view => $views) {
                        foreach ($views as $meal => $price) {
                            array_push($prices, [
                                'period_id' => $newPeriod->id,
                                'room' => $room,
                                'view' => $view,
                                'meal' => $meal,
                                'discount' => ((isset($discounts[$newPeriod->id]) && isset($discounts[$newPeriod->id][$room]) && isset($discounts[$newPeriod->id][$room][$view]) && isset($discounts[$newPeriod->id][$room][$view][$meal])) ? $discounts[$newPeriod->id][$room][$view][$meal]['discount'] : null),
                                'price' => $price['price'] ?: null,
                            ]);
                        }
                    }
                }
            } else {
                $error = true;
            }
        }

        if (!$error) {
            Price::insert($prices);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);

            $datatable->setup((new PricePeriod())->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function save(DataTable $datatable, Request $request)
    {
        $ids = array_keys($request->input('dates'));
        $prices = [];
        $periods = PricePeriod::with('prices')->whereIn('id', $ids)->get();

        $discounts = [];
        foreach ($periods as $period) {
            foreach ($period->prices as $price) {
                $discounts[$period->id][$price->room][$price->view][$price->meal]['discount'] = $price->discount;
            }
        }

        Price::whereIn('period_id', $ids)->delete();

        foreach ($request->input('dates') as $id => $date) {
            foreach ($date as $room => $rooms) {
                if (is_array($rooms)) {
                    foreach ($rooms as $view => $views) {
                        foreach ($views as $meal => $price) {
                            array_push($prices, [
                                'period_id' => $id,
                                'room' => $room,
                                'view' => $view,
                                'meal' => $meal,
                                'discount' => ((isset($discounts[$id]) && isset($discounts[$id][$room]) && isset($discounts[$id][$room][$view]) && isset($discounts[$id][$room][$view][$meal])) ? $discounts[$id][$room][$view][$meal]['discount'] : null),
                                'price' => $price ?: null,
                            ]);
                        }
                    }
                }
            }
        }

        Price::insert($prices);

        $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.entityPrices', 1)]);

        $datatable->setup((new PricePeriod())->where('dto', '>=', Carbon::now()->setTime(0, 0, 0)), $request->input('table'), $this->datatables[$request->input('table')], true);
        $datatables = $datatable->getTables();

        return response()->json($datatables + [
            'success' => $successMessage,
        ]);
    }

}
