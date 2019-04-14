<?php

namespace App\Model\Business;

use App\Model\Auth\User;
use App\Model\Business\Classificators\Color;
use App\Model\Business\Classificators\Size;
use App\Model\Business\Classificators\Type;
use App\Model\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    public const TABLE = 'products';

    /**
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'price',
        'type_id',
        'color_id',
        'size_id',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'user_id',
    ];

    /**
     * @return BelongsTo
     */
    public function type() : BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    /**
     * @return BelongsTo
     */
    public function color() : BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    /**
     * @return BelongsTo
     */
    public function size() : BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
