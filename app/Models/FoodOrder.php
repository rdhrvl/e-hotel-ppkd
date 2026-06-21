<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FoodOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'status', // 'processed', 'preparing', 'delivered', 'completed'
        'total_price',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /** @return BelongsTo<Booking, $this> */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /** @return HasMany<FoodOrderItem, $this> */
    public function items(): HasMany
    {
        return $this->hasMany(FoodOrderItem::class);
    }
}
