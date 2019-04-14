<?php

namespace App\Model\Business\Classificators;

use App\Model\Model;

class Color extends Model
{
    public const TABLE = 'colors';

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
        'value',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'hex',

        // todo: add red, green, blue
    ];

    /**
     * @return string
     */
    public function getHEXAttribute() : string
    {
        $hex = dechex($this->attributes['value']);

        $hexColor = str_pad($hex, 6, '0', STR_PAD_LEFT);

        return $hexColor;
    }
}
