<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;

class BookingRequest extends Request
{
    protected $rules = [
        'payment' => 'required|numeric',
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
