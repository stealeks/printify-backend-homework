<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @method static static create(array $data)
 * @method static Builder select()
 * @method static static|null find(int $id)
 */
abstract class Model extends BaseModel
{
    public const TABLE = '';

    /**
     * @param string $column
     * @return string
     */
    public static function column(string $column) : string
    {
        return static::TABLE.'.'.$column;
    }
}
