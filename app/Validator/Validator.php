<?php

namespace App\Validator;

use Illuminate\Validation\ValidationException;

abstract class Validator
{
    /**
     * @param mixed[] $toValidate
     * @return string[]
     */
    abstract public static function getRules(array $toValidate) : array;

    /**
     * @param mixed[] $toValidate
     * @throws ValidationException
     */
    public static function validate(array $toValidate) : void
    {
        $rules = static::getRules($toValidate);

        validator($toValidate, $rules)->validate();
    }
}
