<?php

namespace App\Validator\Auth;

use App\Validator\Validator;

class UserLoginValidator extends Validator
{
    /**
     * @param mixed[] $toValidate
     * @return string[]
     */
    public static function getRules(array $toValidate) : array
    {
        return [
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ];
    }
}
