<?php

namespace App\Http\Requests\Guests;

use App\Http\Requests\Request;

class SubscribeRequest extends Request
{
    protected $rules = [
        'subscribe_email' => 'required|email|max:255|unique:subscribers,email',
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
