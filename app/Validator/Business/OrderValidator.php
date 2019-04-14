<?php

namespace App\Validator\Business;

use App\Model\Business\Product;
use App\Validator\Validator;

class OrderValidator extends Validator
{
    /**
     * @param mixed[] $toValidate
     * @return string[]
     */
    public static function getRules(array $toValidate) : array
    {
        return [
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.product_id' => [
                'required',
                'distinct',
                'exists:'.Product::TABLE.',id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }
}
