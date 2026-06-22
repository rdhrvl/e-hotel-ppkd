<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'guest_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'number_of_guests',
        'status', // 'pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'
        'total_price',
        'arrival_time',
        'box_no',
        'box_issued_by',
        'box_date',
        'payment_method',
        'notes',
        'book_by',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
        'box_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = 'BK-' . strtoupper(Str::random(6));
            }
        });
    }

    /** @return BelongsTo<Guest, $this> */
    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    /** @return BelongsTo<Room, $this> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** @return HasOne<GuestBill, $this> */
    public function guestBill(): HasOne
    {
        return $this->hasOne(GuestBill::class);
    }

    /** @return HasMany<Payment, $this> */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /** @return HasMany<BookingItem, $this> */
    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    /** @return HasMany<FoodOrder, $this> */
    public function foodOrders(): HasMany
    {
        return $this->hasMany(FoodOrder::class);
    }

    /** @return BelongsToMany<Service, $this> */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'booking_items')
            ->withPivot(['id', 'quantity', 'price', 'notes'])
            ->withTimestamps();
    }

    /**
     * Get the number of nights for the booking.
     */
    public function getNightsAttribute(): int
    {
        if (!$this->check_in_date || !$this->check_out_date) {
            return 0;
        }
        return (int) $this->check_in_date->diffInDays($this->check_out_date);
    }

    // Compatibility accessors
    public function getGuestNameAttribute(): string
    {
        return $this->guest?->name ?? 'Guest';
    }
}
