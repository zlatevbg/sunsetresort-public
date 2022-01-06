<?php

namespace App\Http\Requests\Www;

use App\Http\Requests\Request;

class ContactRequest extends Request
{
    protected $rules = [
        'department' => 'required|numeric',
        'name' => 'required|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required',
        'g-recaptcha-response' => 'required',
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
