<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Meal;

class MealRequest extends Request
{
    protected $rules = [
        'name' => 'required|max:255',
        'description' => 'present|max:255',
        'price_adult' => 'present|numeric|between:0,999.99',
        'price_child' => 'present|numeric|between:0,999.99',
        // 'slug' => 'filled|max:255',
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
    public function rules()
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $page = Meal::findOrFail(\Request::input('id'))->first();
            $parent = $page->parent;

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:meals,slug,' . $page->id . ',id,parent,' . ($parent ?: 'NULL'));
        } else {
            $parent = \Request::session()->get('mealsParent', 0);

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:meals,slug,NULL,id,parent,' . ($parent ?: 'NULL'));
        }

        return $this->rules;
    }
}
