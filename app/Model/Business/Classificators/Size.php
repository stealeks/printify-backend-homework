<?php

namespace App\Model\Business\Classificators;

use App\Model\Model;

class Size extends Model
{
    public const TABLE = 'sizes';

    /**
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
    ];
}
