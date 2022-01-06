<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use Illuminate\Http\Request;
use App\Booking;
use Carbon\Carbon;

class BookingController extends Controller {

    protected $route = 'bookings';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            $this->route => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleBookings'),
                'url' => \Locales::route($this->route),
                'class' => 'table-checkbox table-striped table-bordered table-hover',
                'columns' => [
                    [
                        'selector' => 'bookings.id',
                        'id' => 'id',
                        'checkbox' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'id' => 'id',
                        'name' => trans(\Locales::getNamespace() . '/datatables.id'),
                        'search' => true,
                        'order' => false,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'bookings.created_at',
                        'id' => 'created_at',
                        'name' => trans(\Locales::getNamespace() . '/datatables.date'),
                        'search' => true,
                        'data' => [
                            'type' => 'sort',
                            'id' => 'created_at',
                            'date' => 'YYmmdd',
                        ],
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'bookings.from',
                        'id' => 'from',
                        'name' => trans(\Locales::getNamespace() . '/datatables.from'),
                        'search' => true,
                        'data' => [
                            'type' => 'sort',
                            'id' => 'from',
                            'date' => 'YYmmdd',
                        ],
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'bookings.to',
                        'id' => 'to',
                        'name' => trans(\Locales::getNamespace() . '/datatables.to'),
                        'search' => true,
                        'data' => [
                            'type' => 'sort',
                            'id' => 'to',
                            'date' => 'YYmmdd',
                        ],
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'bookings.nights',
                        'id' => 'nights',
                        'name' => trans(\Locales::getNamespace() . '/datatables.nights'),
                        'search' => true,
                        'class' => 'text-center',
                    ],
                    [
                        'selector' => 'bookings.price',
                        'id' => 'price',
                        'name' => trans(\Locales::getNamespace() . '/datatables.price'),
                        'search' => true,
                        'order' => false,
                        'class' => 'text-right',
                        'append' => [
                            'selector' => [$this->route . '.price'],
                            'simpleText' => ' лв.',
                        ],
                    ],
                    [
                        'selector' => 'bookings.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'bookings.email',
                        'id' => 'email',
                        'name' => trans(\Locales::getNamespace() . '/datatables.email'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'bookings.phone',
                        'id' => 'phone',
                        'name' => trans(\Locales::getNamespace() . '/datatables.phone'),
                        'search' => true,
                    ],
                    [
                        'selector' => 'bookings.transactionCode',
                        'id' => 'transactionCode',
                        'name' => trans(\Locales::getNamespace() . '/datatables.status'),
                        'search' => true,
                        'trans' => [
                            'selector' => [$this->route . '.transactionCode'],
                            'lang' => 'messages.responseCodes',
                            'none' => 'messages.responseCodes.other',
                        ],
                    ],
                ],
                'orderByColumn' => 2,
                'order' => 'desc',
                'buttons' => [],
            ],
        ];
    }

    public function index(DataTable $datatable, Booking $bookings, Request $request)
    {
        $datatable->setup($bookings, $this->route, $this->datatables[$this->route]);

        $datatables = $datatable->getTables();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($datatables);
        } else {
            return view(\Locales::getNamespace() . '.' . $this->route . '.index', compact('datatables'));
        }
    }
}
