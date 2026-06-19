<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'amount',
        'method', // 'cash', 'transfer', 'e-wallet'
        'status', // 'pending', 'paid', 'failed'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /** @return BelongsTo<Booking, $this> */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Compatibility accessors for payment_method
    public function getPaymentMethodAttribute(): string
    {
        return $this->method === 'transfer' ? 'bank_transfer' : $this->method;
    }

    public function setPaymentMethodAttribute(string $value): void
    {
        $this->attributes['method'] = $value === 'bank_transfer' ? 'transfer' : $value;
    }
}
