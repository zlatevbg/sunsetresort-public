<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Question;

class QuestionRequest extends Request
{
    protected $rules = [
        'name' => 'required_without:question|max:255',
        'question' => 'required_without:name|max:255',
        'answer' => 'required_with:question',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Question $question)
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $question = Question::findOrFail(\Request::input('id'))->first();
            $parent = $question->parent;

            if (!$parent) {
                $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:questions,slug,' . $question->id . ',id,parent,' . ($parent ?: 'NULL'));
            }
        } else {
            $parent = \Request::session()->get($question->getTable() . 'Parent', 0);

            if (!$parent) {
                $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:questions,slug,NULL,id,parent,' . ($parent ?: 'NULL'));
            }
        }

        return $this->rules;
    }
}
