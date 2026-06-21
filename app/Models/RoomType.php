<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'base_price',
        'description',
        'bed_type',
        'has_breakfast',
        'included_amenities',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'has_breakfast' => 'boolean',
        'included_amenities' => 'array',
    ];

    /** @return HasMany<Room, $this> */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Keep compatibility with existing code referring to price_per_night
     */
    public function getPricePerNightAttribute(): float
    {
        return (float) $this->base_price;
    }

    public function setPricePerNightAttribute($value): void
    {
        $this->attributes['base_price'] = $value;
    }
}
