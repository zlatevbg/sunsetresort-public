<?php

namespace App\Services;

class Validator
{
    public function validateBeforeOrEqual($attribute, $value, $parameters, $validator)
    {
        return strtotime($validator->getData()[$parameters[0]]) >= strtotime($value);
    }

    public function replaceBeforeOrEqual($message, $attribute, $rule, $parameters)
    {
        return str_replace([':date'], trans('validation.attributes.' . $parameters[0]), $message);
    }
}
