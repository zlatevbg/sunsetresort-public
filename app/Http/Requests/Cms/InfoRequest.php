<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Info;

class InfoRequest extends Request
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
    public function rules(Info $page)
    {
        $this->merge(['is_map' => $this->input('is_map', 0)]); // set default value of the is_map checkbox

        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $page = Info::findOrFail(\Request::input('id'))->first();
            $parent = $page->parent;

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:info,slug,' . $page->id . ',id,parent,' . ($parent ?: 'NULL'));
        } else {
            $parent = \Request::session()->get($page->getTable() . 'Parent', 0);

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:info,slug,NULL,id,parent,' . ($parent ?: 'NULL'));
        }

        return $this->rules;
    }
}
