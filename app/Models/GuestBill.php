<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'deposit_amount',
        'total_room_charges',
        'total_extra_charges',
        'paid_amount',
        'overpayment_amount',
        'status', // 'unpaid', 'partially_paid', 'paid'
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'total_room_charges' => 'decimal:2',
        'total_extra_charges' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'overpayment_amount' => 'decimal:2',
    ];

    /** @return BelongsTo<Booking, $this> */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the total amount (room + extra charges).
     */
    public function getTotalAmountAttribute(): float
    {
        return (float) ($this->total_room_charges + $this->total_extra_charges);
    }

    /**
     * Get the remaining balance to be paid.
     */
    public function getBalanceAttribute(): float
    {
        return (float) ($this->total_amount - $this->deposit_amount - $this->paid_amount);
    }

    /**
     * Recalculate status of the bill based on current totals.
     */
    public function calculateStatus(): string
    {
        $sisa = $this->balance;

        if ($sisa <= 0) {
            return 'paid';
        }

        if ((float) $this->deposit_amount > 0 || (float) $this->paid_amount > 0) {
            return 'partially_paid';
        }

        return 'unpaid';
    }
}
