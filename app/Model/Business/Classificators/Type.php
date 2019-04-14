<?php

namespace App\Model\Business\Classificators;

use App\Model\Model;

class Type extends Model
{
    public const TABLE = 'types';

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
