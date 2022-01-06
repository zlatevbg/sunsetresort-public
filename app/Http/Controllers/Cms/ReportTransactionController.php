<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Domain;
use App\Transaction;
use App\Services\DataTable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportTransactionController extends Controller
{

    protected $route = 'export-transactions';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            $this->route => [
                'create' => true,
                'wrapperClass' => 'table-invisible',
                'dom' => "<'dataTableFull'l>tr<'clearfix'<'dataTableI'i><'dataTableP'p>>",
                'url' => \Locales::route('export-transactions'),
                'class' => 'table-checkbox table-striped table-bordered table-hover table-report',
                'selectors' => ['transactions.user_id', 'users.name as user'],
                'columns' => [
                    [
                        'selector' => 'transactions.created_at',
                        'id' => 'created_at',
                        'name' => trans(\Locales::getNamespace() . '/datatables.createdAt'),
                        'class' => 'text-center vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'created_at',
                            'date' => 'Ymd',
                        ],
                    ],
                    [
                        'selector' => 'transactions.deleted_at',
                        'id' => 'deleted_at',
                        'name' => trans(\Locales::getNamespace() . '/datatables.usedAt'),
                        'class' => 'text-center vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'deleted_at',
                            'date' => 'Ymd',
                        ],
                    ],
                    [
                        'selector' => 'transactions.rc',
                        'id' => 'rc',
                        'name' => trans(\Locales::getNamespace() . '/datatables.transaction'),
                        'class' => 'vertical-center',
                        'trans' => [
                            'selector' => ['transactions.rc'],
                            'lang' => 'messages.errCodes',
                            'none' => 'messages.errCodes[""]',
                        ],
                    ],
                    [
                        'selector' => 'transactions.from',
                        'id' => 'from',
                        'name' => trans(\Locales::getNamespace() . '/datatables.dfrom'),
                        'class' => 'text-center vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'from',
                            'date' => 'Ymd',
                        ],
                    ],
                    [
                        'selector' => 'transactions.to',
                        'id' => 'to',
                        'name' => trans(\Locales::getNamespace() . '/datatables.dto'),
                        'class' => 'text-center vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'to',
                            'date' => 'Ymd',
                        ],
                    ],
                    [
                        'selector' => 'transactions.nights',
                        'id' => 'nights',
                        'name' => trans(\Locales::getNamespace() . '/datatables.nights'),
                        'class' => 'text-center vertical-center',
                    ],
                    [
                        'selector' => 'transactions.amount',
                        'id' => 'amount',
                        'name' => trans(\Locales::getNamespace() . '/datatables.amount'),
                        'class' => 'text-right vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'amount',
                            'cast' => 'int',
                        ],
                        'append' => [
                            'selector' => ['transactions.amount'],
                            'simpleText' => ' лв.',
                        ],
                    ],
                    [
                        'selector' => 'transactions.price',
                        'id' => 'price',
                        'name' => trans(\Locales::getNamespace() . '/datatables.price'),
                        'class' => 'text-right vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'price',
                            'cast' => 'int',
                        ],
                        'append' => [
                            'selector' => ['transactions.price'],
                            'simpleText' => ' лв.',
                        ],
                    ],
                    [
                        'selector' => 'transactions.name',
                        'id' => 'name',
                        'name' => trans(\Locales::getNamespace() . '/datatables.name'),
                        'class' => 'text-center vertical-center',
                        'search' => true,
                    ],
                    [
                        'selectRaw' => 'COALESCE(transactions.payment, 0) as payment',
                        'id' => 'payment',
                        'name' => trans(\Locales::getNamespace() . '/datatables.payment'),
                        'class' => 'text-right vertical-center',
                        'data' => [
                            'type' => 'sort',
                            'id' => 'payment',
                            'cast' => 'int',
                        ],
                    ],
                    [
                        'selector' => 'transactions.status',
                        'id' => 'status',
                        'name' => trans(\Locales::getNamespace() . '/datatables.status'),
                        'class' => 'text-center vertical-center',
                        'search' => true,
                        'join' => [
                            'table' => 'users',
                            'localColumn' => 'users.id',
                            'constrain' => '=',
                            'foreignColumn' => 'transactions.user_id',
                        ],
                        'replace' => [
                            'rules' => [
                                0 => [
                                    'value' => 'used',
                                    'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.used'),
                                    // 'append' => 'deleted_at',
                                ],
                                1 => [
                                    'value' => 'refunded',
                                    'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.refunded'),
                                    // 'append' => 'deleted_at',
                                ],
                                2 => [
                                    'value' => 'expired',
                                    'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.expired'),
                                    'color' => 'red',
                                ],
                                3 => [
                                    'value' => 'paid',
                                    'text' => trans(\Locales::getNamespace() . '/datatables.statusOptions.paid'),
                                    // 'append' => 'payment',
                                ],
                            ],
                            'user' => true,
                        ],
                    ],
                ],
                'orderByColumn' => 0,
                'order' => 'desc',
                'footer' => [
                    'class' => 'bg-info',
                    'columns' => [
                        [ // created_at
                            'data' => '',
                        ],
                        [ // deleted_at
                            'data' => '',
                        ],
                        [ // rc
                            'data' => '',
                        ],
                        [ // from
                            'data' => '',
                        ],
                        [ // to
                            'data' => '',
                        ],
                        [ // nights
                            'data' => '',
                        ],
                        [ // amount
                            'data' => '<span></span>',
                            'sum' => true,
                        ],
                        [ // price
                            'data' => '<span></span>',
                            'sum' => true,
                        ],
                        [ // name
                            'data' => '',
                        ],
                        [ // payment
                            'data' => '<span></span>',
                            'sum' => true,
                        ],
                        [ // status
                            'data' => '',
                        ],
                    ],
                ],
            ],
        ];
    }

    public function index(DataTable $datatable, Request $request, Transaction $transactions)
    {
        /*$datatable->setOption('skipLoading', true, $this->route);
        $datatable->setup($transactions, $this->route, $this->datatables[$this->route]);

        $datatables = $datatable->getTables();*/

        $order = [];
        foreach (trans(\Locales::getNamespace() . '/datatables.orderOptions') as $key => $value) {
            $order[$key] = $value;
        }

        $sort = [];
        foreach (trans(\Locales::getNamespace() . '/datatables.sortOptions') as $key => $value) {
            $sort[$key] = $value;
        }

        $status = [];
        foreach (trans(\Locales::getNamespace() . '/datatables.statusOptions') as $key => $value) {
            $status[$key] = $value;
        }

        $transaction = [];
        foreach (trans(\Locales::getNamespace() . '/datatables.transactionOptions') as $key => $value) {
            $transaction[$key] = $value;
        }

        $locale = ['' => trans(\Locales::getNamespace() . '/forms.allOption')];
        foreach (Domain::where('slug', 'www')->first()->locales as $value) {
            $locale[$value['locale']] = $value['native'];
        }

        return view(\Locales::getNamespace() . '.' . '.transactions.report', compact('order', 'sort', 'status', 'transaction', 'locale'));

        /*if ($request->ajax() || $request->wantsJson()) {
            return response()->json($datatables);
        } else {
            return view(\Locales::getNamespace() . '.' . '.transactions.report', compact('datatables', 'order', 'sort', 'status', 'transaction', 'locale'));
        }*/
    }

    public function generate(DataTable $datatable, Request $request, Transaction $transactions)
    {
        if ($request->input('status')) {
            $transactions = $transactions->withTrashed();

            if ($request->input('status') != 'all') {
                if ($request->input('status') == 'used') {
                    $transactions = $transactions->whereIn('transactions.status', ['used', 'paid']);
                } else {
                    $transactions = $transactions->where('transactions.status', $request->input('status'));
                }
            }
        }

        $this->datatables[$this->route]['columns'] = array_values($this->datatables[$this->route]['columns']);
        $this->datatables[$this->route]['footer']['columns'] = array_values($this->datatables[$this->route]['footer']['columns']);

        if ($request->input('transaction') == 'success') {
            $transactions = $transactions->where('transactions.rc', '00');
        } else {
            $transactions = $transactions->withTrashed()->where(function ($query) {
                $query->where('transactions.rc', '!=', '00')->orWhereNull('transactions.rc');
            });
        }

        if ($request->input('dfrom')) {
            $transactions = $transactions->whereDate('transactions.from', '>=', Carbon::parse($request->input('dfrom')));
        }

        if ($request->input('dto')) {
            $transactions = $transactions->whereDate('transactions.to', '<=', Carbon::parse($request->input('dto')));
        }

        if ($request->input('order')) {
            $transactions = $transactions->orderBy('transactions.' . $request->input('order'), $request->input('sort'));
        }

        if ($request->input('locale')) {
            $transactions = $transactions->where('transactions.locale', $request->input('locale'));
        }

        $datatable->setup($transactions, $this->route, $this->datatables[$this->route], true);
        $datatables = $datatable->getTables();

        if ($request->input('generate')) {
            $method = 'generate' . ucfirst($request->input('generate'));

            return response()->json([
                'success' => true,
                'uuid' => $this->$method($request, $datatables[$this->route]['data']),
            ]);
        } else {
            // $enable = ['button-download-report'];

            return response()->json($datatables + [
                'success' => true,
                // 'showTable' => true,
                // 'enable' => $enable,
            ]);
        }
    }

    public function generateExcel($request, $rawData)
    {
        $data = [];

        foreach ($rawData as $key => $value) {
            unset($rawData[$key]['user']);
            unset($rawData[$key]['user_id']);
            $data[$key] = array_replace(array_flip(['created_at', 'deleted_at', 'rc', 'from', 'to', 'nights', 'amount', 'price', 'name', 'payment', 'status']), $rawData[$key]);

            $data[$key]['from'] = $data[$key]['from']['display'];
            $data[$key]['to'] = $data[$key]['to']['display'];
            $data[$key]['amount'] = (float) $data[$key]['amount']['display'];
            $data[$key]['price'] = (float) $data[$key]['price']['display'];
            $data[$key]['payment'] = (float) $data[$key]['payment']['display'];
            $data[$key]['status']  = strip_tags(str_replace('<br>', ' - ', $data[$key]['status'])); // preg_replace('/(.*)<br>(.*)/', '="$1" & CHAR(10) & "$2"', $data[$key]['status']);
            $data[$key]['created_at'] = $data[$key]['created_at']['display'];
            $data[$key]['deleted_at'] = $data[$key]['deleted_at']['display'];
            $data[$key]['rc']  = strip_tags(str_replace('<br>', ' - ', $data[$key]['rc']));
        }

        $uuid = (string)\Uuid::generate();
        $filename = 'transactions-' . $uuid;

        $firstColumn = 'A';
        $firstRow = 1;
        $firstCell = $firstColumn . $firstRow;

        array_unshift($data, [
            'created_at' => trans(\Locales::getNamespace() . '/datatables.createdAt'),
            'deleted_at' => trans(\Locales::getNamespace() . '/datatables.usedAt'),
            'rc' => trans(\Locales::getNamespace() . '/datatables.transaction'),
            'from' => trans(\Locales::getNamespace() . '/datatables.dfrom'),
            'to' => trans(\Locales::getNamespace() . '/datatables.dto'),
            'nights' => trans(\Locales::getNamespace() . '/datatables.nights'),
            'amount' => trans(\Locales::getNamespace() . '/datatables.amount'),
            'price' => trans(\Locales::getNamespace() . '/datatables.price'),
            'name' => trans(\Locales::getNamespace() . '/datatables.name'),
            'payment' => trans(\Locales::getNamespace() . '/datatables.payment'),
            'status' => trans(\Locales::getNamespace() . '/datatables.status'),
        ]);

        $data = collect($data);

        $content = $data;

        $columnWidth = [
            'A' => -1,
            'B' => -1,
            'C' => -1,
            'D' => -1,
            'E' => -1,
            'F' => -1,
            'G' => -1,
            'H' => -1,
            'I' => -1,
            'J' => -1,
            'K' => -1,
        ];

        foreach ($content as $id => $row) {
            $col = $firstColumn;
            foreach ($row as $value) {
                if (isset($columnWidth[$col])) {
                    $width = mb_strlen($value);
                    if ($width > $columnWidth[$col]) {
                        $columnWidth[$col] = $width;
                    }
                }

                $col++;
            }
        }

        \Excel::create('Report', function ($excel) use ($request, $content, $columnWidth, $firstColumn, $firstRow, $firstCell) {

            $reportName = 'Report';

            $excel->sheet($reportName, function ($sheet) use ($request, $content, $columnWidth, $firstColumn, $firstRow, $firstCell) {

                $columnFormat = [
                    'A' => '@',
                    'B' => '@',
                    'C' => '@',
                    'D' => '@',
                    'E' => '@',
                    'F' => '@',
                    'G' => '@',
                    'H' => '@',
                    'I' => '@',
                    'J' => '@',
                    'K' => '@',
                ];

                $formula1 = chr(ord($firstColumn) + array_search('amount', array_keys($content[0])));
                $columnFormat[$formula1] = '0.00';

                $formula2 = chr(ord($firstColumn) + array_search('price', array_keys($content[0])));
                $columnFormat[$formula2] = '0.00';

                if ($request->input('status') == 'used' || $request->input('status') == 'paid') {
                    $formula3 = chr(ord($firstColumn) + array_search('payment', array_keys($content[0])));
                    $columnFormat[$formula3] = '0.00';
                }

                $sheet->setColumnFormat($columnFormat);

                $sheet->fromArray($content, null, $firstCell, false, false);

                $lastRow = $sheet->getHighestDataRow() + ($request->input('status') ? 1 : 0); // plus footer
                $lastColumn = $sheet->getHighestDataColumn();
                $lastCell = $lastColumn . $lastRow;

                $sheet->setShowGridlines(false);

                $sheet->getStyle($firstCell . ':' . $lastCell)->applyFromArray([
                    'font' => [
                        'size' => 12,
                    ],
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => \PHPExcel_Style_Border::BORDER_THIN,
                            'color' => [
                                'rgb' => 'DDDDDD',
                            ]
                        ]
                    ]
                ]);

                $sheet->getStyle($formula1 . ($firstRow + 1) . ':' . $formula1 . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ]
                ]);

                $sheet->getStyle($formula2 . ($firstRow + 1) . ':' . $formula2 . $lastRow)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                    ]
                ]);

                if ($request->input('status') == 'used' || $request->input('status') == 'paid') {
                    $sheet->getStyle($formula3 . ($firstRow + 1) . ':' . $formula3 . $lastRow)->applyFromArray([
                        'alignment' => [
                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
                        ]
                    ]);
                }

                $sheet->getStyle($firstCell . ':' . $lastCell)->getAlignment()->setWrapText(true);

                $sheet->getStyle($firstCell . ':' . $lastColumn . $firstRow)->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'style' => \PHPExcel_Style_Border::BORDER_THICK,
                            'color' => [
                                'rgb' => 'DDDDDD',
                            ]
                        ]
                    ]
                ]);

                $sheet->getStyle($firstColumn . ($firstRow + 1) . ':' . $lastColumn . ($firstRow + 1))->applyFromArray([
                    'borders' => [
                        'top' => [
                            'style' => \PHPExcel_Style_Border::BORDER_THICK,
                            'color' => [
                                'rgb' => 'DDDDDD',
                            ]
                        ]
                    ]
                ]);

                if ($request->input('status')) {
                    // Footer

                    $sheet->getStyle($firstColumn . $lastRow . ':' . $lastColumn . $lastRow)->applyFromArray([
                        'borders' => [
                            'top' => [
                                'style' => \PHPExcel_Style_Border::BORDER_THICK,
                                'color' => [
                                    'rgb' => 'DDDDDD',
                                ]
                            ]
                        ]
                    ]);

                    $sheet->getStyle($firstColumn . ($lastRow - 1) . ':' . $lastColumn . ($lastRow - 1))->applyFromArray([
                        'borders' => [
                            'bottom' => [
                                'style' => \PHPExcel_Style_Border::BORDER_THICK,
                                'color' => [
                                    'rgb' => 'DDDDDD',
                                ]
                            ]
                        ]
                    ]);
                }

                $sheet->freezePane('A' . ($firstRow + 1));

                $sheet->setAutoFilter($firstCell . ':' . $lastColumn . $firstRow);

                $sheet->cells($firstCell . ':' . $lastColumn . $firstRow, function ($cells) {
                    $cells->setFontWeight('bold');
                    $cells->setBackground('#D9EDF7');
                });

                if ($request->input('status')) {
                    // bold footer
                    $sheet->cells($firstColumn . $lastRow . ':' . $lastCell, function ($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setBackground('#D9EDF7');
                    });

                    /* FORMULAS START */

                    $sheet->cell($formula1 . $lastRow, function ($cell) use ($firstRow, $lastRow, $formula1) {
                        $cell->setValue('=SUM(' . $formula1 . ($firstRow + 1) . ':' . $formula1 . ($lastRow - 1) . ')');
                    });

                    $sheet->cell($formula2 . $lastRow, function ($cell) use ($firstRow, $lastRow, $formula2) {
                        $cell->setValue('=SUM(' . $formula2 . ($firstRow + 1) . ':' . $formula2 . ($lastRow - 1) . ')');
                    });

                    if ($request->input('status') == 'used' || $request->input('status') == 'paid') {
                        $sheet->cell($formula3 . $lastRow, function ($cell) use ($firstRow, $lastRow, $formula3) {
                            $cell->setValue('=SUM(' . $formula3 . ($firstRow + 1) . ':' . $formula3 . ($lastRow - 1) . ')');
                        });
                    }

                    /* FORMULAS END */
                }

                $sheet->setAutoSize(false);
                for ($col = 'A'; $col <= $lastColumn; $col++) {
                    if (isset($columnWidth[$col])) {
                        $sheet->getColumnDimension($col)->setWidth($columnWidth[$col] + 8.43);
                    }
                }

                for ($i = $firstRow + 1; $i <= $lastRow - 1; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }

                $sheet->getRowDimension($firstRow)->setRowHeight(25);
                $sheet->getRowDimension($lastRow)->setRowHeight(25);
            });

        })->setFilename($filename)->store('xlsx');

        return $uuid;
    }

    public function download(Request $request)
    {
        return response()->download(storage_path('app/reports/transactions-' . $request->input('uuid') . '.xlsx'))->deleteFileAfterSend(true);
    }
}
