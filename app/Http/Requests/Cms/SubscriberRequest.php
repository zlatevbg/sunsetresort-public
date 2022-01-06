<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;

class SubscriberRequest extends Request
{
    protected $rules = [
        'name' => 'filled|max:255',
        'slug' => 'filled|max:255',
        'email' => 'filled|email|max:255',
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
