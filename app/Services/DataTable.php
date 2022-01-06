<?php

namespace App\Services;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\PricePeriod;
use App\Meal;

class DataTable
{
    /**
     * Single or Multiple DataTables on one page.
     *
     * @var string
     */
    protected $request;
    protected $table;
    protected $tables = [];

    /**
     * Creates new instance.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function setup($model, $table, $options, $internal = null)
    {
        $this->setTable($table);
        $this->createTable($table, $options);

        $count = $model->count();
        $this->setOption('size', ($count <= 100 ? 'small' : ($count <= 1000 ? 'medium' : 'large')));
        $columnsData = $this->getColumnsData();

        foreach ($columnsData['aggregates'] as $aggregate) {
            $model = $model->with($aggregate['aggregate']);
        }

        foreach ($columnsData['with'] as $with) {
            $model = $model->with($with['with']);
        }

        foreach ($columnsData['joins'] as $join) {
            $model = $model->leftJoin($join['join']['table'], $join['join']['localColumn'], $join['join']['constrain'], $join['join']['foreignColumn']);
        }

        foreach ($columnsData['replaces'] as $replace) {
            if (isset($replace['replace']['selector'])) {
                $columnsData['columns'] = array_merge($columnsData['columns'], $replace['replace']['selector']);
            }
        }

        foreach ($columnsData['appends'] as $append) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $append['append']['selector']);
        }

        foreach ($columnsData['prepends'] as $prepend) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $prepend['prepend']['selector']);
        }

        foreach ($columnsData['trans'] as $trans) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $trans['trans']['selector']);
        }

        foreach ($columnsData['colors'] as $color) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $color['color']['selector']);
        }

        foreach ($columnsData['links'] as $link) {
            if (isset($link['link']['selector'])) {
                $columnsData['columns'] = array_merge($columnsData['columns'], $link['link']['selector']);
            }
        }

        foreach ($columnsData['thumbnails'] as $thumbnail) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $thumbnail['thumbnail']['selector']);
        }

        foreach ($columnsData['files'] as $file) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $file['file']['selector']);
        }

        if ($this->request->ajax() || $this->request->wantsJson()) {
            $this->setOption('ajax', true);
            if ($internal) {
                $this->setOption('updateTable', true);
            } else {
                $this->setOption('reloadTable', true);
            }
            $this->setOption('draw', (int)$this->request->input('draw', 1));
            $this->setOption('recordsTotal', $count);

            $model = $model->select($columnsData['columns']);

            if ($columnsData['selectRaw']) {
                $model = $model->selectRaw(implode(',', $columnsData['selectRaw']));
            }

            $column = $this->request->input('columns.' . $this->request->input('order.0.column') . '.data', $columnsData['orderByColumn']);
            $dir = $this->request->input('order.0.dir', $this->getOption('order'));
            $model = $model->orderBy($column, $dir);

            if ($this->request->input('search.value')) {
                $this->setOption('search', true);

                $model = $model->where(function($query) {
                    $i = 0;
                    foreach ($this->getOption('columns') as $column) {
                        if (isset($column['search'])) {
                            if ($i == 0) {
                                $query->where($column['selector'], 'like', '%' . $this->request->input('search.value') . '%');
                            } else {
                                $query->orWhere($column['selector'], 'like', '%' . $this->request->input('search.value') . '%');
                            }
                        }
                        $i++;
                    }
                });

                $this->setOption('recordsFiltered', $model->count());
            } else {
                $this->setOption('recordsFiltered', $count);
            }

            if ($this->request->input('length') > 0) { // All = -1
                if ($this->request->input('start') > 0) {
                    $model = $model->skip($this->request->input('start'));
                }

                $model = $model->take($this->request->input('length'));
            }

            $data = $model->get();
            $originalData = $data;
            $data = $data->toArray();

            if (count($columnsData['processors'])) {
                $data = $this->process($originalData, $columnsData['processors']);
            }

            if (count($columnsData['replaces'])) {
                $data = $this->replace($data, $columnsData['replaces']);
            }

            if (count($columnsData['aggregates'])) {
                $data = $this->aggregate($data, $columnsData['aggregates']);
            }

            if (count($columnsData['appends'])) {
                $data = $this->append($data, $columnsData['appends']);
            }

            if (count($columnsData['prepends'])) {
                $data = $this->prepend($data, $columnsData['prepends']);
            }

            if (count($columnsData['trans'])) {
                $data = $this->trans($data, $columnsData['trans']);
            }

            if (count($columnsData['colors'])) {
                $data = $this->color($data, $columnsData['colors']);
            }

            if (count($columnsData['thumbnails'])) {
                $data = $this->thumbnail($data, $columnsData['thumbnails']);
            }

            if (count($columnsData['files'])) {
                $data = $this->file($data, $columnsData['files']);
            }

            if (count($columnsData['links'])) {
                $data = $this->link($data, $columnsData['links']);
            }

            if (count($columnsData['statuses'])) {
                $data = $this->status($data, $columnsData['statuses']);
            }

            if (count($columnsData['filesizes'])) {
                $data = $this->filesize($data, $columnsData['filesizes']);
            }

            if (count($columnsData['data'])) {
                $data = $this->data($data, $columnsData['data']);
            }

            $this->setOption('data', $data);
        } else {
            $this->setOption('count', $count);
            $this->setOption('ajax', $count > \Config::get('datatables.clientSideLimit'));

            if (!$this->getOption('ajax')) {
                $model = $model->select($columnsData['columns']);

                if ($columnsData['selectRaw']) {
                    $model = $model->selectRaw(implode(',', $columnsData['selectRaw']));
                }

                $model = $model->orderBy($columnsData['orderByColumn'], $this->getOption('order'));

                $data = $model->get();
                $originalData = $data;
                $data = $data->toArray();

                if (count($columnsData['processors'])) {
                    $data = $this->process($originalData, $columnsData['processors']);
                }

                if (count($columnsData['replaces'])) {
                    $data = $this->replace($data, $columnsData['replaces']);
                }

                if (count($columnsData['aggregates'])) {
                    $data = $this->aggregate($data, $columnsData['aggregates']);
                }

                if (count($columnsData['appends'])) {
                    $data = $this->append($data, $columnsData['appends']);
                }

                if (count($columnsData['prepends'])) {
                    $data = $this->prepend($data, $columnsData['prepends']);
                }

                if (count($columnsData['trans'])) {
                    $data = $this->trans($data, $columnsData['trans']);
                }

                if (count($columnsData['colors'])) {
                    $data = $this->color($data, $columnsData['colors']);
                }

                if (count($columnsData['thumbnails'])) {
                    $data = $this->thumbnail($data, $columnsData['thumbnails']);
                }

                if (count($columnsData['files'])) {
                    $data = $this->file($data, $columnsData['files']);
                }

                if (count($columnsData['links'])) {
                    $data = $this->link($data, $columnsData['links']);
                }

                if (count($columnsData['statuses'])) {
                    $data = $this->status($data, $columnsData['statuses']);
                }

                if (count($columnsData['filesizes'])) {
                    $data = $this->filesize($data, $columnsData['filesizes']);
                }

                if (count($columnsData['data'])) {
                    $data = $this->data($data, $columnsData['data']);
                }

                $this->setOption('data', $data);
            }
        }
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function createTable($table, $options)
    {
        $this->tables[$table] = isset($this->tables[$table]) ? array_merge_recursive($this->tables[$table], $options) : $options;
    }

    public function getTables($table = null)
    {
        return $table ? $this->tables[$table] : $this->tables;
    }

    public function getOption($key)
    {
        return isset($this->tables[$this->getTable()][$key]) ? $this->tables[$this->getTable()][$key] : null;
    }

    public function setOption($key, $value, $table = null)
    {
        $this->tables[$table ?: $this->getTable()][$key] = $value;
    }

    public function getColumnsData()
    {
        $columnsData = ['selectRaw' => [], 'with' => [], 'replaces' => [], 'colors' => [], 'prepends' => [], 'trans' => [], 'data' => [], 'appends' => [], 'links' => [], 'statuses' => [], 'thumbnails' => [], 'files' => [], 'filesizes' => [], 'joins' => [], 'aggregates' => [], 'processors' => []];
        $columns = array_where($this->getOption('columns'), function ($key, $column) use (&$columnsData) {
            $skip = false;

            if (isset($column['aggregate'])) {
                array_push($columnsData['aggregates'], $column);
                $skip = true;
            }

            if (isset($column['process'])) {
                array_push($columnsData['processors'], $column);
            }

            if (isset($column['replace'])) {
                array_push($columnsData['replaces'], $column);
            }

            if (isset($column['with'])) {
                array_push($columnsData['with'], $column);
            }

            if (isset($column['join'])) {
                array_push($columnsData['joins'], $column);
            }

            if (isset($column['append'])) {
                array_push($columnsData['appends'], $column);
            }

            if (isset($column['prepend'])) {
                array_push($columnsData['prepends'], $column);
            }

            if (isset($column['trans'])) {
                array_push($columnsData['trans'], $column);
            }

            if (isset($column['color'])) {
                array_push($columnsData['colors'], $column);
            }

            if (isset($column['link'])) {
                array_push($columnsData['links'], $column);
            }

            if (isset($column['status'])) {
                array_push($columnsData['statuses'], $column);
            }

            if (isset($column['thumbnail'])) {
                array_push($columnsData['thumbnails'], $column);
            }

            if (isset($column['file'])) {
                array_push($columnsData['files'], $column);
            }

            if (isset($column['filesize'])) {
                array_push($columnsData['filesizes'], $column);
            }

            if (isset($column['data'])) {
                array_push($columnsData['data'], $column);
            }

            if (isset($column['selectRaw'])) {
                array_push($columnsData['selectRaw'], $column['selectRaw']);
            }

            if ($skip) {
                return false;
            } else {
                return true;
            }
        });

        $columnsData['columns'] = array_column($columns, 'selector');
        if ($this->getOption('selectors')) {
            $columnsData['columns'] = array_merge($columnsData['columns'], $this->getOption('selectors'));
        }
        $columnsData['orderByColumn'] = (is_numeric($this->getOption('orderByColumn')) ? $this->getOption('columns')[$this->getOption('orderByColumn')]['selector'] : $this->getOption('orderByColumn'));

        return $columnsData;
    }

    public function replace($data, $replaces)
    {
        foreach ($data as $key => $items) {
            foreach ($replaces as $replace) {
                if (array_key_exists($replace['id'], $items) || (isset($replace['replace']['id']) && array_key_exists($replace['replace']['id'], $items))) {
                    if (isset($replace['replace']['rules'])) {
                        foreach ($replace['replace']['rules'] as $rules) {
                            $id = $items[isset($rules['column']) ? $rules['column'] : $replace['id']];
                            if (array_key_exists('valueNot', $rules)) {
                                $condition = $id != $rules['valueNot'];
                            } else {
                                $condition = ($id == $rules['value']);
                            }

                            if ($condition) {
                                if (isset($rules['text'])) {
                                    $data[$key][$replace['id']] = $rules['text'];
                                }

                                if (isset($rules['color'])) {
                                    $data[$key][$replace['id']] = '<span style="color: ' . $rules['color'] . ';">' . $rules['text'] . '</span>';
                                }

                                if (isset($rules['bold'])) {
                                    $data[$key][$replace['id']] = '<strong>' . $data[$key][$replace['id']] . '</strong>';
                                }

                                if (isset($rules['checkbox'])) {
                                    $data[$key]['checkbox'] = '<input type="checkbox">';
                                }

                                if (isset($rules['append'])) {
                                    if ($rules['append'] == 'deleted_at') {
                                        $data[$key][$replace['id']] .= '<br>' . \App\Helpers\displayWindowsDate(Carbon::parse($data[$key][$rules['append']], 'Europe/Sofia')->formatLocalized('%d.%m.%Y %H:%M:%S'));
                                    } else {
                                        $data[$key][$replace['id']] .= '<br>' . $data[$key][$rules['append']];
                                    }
                                }
                            } else {
                                if (isset($rules['checkbox'])) {
                                    $data[$key]['checkbox'] = '';
                                }
                            }
                        }

                        if (isset($replace['replace']['append'])) {
                            $data[$key][$replace['id']] .= '<br>' . $data[$key][$replace['replace']['append']];
                        }

                        if (isset($replace['replace']['user'])) {
                            $data[$key][$replace['id']] .= '<br>' . $data[$key]['user'];
                        }
                    } else {
                        if (isset($replace['replace']['printf'])) {
                            $data[$key][$replace['id']] = vsprintf($replace['replace']['printf'], array_map(function($value) use ($data, $key) {
                                return $data[$key][$value];
                            }, $replace['replace']['selector']));
                        } elseif (isset($replace['replace']['simpleText'])) {
                            $data[$key][$replace['id']] = $replace['replace']['simpleText'];
                        } elseif (isset($replace['replace']['array'])) {
                            if (array_key_exists($data[$key][$replace['id']], $replace['replace']['array'])) {
                                $data[$key][$replace['id']] = $replace['replace']['array'][$data[$key][$replace['id']]];
                            }
                        } else {
                            $data[$key][$replace['id']] = $data[$key][$replace['replace']['text']];
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function aggregate($data, $aggregates)
    {
        foreach ($data as $key => $items) {
            foreach ($aggregates as $aggregate) {
                $relation = snake_case($aggregate['aggregate']);
                if (count($items[$relation])) {
                    $data[$key][$aggregate['id']] = $items[$relation][0]['aggregate'];
                } else {
                    $data[$key][$aggregate['id']] = 0;
                }

                unset($data[$key][$relation]);
            }
        }

        return $data;
    }

    public function process($data, $processors)
    {
        if ($data->first() instanceof PricePeriod) {
            $meals = Meal::where('parent', function ($query) {
                $query->select('id')->from('meals')->where('slug', \Locales::getCurrent());
            })->get();

            foreach ($processors as $process) {
                $method = 'process' . $process['process'];
                $data = $this->$method($data, $meals);
            }
        } else {
            foreach ($processors as $process) {
                $method = 'process' . $process['process'];
                $data = $this->$method($data);
            }
        }

        return $data->toArray();
    }

    public function processAvailability($data)
    {
        $newData = $data->map(function ($item, $key) {
            $item->total = $item->availability->sum('availability');
            return $item;
        });

        return $newData;
    }

    public function processAvailabilitySV1($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'one-bed')->where('view', 'sea')->first();
            $item->sv1 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][one-bed][sea]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-sv1">min</span><input class="form-control" name="min_stay[' . $item->id . '][one-bed][sea]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-sv1"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityPV1($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'one-bed')->where('view', 'park')->first();
            $item->pv1 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][one-bed][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-pv1">min</span><input class="form-control" name="min_stay[' . $item->id . '][one-bed][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-pv1"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilitySV2($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'two-bed')->where('view', 'sea')->first();
            $item->sv2 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][two-bed][sea]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-sv2">min</span><input class="form-control" name="min_stay[' . $item->id . '][two-bed][sea]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-sv2"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityPV2($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'two-bed')->where('view', 'park')->first();
            $item->pv2 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][two-bed][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-pv2">min</span><input class="form-control" name="min_stay[' . $item->id . '][two-bed][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-pv2"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilitySV3($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'three-bed')->where('view', 'sea')->first();
            $item->sv3 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][three-bed][sea]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-sv3">min</span><input class="form-control" name="min_stay[' . $item->id . '][three-bed][sea]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-sv3"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityPV3($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'three-bed')->where('view', 'park')->first();
            $item->pv3 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][three-bed][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-pv3">min</span><input class="form-control" name="min_stay[' . $item->id . '][three-bed][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-pv3"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityStudio($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'studio')->where('view', 'park')->first();
            $item->studio = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][studio][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-studio">min</span><input class="form-control" name="min_stay[' . $item->id . '][studio][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-studio"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityE1($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'one-bed-economy')->where('view', 'park')->first();
            $item->e1 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][one-bed-economy][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-one-bed-economy">min</span><input class="form-control" name="min_stay[' . $item->id . '][one-bed-economy][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-one-bed-economy"></div>';
            return $item;
        });

        return $newData;
    }

    public function processAvailabilityE2($data)
    {
        $newData = $data->map(function ($item, $key) {
            $value = $item->availability->where('room', 'two-bed-economy')->where('view', 'park')->first();
            $item->e2 = '<input class="form-control form-control-availability" name="dates[' . $item->id . '][two-bed-economy][park]" type="text" value="' . ($value ? $value->availability : 0) . '"><div class="input-group input-group-min-stay"><span class="input-group-addon" id="min-stay-two-bed-economy">min</span><input class="form-control" name="min_stay[' . $item->id . '][two-bed-economy][park]" type="text" value="' . ($value ? $value->min_stay : 0) . '" aria-describedby="min-stay-two-bed-economy"></div>';
            return $item;
        });

        return $newData;
    }

    public function processDiscountSV1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'one-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountPV1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'one-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountSV2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'two-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountPV2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'two-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountSV3($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'three-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv3 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][three-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountPV3($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'three-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv3 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][three-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountStudio($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'studio')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->studio .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][studio][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountE1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug == 'sc') {
                    $price = $item->prices->where('room', 'one-bed-economy')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->e1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed-economy][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscountE2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug == 'sc') {
                    $price = $item->prices->where('room', 'two-bed-economy')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->e2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed-economy][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->discount : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processDiscount($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) {
            $item->discount = '<input class="form-control form-control-discount" name="dates[' . $item->id . '][discount]" type="text" value="' . $item->discount . '">';
            return $item;
        });

        return $newData;
    }

    public function processPricesSV1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'one-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesPV1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'one-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesSV2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'two-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesPV2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'two-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesSV3($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'three-bed')->where('view', 'sea')->where('meal', $meal->slug)->first();
                    $item->sv3 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][three-bed][sea][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesPV3($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'three-bed')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->pv3 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][three-bed][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesStudio($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug != 'sc') {
                    $price = $item->prices->where('room', 'studio')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->studio .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][studio][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesE1($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug == 'sc') {
                    $price = $item->prices->where('room', 'one-bed-economy')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->e1 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][one-bed-economy][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function processPricesE2($data, $meals = [])
    {
        $newData = $data->map(function ($item, $key) use ($meals) {
            foreach ($meals as $meal) {
                if ($meal->slug == 'sc') {
                    $price = $item->prices->where('room', 'two-bed-economy')->where('view', 'park')->where('meal', $meal->slug)->first();
                    $item->e2 .= '<input class="form-control form-control-discount" name="dates[' . $item->id . '][two-bed-economy][park][' . $meal->slug . ']" type="text" value="' . ($price ? $price->price : '') . '">';
                }
            }
            return $item;
        });

        return $newData;
    }

    public function append($data, $appends)
    {
        foreach ($data as $key => $items) {
            foreach ($appends as $append) {
                if (array_key_exists($append['id'], $items)) {
                    if (isset($append['append']['rules'])) {
                        foreach ($append['append']['rules'] as $column => $value) {
                            if ($items[$column] == $value) {
                                $data[$key][$append['id']] .= $append['append']['text'];
                            }
                        }
                    } elseif (isset($append['append']['simpleText'])) {
                        $data[$key][$append['id']] .= $append['append']['simpleText'];
                    } else {
                        $data[$key][$append['id']] .= ' ' . $data[$key][$append['append']['text']];
                    }
                }
            }
        }

        return $data;
    }

    public function prepend($data, $prepends)
    {
        foreach ($data as $key => $items) {
            foreach ($prepends as $prepend) {
                if (array_key_exists($prepend['id'], $items)) {
                    foreach ($prepend['prepend']['rules'] as $column => $value) {
                        if ($items[$column] == $value) {
                            $data[$key][$prepend['id']] = $prepend['prepend']['text'] . $data[$key][$prepend['id']];
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function trans($data, $trans)
    {
        foreach ($data as $key => $items) {
            foreach ($trans as $tran) {
                if (array_key_exists($tran['id'], $items)) {
                    $data[$key][$tran['id']] .= '<br>' . (trans($tran['trans']['lang'] . '.' . $data[$key][$tran['id']]) != $tran['trans']['lang'] . '.' . $data[$key][$tran['id']] ? trans($tran['trans']['lang'] . '.' . $data[$key][$tran['id']]) : trans($tran['trans']['none']));
                }
            }
        }

        return $data;
    }

    public function color($data, $colors)
    {
        foreach ($data as $key => $items) {
            foreach ($colors as $color) {
                if (array_key_exists($color['id'], $items)) {
                    $data[$key][$color['id']] = '<span style="color: ' . $items[$color['color']['id']] . ';">' . $data[$key][$color['id']] . '</span>';
                }
            }
        }

        return $data;
    }

    public function link($data, $links)
    {
        foreach ($data as $key => $items) {
            foreach ($links as $link) {
                if (array_key_exists($link['id'], $items)) {
                    if (isset($link['link']['rules'])) {
                        foreach ($link['link']['rules'] as $rules) {
                            if ($items[$rules['column']] == $rules['value']) {
                                $data[$key][$link['id']] = '<a href="' . \Locales::route($link['link']['route'], ltrim(implode('/', $this->request->session()->get('routeSlugs', [])) . (isset($link['link']['routeParameter']) ? '/' . $data[$key][$link['link']['routeParameter']] : ''), '/')) . '">' . (isset($rules['icon']) ? '<span class="glyphicon glyphicon-' . $rules['icon'] . ' glyphicon-left"></span>' : '') . $data[$key][$link['id']] . '</a>';
                                break;
                            }
                        }
                    } else {
                        if (isset($link['link']['route'])) {
                            $url = \Locales::route($link['link']['route'], ltrim(implode('/', $this->request->session()->get('routeSlugs', [])) . (isset($link['link']['routeParameter']) ? '/' . $data[$key][$link['link']['routeParameter']] : ''), '/'));
                        } else {
                            $url = $data[$key][$link['link']['url']];
                        }

                        $data[$key][$link['id']] = ($url ? '<a href="' . $url . '">' : '') . (isset($link['link']['icon']) ? '<span class="glyphicon glyphicon-' . $link['link']['icon'] . ' glyphicon-left"></span>' : '') . $data[$key][$link['id']] . ($url ? '</a>' : '');
                    }
                }
            }
        }

        return $data;
    }

    public function status($data, $statuses)
    {
        foreach ($data as $key => $items) {
            foreach ($statuses as $status) {
                if (array_key_exists($status['id'], $items)) {
                    foreach ($status['status']['rules'] as $val => $options) {
                        if ($items[$status['id']] == $val) {
                            $data[$key][$status['id']] = '<a class="' . $status['status']['class'] . '" data-ajax-queue="' . $status['status']['queue'] . '" href="' . \Locales::route($status['status']['route'], [$data[$key]['id'], $options['status']]) . '">' . \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/' . $options['icon']), $options['title']) . '</a>';
                            break;
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function thumbnail($data, $thumbnails)
    {
        foreach ($data as $key => $items) {
            foreach ($thumbnails as $thumbnail) {
                if (array_key_exists($thumbnail['id'], $items)) {
                    $uploadDirectory = 'upload/' . $this->getOption('uploadDirectory') . '/' . (isset($thumbnail['thumbnail']['folder']) ? $data[$key][$thumbnail['thumbnail']['folder']] : trim(implode('/', $this->request->session()->get('routeSlugs', [])), '/') . '/' . \Config::get('upload.imagesDirectory')) . '/' . $data[$key][$thumbnail['thumbnail']['id']] . '/';
                    $data[$key][$thumbnail['id']] = '<a class="popup" ' . (isset($thumbnail['thumbnail']['title']) ? 'title="' . \HTML::entities($data[$key][$thumbnail['thumbnail']['title']]) . '"' : '') . 'href="' . asset($uploadDirectory . $this->getOption('expandDirectory') . $data[$key][$thumbnail['id']]) . '">' . \HTML::image($uploadDirectory . \Config::get('upload.thumbnailDirectory') . '/' . $data[$key][$thumbnail['id']], $data[$key]['name']) . '</a>';
                }
            }
        }

        return $data;
    }

    public function file($data, $files)
    {
        foreach ($data as $key => $items) {
            foreach ($files as $file) {
                if (array_key_exists($file['id'], $items)) {
                    $data[$key][$file['id']] = '<a ' . (isset($file['file']['title']) ? 'title="' . \HTML::entities($data[$key][$file['file']['title']]) . '"' : '') . 'href="' . \Locales::route($file['file']['route'], $data[$key]['id']) . '">' . \HTML::image(\App\Helpers\autover('/img/' . \Locales::getNamespace() . '/ext/' . $data[$key][$file['file']['extension']] . '.png'), $data[$key]['name']) . '</a>';
                }
            }
        }

        return $data;
    }

    public function filesize($data, $filesizes)
    {
        foreach ($data as $key => $items) {
            foreach ($filesizes as $filesize) {
                if (array_key_exists($filesize['id'], $items)) {
                    $data[$key][$filesize['id']] = \App\Helpers\formatBytes($data[$key][$filesize['id']]);
                }
            }
        }

        return $data;
    }

    public function data($data, $arr)
    {
        foreach ($data as $key => $items) {
            foreach ($arr as $values) {
                if (array_key_exists($values['data']['id'], $items)) {
                    $display = $data[$key][$values['data']['id']];

                    if (isset($values['data']['date'])) {
                        $date = Carbon::parse($display, 'Europe/Sofia');
                        $val = Carbon::parse($display, 'Europe/Sofia')->format($values['data']['date']);
                    }

                    $data[$key][$values['data']['id']] = [
                        'display' => $display,
                        $values['data']['type'] => $val,
                    ];
                }
            }
        }

        return $data;
    }

}
