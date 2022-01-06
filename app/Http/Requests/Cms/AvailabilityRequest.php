<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;

class AvailabilityRequest extends Request
{
    protected $rules = [
        'dfrom' => 'required|date',
        'dto' => 'required|date',
        'availability' => 'required|array',
        'min_stay' => 'required|array',
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
