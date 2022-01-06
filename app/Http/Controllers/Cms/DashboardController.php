<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\DataTable;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests\Cms\TransactionRequest;
use App\TransactionTest;

class DashboardController extends Controller {

    protected $route = 'dashboard';
    protected $datatables;
    public $prefix;

    public function __construct()
    {
        if (Auth::user()->role_id == 3) {
            $this->prefix = 'TEST-SRB' . date('Y') . '-';

            $this->datatables = [
                $this->route => [
                    'title' => 'Онлайн резервации - ТЕСТ!',
                    'url' => \Locales::route($this->route),
                    'class' => 'table-checkbox table-striped table-bordered table-hover',
                    'selectors' => ['transactions_test.deleted_at', 'transactions_test.payment', 'transactions_test.user_id', 'users.name as user'],
                    'columns' => [
                        [
                            'selector' => 'transactions_test.id',
                            'id' => 'checkbox',
                            /*'id' => 'id',
                            'checkbox' => true,*/
                            'order' => false,
                            'class' => 'text-center vertical-center',
                            'width' => '1.25em',
                            'replace' => [
                                'id' => 'id',
                                'rules' => [
                                    0 => [
                                        'column' => 'deleted_at',
                                        'value' => null,
                                        'checkbox' => true,
                                    ],
                                ],
                            ],
                        ],
                        [
                            'selector' => 'transactions_test.id',
                            'id' => 'id',
                            'name' => trans(\Locales::getNamespace() . '/datatables.id'),
                            'order' => false,
                            'search' => true,
                            'class' => 'vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.created_at',
                            'id' => 'created_at',
                            'name' => trans(\Locales::getNamespace() . '/datatables.date'),
                            'search' => true,
                            'data' => [
                                'type' => 'sort',
                                'id' => 'created_at',
                                'date' => 'Ymd',
                            ],
                            'class' => 'text-center vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.from',
                            'id' => 'from',
                            'name' => trans(\Locales::getNamespace() . '/datatables.from'),
                            'search' => true,
                            'data' => [
                                'type' => 'sort',
                                'id' => 'from',
                                'date' => 'YYmmdd',
                            ],
                            'class' => 'text-center vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.to',
                            'id' => 'to',
                            'name' => trans(\Locales::getNamespace() . '/datatables.to'),
                            'search' => true,
                            'data' => [
                                'type' => 'sort',
                                'id' => 'to',
                                'date' => 'YYmmdd',
                            ],
                            'class' => 'text-center vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.nights',
                            'id' => 'nights',
                            'name' => trans(\Locales::getNamespace() . '/datatables.nights'),
                            'search' => true,
                            'class' => 'text-center vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.price',
                            'id' => 'price',
                            'name' => trans(\Locales::getNamespace() . '/datatables.price'),
                            'search' => true,
                            'order' => false,
                            'class' => 'text-right vertical-center',
                            'data' => [
                                'type' => 'sort',
                                'id' => 'price',
                                'cast' => 'int',
                            ],
                            'append' => [
                                'selector' => ['transactions_test.price'],
                                'simpleText' => ' лв.',
                            ],
                        ],
                        [
                            'selector' => 'transactions_test.name',
                            'id' => 'name',
                            'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                            'search' => true,
                            'class' => 'vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.email',
                            'id' => 'email',
                            'name' => trans(\Locales::getNamespace() . '/datatables.email'),
                            'search' => true,
                            'class' => 'vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.phone',
                            'id' => 'phone',
                            'name' => trans(\Locales::getNamespace() . '/datatables.phone'),
                            'search' => true,
                            'class' => 'vertical-center',
                        ],
                        [
                            'selector' => 'transactions_test.status',
                            'id' => 'status',
                            'search' => true,
                            'name' => trans(\Locales::getNamespace() . '/datatables.status'),
                            'class' => 'text-center vertical-center',
                            'join' => [
                                'table' => 'users',
                                'localColumn' => 'users.id',
                                'constrain' => '=',
                                'foreignColumn' => 'transactions_test.user_id',
                            ],
                            'replace' => [
                                'rules' => [
                                    0 => [
                                        'value' => 'used',
                                        'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.used'),
                                        'append' => 'deleted_at',
                                    ],
                                    1 => [
                                        'value' => 'refunded',
                                        'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.refunded'),
                                        'append' => 'deleted_at',
                                    ],
                                    2 => [
                                        'value' => 'expired',
                                        'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.expired'),
                                        'color' => 'red',
                                    ],
                                    3 => [
                                        'value' => 'paid',
                                        'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.paid'),
                                        'append' => 'payment',
                                    ],
                                ],
                                'user' => true,
                            ],
                        ],
                    ],
                    'orderByColumn' => 2,
                    'order' => 'desc',
                    'buttons' => [
                        [
                            'url' => \Locales::route($this->route . '/info'),
                            'class' => 'btn-info disabled js-edit',
                            'icon' => 'ок',
                            'name' => trans(\Locales::getNamespace() . '/forms.infoButton'),
                        ],
                        [
                            'url' => \Locales::route($this->route . '/cancel'),
                            'class' => 'btn-danger disabled js-edit',
                            'icon' => 'ок',
                            'name' => trans(\Locales::getNamespace() . '/forms.cancelButton'),
                        ],
                    ],
                ],
            ];
        }
    }

    public function dashboard(Request $request)
    {
        if (Auth::user()->role_id < 3) {
            $profileOld = env('ANALYTICS_OLD_VIEW_ID');
            $profileNew = env('ANALYTICS_NEW_VIEW_ID');
            $startDateOld = Carbon::parse('2009-08-20');
            $startDateNew = Carbon::parse('2017-01-30');
            $client = new \Google_Client();
            $client->setApplicationName('www-sunsetresort-bg');
            $analytics = new \Google_Service_Analytics($client);

            $scopes = [\Google_Service_Analytics::ANALYTICS_READONLY];
            $client->setScopes($scopes);
            $client->setAuthConfig(storage_path('app/google/analytics-service-account-credentials.json'));

            $accessToken = \Cache::remember('google-analytics-token', 60, function() use ($client) {
                if ($client->isAccessTokenExpired()) {
                    $client->refreshTokenWithAssertion();
                }

                return $client->getAccessToken()['access_token'];
            });

            return view(\Locales::getNamespace() . '.dashboard', compact('profileOld', 'profileNew', 'startDateOld', 'startDateNew', 'accessToken'));
        } else {
            $datatable = new Datatable($request);
            $transactions = new TransactionTest();
            $transactions->whereDate('to', '<=', Carbon::now()->subDays(5))->whereNull('deleted_at')->update(['status' => 'expired', 'deleted_at' => Carbon::now()]);

            $datatable->setup($transactions->withTrashed(), $this->route, $this->datatables[$this->route]);

            $datatables = $datatable->getTables();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($datatables);
            } else {
                return view(\Locales::getNamespace() . '.transactions-test.index', compact('datatables'));
            }
        }
    }

    public function info(Request $request, $id = null)
    {
        $transaction = TransactionTest::findOrFail($id);

        $ERR_CODES = trans('messages.errCodes');

        $TERMINAL = 'V6200049';
        $TRTYPE = 90;
        $ORDER = str_pad($transaction->id, 6, '8', STR_PAD_LEFT);
        $NONCE = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));

        $data = (mb_strlen($TERMINAL)) . $TERMINAL . (mb_strlen($TRTYPE)) . $TRTYPE . (mb_strlen($ORDER)) . $ORDER . (mb_strlen($NONCE)) . $NONCE;

        $fp = fopen(storage_path('app/ssl/test-2020.key'), 'r');
        $key = fread($fp, 8192);
        fclose($fp);

        $signature = null;
        $private = openssl_pkey_get_private($key);
        openssl_sign($data, $signature, $private, OPENSSL_ALGO_SHA256);
        openssl_free_key($private);

        $P_SIGN = strtoupper(bin2hex($signature));

        $fields = [
            'RRN' => $transaction->rrn,
            'INT_REF' => $transaction->int_ref,
            'TERMINAL' => $TERMINAL,
            'TRTYPE' => $TRTYPE,
            'ORDER' => $ORDER,
            'NONCE' => $NONCE,
            'TRAN_TRTYPE' => $transaction->status == 'refunded' ? 24 : $transaction->type,
            'P_SIGN' => $P_SIGN,
        ];

        // \Log::debug($fields);

        $client = new Client();
        $result = $client->request('POST', 'https://3dsgate-dev.borica.bg/cgi-bin/cgi_link', [
            'form_params' => $fields,
        ]);

        // dd($result->getBody(), $result->getBody()->getContents());
        $content = $result->getBody()->getContents();
        // \Log::debug($content);
        $info = json_decode($content, true);

        // \Log::debug($info);

        $status = '';

        if ($info['ACTION'] == 3 && $info['RC'] == -24) {
            $status = trans(\Locales::getNamespace() . '/forms.transactionError');
            if (array_key_exists($info['RC'], $ERR_CODES)) {
                $status .= '<br>' . $ERR_CODES[$info['RC']];
            }
            $info = [];
        }

        // $ACTION = $info['actionCode'] ?? '';
        $ACTION = $info['ACTION'] ?? '';
        // $RC = $info['responseCode'] ?? '';
        $RC = $info['RC'] ?? '';
        // $STATUS_MSG = $info['statusMsg'] ?? '';
        $STATUS_MSG = $info['STATUSMSG'] ?? '';
        // $TERMINAL = $info['terminal'] ?? '';
        $TERMINAL = $info['TERMINAL'] ?? '';
        // $TRTYPE = $info['tr_type'] ?? '';
        $TRTYPE = $info['TRTYPE'] ?? '';
        // $AMOUNT = $info['amount'] ?? '';
        $AMOUNT = $info['AMOUNT'] ?? '';
        // $CURRENCY = $info['currency'] ?? '';
        $CURRENCY = $info['CURRENCY'] ?? '';
        // $ORDER = $info['orderID'] ?? '';
        $ORDER = $info['ORDER'] ?? '';
        // $TIMESTAMP = $info['timestamp'] ?? '';
        $TIMESTAMP = $info['TIMESTAMP'] ?? '';
        // $TRAN_DATE = $info['tranDate'] ?? '';
        $TRAN_DATE = $info['TRAN_DATE'] ?? '';
        // $APPROVAL = $info['approval'] ?? '';
        $APPROVAL = $info['APPROVAL'] ?? '';
        // $RRN = $info['rrn'] ?? '';
        $RRN = $info['RRN'] ?? '';
        // $INT_REF = $info['intRef'] ?? '';
        $INT_REF = $info['INT_REF'] ?? '';
        // $PARES_STATUS = $info['paresStatus'] ?? '';
        $PARES_STATUS = $info['PARES_STATUS'] ?? '';
        // $ECI = $info['eci'] ?? '';
        $ECI = $info['ECI'] ?? '';
        // $CARD = $info['card'] ?? '';
        $CARD = $info['CARD'] ?? '';
        // $NONCE = $info['nonce'] ?? '';
        $NONCE = $info['NONCE'] ?? '';
        // $TRAN_TRTYPE = $info['tran_trtype'] ?? '';
        // $P_SIGN = $info['signature'] ?? '';
        $P_SIGN = $info['P_SIGN'] ?? '';
        $P_SIGN_BIN = hex2bin($P_SIGN);

        $data = ($ACTION != '' ? mb_strlen($ACTION) : '-') . $ACTION . ($RC != '' ? mb_strlen($RC) : '-') . $RC . ($APPROVAL != '' ? mb_strlen($APPROVAL) : '-') . $APPROVAL . ($TERMINAL != '' ? mb_strlen($TERMINAL) : '-') . $TERMINAL . ($TRTYPE != '' ? mb_strlen($TRTYPE) : '-') . $TRTYPE . ($AMOUNT != '' ? mb_strlen($AMOUNT) : '-') . $AMOUNT . ($CURRENCY != '' ? mb_strlen($CURRENCY) : '-') . $CURRENCY . ($ORDER != '' ? mb_strlen($ORDER) : '-') . $ORDER . ($RRN != '' ? mb_strlen($RRN) : '-') . $RRN . ($INT_REF != '' ? mb_strlen($INT_REF) : '-') . $INT_REF . ($PARES_STATUS != '' ? mb_strlen($PARES_STATUS) : '-') . $PARES_STATUS . ($ECI != '' ? mb_strlen($ECI) : '-') . $ECI . ($TIMESTAMP != '' ? mb_strlen($TIMESTAMP) : '-') . $TIMESTAMP . ($NONCE != '' ? mb_strlen($NONCE) : '-') . $NONCE;

        $fp = fopen(storage_path('app/ssl/borica-test-2020.pem'), 'r');
        $key = fread($fp, 8192);
        fclose($fp);

        $public = openssl_pkey_get_public($key);
        $result = openssl_verify($data, $P_SIGN_BIN, $public, OPENSSL_ALGO_SHA256);
        openssl_free_key($public);

        // \Log::debug($result);

        if ($result == 1) {

        } elseif ($result == 0) {
            $status .= '<br>' . trans(\Locales::getNamespace() . '/forms.transactionError');
        } else {
            $status .= '<br>' . openssl_error_string();
        }

        $table = $request->input('table') ?: $this->route;

        $view = \View::make(\Locales::getNamespace() . '.transactions-test.info', compact('table', 'transaction', 'status', 'info'));
        $sections = $view->renderSections();
        return response()->json([$sections['content']]);
    }

    public function cancel(Request $request, $id = null)
    {
        $transaction = TransactionTest::findOrFail($id);

        $table = $request->input('table') ?: $this->route;

        $view = \View::make(\Locales::getNamespace() . '.transactions-test.cancel', compact('table', 'transaction', 'fields'));
        $sections = $view->renderSections();

        /*\Session::forget('ajax-url');
        \Session::forget('transaction-id');
        \Session::forget('error');
        \Session::forget('success');*/

        return response()->json([$sections['content']]);
    }

    public function refund(Request $request, $id = null)
    {
        $transaction = TransactionTest::findOrFail($id);

        $BACKREF = url('https://www.sunsetresort.bg/postbank'); // \Locales::route('postbank'); // $BACKREF = \Locales::route($this->route . '/refund'); // $BACKREF = \Locales::route(trans(\Locales::getNamespace() . '/routes.' . $this->route . '.slug'));
        $TERMINAL = 'V6200049';
        $TRTYPE = 24;
        $AMOUNT = number_format($transaction->amount, 2, '.', '');
        $CURRENCY = 'BGN';
        $ORDER = str_pad($transaction->id, 6, '8', STR_PAD_LEFT);
        $ORDER_ID = $ORDER . '@' . substr($this->prefix, 0, -1);
        $MERCHANT = '9200200084';
        $MERCH_GMT = '+0' . (Carbon::now()->timezone('Europe/Sofia')->offset / (60 * 60));
        $TIMESTAMP = Carbon::now()->format('YmdHis');
        $NONCE = strtoupper(bin2hex(openssl_random_pseudo_bytes(16)));
        $MERCH_NAME = 'Sunset Resort Management EOOD';
        $MERCH_URL = 'https://www.sunsetresort.bg/';
        $EMAIL = 'mitko@sunsetresort.bg';
        $COUNTRY = 'BG';
        $ADDENDUM = 'AD,TD';

        $data = (mb_strlen($TERMINAL)) . $TERMINAL . (mb_strlen($TRTYPE)) . $TRTYPE . (mb_strlen($AMOUNT)) . $AMOUNT . (mb_strlen($CURRENCY)) . $CURRENCY . (mb_strlen($ORDER)) . $ORDER . (mb_strlen($MERCHANT)) . $MERCHANT . (mb_strlen($TIMESTAMP)) . $TIMESTAMP . (mb_strlen($NONCE)) . $NONCE;

        $fp = fopen(storage_path('app/ssl/test-2020.key'), 'r');
        $key = fread($fp, 8192);
        fclose($fp);

        $signature = null;
        $private = openssl_pkey_get_private($key);
        openssl_sign($data, $signature, $private, OPENSSL_ALGO_SHA256);
        openssl_free_key($private);

        $P_SIGN = strtoupper(bin2hex($signature));

        $fields = [
            'BACKREF' => $BACKREF,
            'AMOUNT' => $AMOUNT,
            'CURRENCY' => $CURRENCY,
            'TERMINAL' => $TERMINAL,
            'MERCH_NAME' => $MERCH_NAME,
            'MERCH_URL' => $MERCH_URL,
            'MERCHANT' => $MERCHANT,
            'EMAIL' => $EMAIL,
            'TRTYPE' => $TRTYPE,
            'ORDER' => $ORDER,
            'ORDER_ID' => $ORDER_ID,
            'COUNTRY' => $COUNTRY,
            'TIMESTAMP' => $TIMESTAMP,
            'MERCH_GMT' => $MERCH_GMT,
            'NONCE' => $NONCE,
            'ADDENDUM' => $ADDENDUM,
            'RRN' => $transaction->rrn,
            'INT_REF' => $transaction->int_ref,
            'DESC' => $transaction->description,
            'P_SIGN' => $P_SIGN,
        ];

        // \Log::debug($fields);

        $client = new Client();
        $result = $client->request('POST', 'https://3dsgate-dev.borica.bg/cgi-bin/cgi_link', [
            'form_params' => $fields,
        ]);

        // dd($result->getBody(), $result->getBody()->getContents());
        $content = $result->getBody()->getContents();
        // \Log::debug($content);
        $info = json_decode($content, true);

        // \Log::debug($info);

        $ERR_CODES = trans('messages.errCodes');

        // $ACTION = $info['actionCode'] ?? '';
        $ACTION = $info['ACTION'] ?? '';
        // $RC = $info['responseCode'] ?? '';
        $RC = $info['RC'] ?? '';
        // $STATUS_MSG = $info['statusMsg'] ?? '';
        $STATUS_MSG = $info['STATUSMSG'] ?? '';
        // $TERMINAL = $info['terminal'] ?? '';
        $TERMINAL = $info['TERMINAL'] ?? '';
        // $TRTYPE = $info['tr_type'] ?? '';
        $TRTYPE = $info['TRTYPE'] ?? '';
        // $AMOUNT = $info['amount'] ?? '';
        $AMOUNT = $info['AMOUNT'] ?? '';
        // $CURRENCY = $info['currency'] ?? '';
        $CURRENCY = $info['CURRENCY'] ?? '';
        // $ORDER = $info['orderID'] ?? '';
        $ORDER = $info['ORDER'] ?? '';
        // $TIMESTAMP = $info['timestamp'] ?? '';
        $TIMESTAMP = $info['TIMESTAMP'] ?? '';
        // $TRAN_DATE = $info['tranDate'] ?? '';
        $TRAN_DATE = $info['TRAN_DATE'] ?? '';
        // $APPROVAL = $info['approval'] ?? '';
        $APPROVAL = $info['APPROVAL'] ?? '';
        // $RRN = $info['rrn'] ?? '';
        $RRN = $info['RRN'] ?? '';
        // $INT_REF = $info['intRef'] ?? '';
        $INT_REF = $info['INT_REF'] ?? '';
        // $PARES_STATUS = $info['paresStatus'] ?? '';
        $PARES_STATUS = $info['PARES_STATUS'] ?? '';
        // $ECI = $info['eci'] ?? '';
        $ECI = $info['ECI'] ?? '';
        // $CARD = $info['card'] ?? '';
        $CARD = $info['CARD'] ?? '';
        // $NONCE = $info['nonce'] ?? '';
        $NONCE = $info['NONCE'] ?? '';
        // $TRAN_TRTYPE = $info['tran_trtype'] ?? '';
        // $P_SIGN = $info['signature'] ?? '';
        $P_SIGN = $info['P_SIGN'] ?? '';
        $P_SIGN_BIN = hex2bin($P_SIGN);

        if ($ACTION == 0) {
            $data = ($ACTION != '' ? mb_strlen($ACTION) : '-') . $ACTION . ($RC != '' ? mb_strlen($RC) : '-') . $RC . ($APPROVAL != '' ? mb_strlen($APPROVAL) : '-') . $APPROVAL . ($TERMINAL != '' ? mb_strlen($TERMINAL) : '-') . $TERMINAL . ($TRTYPE != '' ? mb_strlen($TRTYPE) : '-') . $TRTYPE . ($AMOUNT != '' ? mb_strlen($AMOUNT) : '-') . $AMOUNT . ($CURRENCY != '' ? mb_strlen($CURRENCY) : '-') . $CURRENCY . ($ORDER != '' ? mb_strlen($ORDER) : '-') . $ORDER . ($RRN != '' ? mb_strlen($RRN) : '-') . $RRN . ($INT_REF != '' ? mb_strlen($INT_REF) : '-') . $INT_REF . ($PARES_STATUS != '' ? mb_strlen($PARES_STATUS) : '-') . $PARES_STATUS . ($ECI != '' ? mb_strlen($ECI) : '-') . $ECI . ($TIMESTAMP != '' ? mb_strlen($TIMESTAMP) : '-') . $TIMESTAMP . ($NONCE != '' ? mb_strlen($NONCE) : '-') . $NONCE;

            $fp = fopen(storage_path('app/ssl/borica-test-2020.pem'), 'r');
            $key = fread($fp, 8192);
            fclose($fp);

            $public = openssl_pkey_get_public($key);
            $result = openssl_verify($data, $P_SIGN_BIN, $public, OPENSSL_ALGO_SHA256);
            openssl_free_key($public);

            // \Log::debug($result);

            // $transaction = TransactionTest::findOrFail(ltrim($ORDER, '8'));
            // \Session::put('ajax-url', \Locales::route($this->route . '/refund'));
            // \Session::put('transaction-id', $transaction->id);

            if ($result == 1) {
                if ($RC == '00') {
                    return response()->json([
                        'success' => trans(\Locales::getNamespace() . '/forms.refundedSuccessfully'),
                    ]);
                } elseif (array_key_exists($RC, $ERR_CODES)) {
                    /*\Session::put('error', $ERR_CODES[$RC]);
                    return redirect(\Locales::route($this->route));*/
                    return response()->json(['errors' => [$ERR_CODES[$RC]]]);
                } else {
                    /*\Session::put('error', 'RC - Unknown Error');
                    return redirect(\Locales::route($this->route));*/
                    return response()->json(['errors' => ['RC - Unknown Error']]);
                }
            } elseif ($result == 0) {
                /*\Session::put('error', 'Грешка при проверка на електронния подпис на банката');
                return redirect(\Locales::route($this->route));*/
                return response()->json(['errors' => ['Грешка при проверка на електронния подпис на банката']]);
            } else {
                /*\Session::put('error', openssl_error_string());
                return redirect(\Locales::route($this->route));*/
                return response()->json(['errors' => [openssl_error_string()]]);
            }
        } else if ($ACTION == 1) {
            return response()->json(['errors' => ['ACTION - Duplicate Transaction']]);
        } else if ($ACTION == 2) {
            return response()->json(['errors' => ['ACTION - Declined']]);
        } else if ($ACTION == 3) {
            if (array_key_exists($RC, $ERR_CODES)) {
                return response()->json(['errors' => [$ERR_CODES[$RC]]]);
            } else {
                return response()->json(['errors' => ['ACTION - Unknown Error']]);
            }
        }
    }

    public function cancelled(DataTable $datatable, TransactionTest $transaction, Request $request)
    {
        $transaction = TransactionTest::findOrFail($request->input('id'))->first();

        if ($transaction->delete()) {
            $transaction->withTrashed()->whereIn('id', $request->input('id'))->update([
                'status' => 'refunded',
                'user_id' => Auth::user()->id,
            ]);

            $datatable->setup(TransactionTest::withTrashed(), $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, true));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => trans(\Locales::getNamespace() . '/forms.refundedSuccessfully'),
                'closePopup' => true,
            ]);
        } else {
            return response()->json(['errors' => [trans(\Locales::getNamespace() . '/forms.countError')]]);
        }
    }

}
