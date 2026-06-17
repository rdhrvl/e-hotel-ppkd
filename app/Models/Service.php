<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'type', // 'extra_bed', 'f_and_b', 'laundry', 'general'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** @return BelongsToMany<Booking, $this> */
    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_items')
            ->withPivot(['id', 'quantity', 'price', 'notes'])
            ->withTimestamps();
    }
}
