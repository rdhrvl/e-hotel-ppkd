<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'food_order_id',
        'service_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** @return BelongsTo<FoodOrder, $this> */
    public function foodOrder(): BelongsTo
    {
        return $this->belongsTo(FoodOrder::class);
    }

    /** @return BelongsTo<Service, $this> */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
