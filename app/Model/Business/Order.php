<?php

namespace App\Model\Business;

use App\Model\Auth\User;
use App\Model\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const TABLE = 'orders';

    /**
     * @var string
     */
    protected $table = self::TABLE;

    /**
     * @var string[]
     */
    protected $fillable = [
        'sum',
        'user_id',
        'country_code',
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'items',
        'user_id',
    ];

    /**
     * @return HasMany
     */
    public function items() : HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
