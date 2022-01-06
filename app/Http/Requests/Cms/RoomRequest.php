<?php

namespace App\Http\Requests\Cms;

use App\Http\Requests\Request;
use App\Room;

class RoomRequest extends Request
{
    protected $rules = [
        'name' => 'required|max:255',
        'area' => 'filled|numeric|max:127',
        'capacity' => 'filled|numeric|max:127',
        'adults' => 'filled|numeric|max:127',
        'children' => 'filled|numeric|max:127',
        'infants' => 'filled|numeric|max:127',
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
            $page = Room::findOrFail(\Request::input('id'))->first();
            $parent = $page->parent;

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:rooms,slug,' . $page->id . ',id,parent,' . ($parent ?: 'NULL'));
        } else {
            $parent = \Request::session()->get('roomsParent', 0);

            $this->rules = array_add($this->rules, 'slug', 'required|max:255|unique:rooms,slug,NULL,id,parent,' . ($parent ?: 'NULL'));
        }

        return $this->rules;
    }
}
