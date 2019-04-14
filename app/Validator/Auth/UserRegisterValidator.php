<?php

namespace App\Validator\Auth;

use App\Model\Auth\User;
use App\Validator\Validator;

class UserRegisterValidator extends Validator
{
    /**
     * @param mixed[] $toValidate
     * @return string[]
     */
    public static function getRules(array $toValidate) : array
    {
        $rules = UserLoginValidator::getRules($toValidate);

        $rules['name'] = [
            'required',
            'between:2,255',
        ];
        $rules['email'][] = 'unique:'.User::TABLE;
        $rules['password'][] = 'min:8';

        return $rules;
    }
}
