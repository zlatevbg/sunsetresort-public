<?php

namespace App\Http\Requests\Www;

use App\Room;
use App\Http\Requests\Request;

class BookRequest extends Request
{
    protected $rules = [];
    public $rooms;
    public $counter;

    public function __construct(\Illuminate\Http\Request $request)
    {
        parent::__construct();

        $counter = 0;
        $rooms = Room::where('parent', function ($query) {
            $query->select('id')->from('rooms')->where('slug', \Locales::getCurrent());
        })->where('is_active', 1)->orderBy('order')->get();

        foreach ($rooms as $room) {
            if ($request->input($room->slug)) {
                $counter++;

                foreach ($request->input($room->slug) as $index => $value) {
                    $request->merge([
                        'view-' . $room->slug . '-' . $index => (isset($value['view']) ? $value['view'] : null),
                        'meal-' . $room->slug . '-' . $index => (isset($value['meal']) ? $value['meal'] : null),
                        'guests-' . $room->slug . '-' . $index => (isset($value['guests']) ? $value['guests'] : null),
                    ]);
                }
            }
        }

        $this->counter = $counter;
        $this->rooms = $rooms;
    }

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
        if ($this->method() == 'POST') {
            if ($this->route('step') == 2) {
                $this->rules = [
                    'dfrom' => 'required|date|before:dto',
                    'dto' => 'required|date',
                ];
            } elseif ($this->route('step') == 3) {
                if ($this->counter) {
                    foreach ($this->rooms as $room) {
                        foreach ($this->input($room->slug, []) as $index => $value) {
                            $this->rules['view-' . $room->slug . '-' . $index] = 'required|max:255';
                            $this->rules['meal-' . $room->slug . '-' . $index] = 'required|max:255';
                            $this->rules['guests-' . $room->slug . '-' . $index] = 'required|numeric|min:1|max:' . ($room->capacity + $room->infants);
                        }
                    }
                } else {
                    $this->rules = [
                        'select-room' => 'required|numeric|min:1',
                    ];
                }
            } elseif ($this->route('step') == 4) {
                $this->rules = [
                    'name' => 'required|max:255',
                    'email' => 'required|email|max:255',
                    'phone' => 'required|max:255',
                    'company' => 'required_with:invoice|max:255',
                    'country' => 'required_with:invoice|max:255',
                    'eik' => 'required_with:invoice|max:255',
                    'vat' => 'required_with:invoice|max:255',
                    'address' => 'required_with:invoice|max:255',
                    'city' => 'required_with:invoice|max:255',
                    'mol' => 'required_with:invoice|max:255',
                    'message' => 'present',
                ];
            } else {
                $this->rules = [
                    'consent' => 'accepted',
                    'g-recaptcha-response' => 'required',
                ];
            }
        }

        return $this->rules;
    }

    public function messages()
    {
        $messages = [];

        foreach ($this->rooms as $room) {
            foreach ($this->input($room->slug, []) as $index => $value) {
                $messages['view-' . $room->slug . '-' . $index . '.required'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.required', ['attribute' => trans('validation.attributes.views')]);
                // $messages['view-' . $room->slug . '-' . $index . '.numeric'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.regex', ['attribute' => trans('validation.attributes.views')]);
                $messages['view-' . $room->slug . '-' . $index . '.max'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.regex', ['attribute' => trans('validation.attributes.views')]);

                $messages['meal-' . $room->slug . '-' . $index . '.required'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.required', ['attribute' => trans('validation.attributes.meals')]);
                $messages['meal-' . $room->slug . '-' . $index . '.numeric'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.regex', ['attribute' => trans('validation.attributes.meals')]);
                $messages['meal-' . $room->slug . '-' . $index . '.max'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.regex', ['attribute' => trans('validation.attributes.meals')]);

                $messages['guests-' . $room->slug . '-' . $index . '.required'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.required', ['attribute' => trans('validation.attributes.guests')]);
                $messages['guests-' . $room->slug . '-' . $index . '.numeric'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.regex', ['attribute' => trans('validation.attributes.guests')]);
                $messages['guests-' . $room->slug . '-' . $index . '.min'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.min.numeric', ['attribute' => trans('validation.attributes.guests'), 'min' => 1]);
                $messages['guests-' . $room->slug . '-' . $index . '.max'] = '[' . $room->name . ' #' . $index . '] ' . trans('validation.max.numeric', ['attribute' => trans('validation.attributes.guests'), 'max' => ($room->capacity + $room->infants + 1)]);
            }
        }

        return $messages;
    }
}
