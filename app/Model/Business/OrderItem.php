<?php

namespace App\Model\Business;

use App\Model\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public const TABLE = 'order_items';

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
        'product_id',
        'quantity',
    ];

    /**
     * @return BelongsTo
     */
    public function order() : BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @return BelongsTo
     */
    public function product() : BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
