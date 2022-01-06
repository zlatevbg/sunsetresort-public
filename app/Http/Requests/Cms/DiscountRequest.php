<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;

class DiscountRequest extends Request
{
    protected $rules = [
        'dfrom' => 'required|date',
        'dto' => 'required|date',
        'discount' => 'present|numeric|min:1|max:100',
        'discounts' => 'present|array',
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
        return $this->rules;
    }
}
