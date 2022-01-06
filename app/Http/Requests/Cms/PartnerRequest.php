<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Partner;

class PartnerRequest extends Request
{
    protected $rules = [
        'name' => 'required|max:255',
        'slug' => 'required|max:255|unique:partners',
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
    public function rules(Partner $partner)
    {
        if (in_array($this->method(), ['PUT', 'PATCH'])) {
            $partner = Partner::findOrFail(\Request::input('id'))->first();

            array_forget($this->rules, 'slug');
            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:partners,slug,' . $partner->id);
        }

        return $this->rules;
    }
}
