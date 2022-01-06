<?php namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Services\DataTable;
use App\Services\FineUploader;
use Illuminate\Http\Request;
use App\Question;
use Storage;
use App\Http\Requests\Cms\QuestionRequest;

class QuestionController extends Controller {

    protected $route = 'questions';
    protected $datatables;

    public function __construct()
    {
        $this->datatables = [
            'question_languages' => [
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
                        'url' => \Locales::route($this->route . '/create-language'),
                        'class' => 'btn-primary js-create',
                        'icon' => 'plus',
                        'name' => trans(\Locales::getNamespace() . '/forms.createCategoryButton'),
                    ],
                    [
                        'url' => \Locales::route($this->route . '/edit-language'),
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
            'question_categories' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleCategories'),
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
                            'selector' => [$this->route . '.id'],
                            'icon' => 'folder-open',
                            'route' => $this->route,
                            'routeParameter' => 'id',
                        ],
                    ],
                    [
                        'selector' => $this->route . '.order',
                        'id' => 'order',
                        'name' => trans(\Locales::getNamespace() . '/datatables.order'),
                        'class' => 'text-center',
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
                        'url' => \Locales::route($this->route . '/edit-category'),
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
            'questions' => [
                'title' => trans(\Locales::getNamespace() . '/datatables.titleQuestions'),
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
                        'selector' => $this->route . '.question',
                        'id' => 'question',
                        'name' => trans(\Locales::getNamespace() . '/datatables.question'),
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
                        'name' => trans(\Locales::getNamespace() . '/forms.createQuestionButton'),
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

    public function index(DataTable $datatable, Question $question, Request $request, $slugs = null)
    {
        $breadcrumbs = [];
        if ($slugs) {
            $slugsArray = explode('/', $slugs);
            $questions = Question::select('id', 'parent', 'slug', 'name')->get()->toArray();
            $questions = \App\Helpers\arrayToTree($questions);
            if ($row = \Slug::arrayMatchSlugsRecursive($slugsArray, $questions)) { // match slugs against the questions array
                $breadcrumbs = \Slug::createBreadcrumbsFromParameters($slugsArray, $questions);

                $request->session()->put('routeSlugs', $slugsArray); // save current slugs for proper file upload actions
                $request->session()->put($question->getTable() . 'Parent', $row['id']); // save current category for proper store/update/destroy actions
                $question = $question->where('parent', $row['id']);

                if ($row['parent']) { // it's a category
                    $datatable->setup($question, 'questions', $this->datatables['questions']);
                } else { // it's a question
                    $datatable->setup($question, 'question_categories', $this->datatables['question_categories']);
                }
            } else {
                abort(404);
            }
        } else {
            $request->session()->put($question->getTable() . 'Parent', null); // save current category for proper store/update/destroy actions
            $request->session()->put('routeSlugs', []); // save current slugs for proper file upload actions
            $question = $question->where('parent', null);
            $datatable->setup($question, 'question_languages', $this->datatables['question_languages']);
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

    public function createLanguage(Request $request)
    {
        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create-language', compact('table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function store(DataTable $datatable, Question $question, QuestionRequest $request)
    {
        $parent = $request->session()->get($question->getTable() . 'Parent', null);

        $question = $question->where('parent', $parent);

        $order = $request->input('order');
        $maxOrder = $question->max('order') + 1;

        if (!$order || $order > $maxOrder) {
            $order = $maxOrder;
        } else { // re-order all higher order rows
            $clone = clone $question;
            $clone->where('order', '>=', $order)->increment('order');
        }

        $request->merge([
            'parent' => $parent,
            'order' => $order,
        ]);

        $newQuestion = Question::create($request->all());

        if ($newQuestion->id) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.storedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityQuestions'), 1)]);

            $datatable->setup($question, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'reset' => true,
                'resetEditor' => true,
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.createError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityQuestions'), 1)]);
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

    public function destroy(DataTable $datatable, Question $question, Request $request)
    {
        $count = count($request->input('id'));

        $directories = Question::find($request->input('id'))->lists('slug');

        if ($count > 0 && $question->destroy($request->input('id'))) {
            $parent = $request->session()->get($question->getTable() . 'Parent', null);

            \DB::statement('SET @pos := 0');
            \DB::update('update ' . $question->getTable() . ' SET `order` = (SELECT @pos := @pos + 1) WHERE parent = ? ORDER BY `order`', [$parent]);

            $slugs = $request->session()->get('routeSlugs', []);
            $question = $question->where('parent', $parent);

            $datatable->setup($question, $request->input('table'), $this->datatables[$request->input('table')], true);
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
        $question = Question::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create', compact('question', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function editCategory(Request $request, $id = null)
    {
        $question = Question::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create-category', compact('question', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function editLanguage(Request $request, $id = null)
    {
        $question = Question::findOrFail($id);

        $table = $request->input('table');

        $view = \View::make(\Locales::getNamespace() . '.' . $this->route . '.create-language', compact('question', 'table'));
        $sections = $view->renderSections();
        return $sections['content'];
    }

    public function update(DataTable $datatable, Question $q, QuestionRequest $request)
    {
        $question = Question::findOrFail($request->input('id'))->first();

        $order = $request->input('order');
        if (!$order || $order < 0) {
            $order = $question->order;
        } elseif ($order) {
            $q = $q->where('parent', $question->parent);
            $maxOrder = $q->max('order');

            if ($order > $maxOrder) {
                $order = $maxOrder;
            } elseif ($order < $question->order) {
                $q->where('order', '>=', $order)->where('order', '<', $question->order)->increment('order');
            } elseif ($order > $question->order) {
                $q->where('order', '<=', $order)->where('order', '>', $question->order)->decrement('order');
            }
        }

        $request->merge([
            'order' => $order,
        ]);

        if ($question->update($request->all())) {
            $slugs = $request->session()->get('routeSlugs', []);

            $successMessage = trans(\Locales::getNamespace() . '/forms.updatedSuccessfully', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityQuestions'), 1)]);

            $question = $question->where('parent', $question->parent);

            $datatable->setup($question, $request->input('table'), $this->datatables[$request->input('table')], true);
            $datatable->setOption('url', \Locales::route($this->route, implode('/', $slugs)));
            $datatables = $datatable->getTables();

            return response()->json($datatables + [
                'success' => $successMessage,
                'closePopup' => true
            ]);
        } else {
            $errorMessage = trans(\Locales::getNamespace() . '/forms.editError', ['entity' => trans_choice(\Locales::getNamespace() . '/forms.' . ($request->input('is_category') ? 'entityCategories' : 'entityQuestions'), 1)]);
            return response()->json(['errors' => [$errorMessage]]);
        }
    }

    public function changeStatus($id, $status)
    {
        $question = Question::findOrFail($id);

        $question->is_active = $status;
        $question->save();

        $href = '';
        $img = '';
        foreach ($this->datatables['questions']['columns'] as $column) {
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
