<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class DeliveryNote extends Model
{
    protected $fillable = [
        'user_id',
        'dn_number',
        'origin',
        'destination',
        'items_count',
        'status',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'items_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('status', ['active', 'in_transit']);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
