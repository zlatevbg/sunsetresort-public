<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;

class TestimonialRequest extends Request
{
    protected $rules = [
        'name' => 'required|max:255',
        'country' => 'filled|max:255',
        'content' => 'filled',
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
