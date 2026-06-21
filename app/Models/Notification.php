<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Notification extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'room_id',
        'actor_id',
        'target_roles',
        'message',
        'priority',
        'is_urgent',
        'action_url',
    ];

    protected $casts = [
        'target_roles' => 'array',
        'is_urgent' => 'boolean',
    ];

    /** @return BelongsTo<Room, $this> */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /** @return BelongsTo<User, $this> */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    /** @return HasMany<NotificationReadStatus, $this> */
    public function readStatuses(): HasMany
    {
        return $this->hasMany(NotificationReadStatus::class);
    }
}
