<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::updated(function (Room $room) {
            if ($room->wasChanged('status')) {
                try {
                    app(\App\Services\NotificationService::class)->handleRoomStatusChange(
                        $room,
                        auth()->user(),
                        $room->status,
                        $room->notes
                    );
                } catch (\Exception $e) {
                    \Log::error('Failed to trigger room status alert: ' . $e->getMessage());
                }
            }
        });
    }

    protected $fillable = [
        'room_number',
        'room_type_id',
        'branch_id',
        'price',
        'floor',
        'status',
        'notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /** @return BelongsTo<RoomType, $this> */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /** @return BelongsTo<Branch, $this> */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /** @return HasMany<Booking, $this> */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** @return HasMany<HousekeepingTask, $this> */
    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(HousekeepingTask::class);
    }

    /**
     * Get the effective price per night for this room (resolves overrides).
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price ?? $this->roomType->price_per_night);
    }

    /**
     * Get the currently checked-in booking for this room (if any).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Booking, $this>
     */
    public function activeBooking(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Booking::class)->where('status', 'checked_in');
    }

    /**
     * Determine if this room is available for new bookings right now.
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
