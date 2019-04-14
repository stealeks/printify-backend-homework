<?php

namespace App\Validator\Business;

use App\Model\Business\Classificators\Color;
use App\Model\Business\Classificators\Size;
use App\Model\Business\Classificators\Type;
use App\Model\Business\Product;
use App\Validator\Validator;
use Illuminate\Validation\ValidationException;

class ProductValidator extends Validator
{
    /**
     * @param mixed[] $toValidate
     * @return string[]
     */
    public static function getRules(array $toValidate) : array
    {
        return [
            'title' => [
                'required',
                'between:3,255',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'type_id' => [
                'required',
                'exists:'.Type::TABLE.',id',
            ],
            'color_id' => [
                'required',
                'exists:'.Color::TABLE.',id',
            ],
            'size_id' => [
                'required',
                'exists:'.Size::TABLE.',id',
            ],
        ];
    }

    /**
     * @param mixed[] $toValidate
     * @throws ValidationException
     */
    public static function validate(array $toValidate) : void
    {
        $errors = [];

        try {
            parent::validate($toValidate);
        } catch (ValidationException $exception) {
            $errors = $exception->errors();
        }

        $product = Product::where('type_id', '=', $toValidate['type_id'])
            ->where('size_id', '=', $toValidate['size_id'])
            ->where('color_id', '=', $toValidate['color_id'])
            ->first();

        if ($product) {
            $error = 'Product with same type_id, size_id and color_id already exists';

            $errors['type_id'][] = $error;
            $errors['size_id'][] = $error;
            $errors['color_id'][] = $error;
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
