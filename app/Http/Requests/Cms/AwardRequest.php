<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Award;

class AwardRequest extends Request
{
    protected $rules = [
        'name' => 'required|max:255',
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
    public function rules(Award $award)
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $award = Award::findOrFail(\Request::input('id'))->first();
            $parent = $award->parent;

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:awards,slug,' . $award->id . ',id,parent,' . ($parent ?: 'NULL'));
        } else {
            $parent = \Request::session()->get($award->getTable() . 'Parent', 0);

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:awards,slug,NULL,id,parent,' . ($parent ?: 'NULL'));
        }

        return $this->rules;
    }
}
