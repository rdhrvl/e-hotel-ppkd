<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User model for the E-DN Mobile Flow application.
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property string|null $pin_hash
 * @property string $role
 * @property bool $biometric_enabled
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'pin_hash',
        'role',
        'biometric_enabled',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'pin_hash',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'biometric_enabled' => 'boolean',
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    /** @return HasMany<DeliveryNote, $this> */
    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }

    /**
     * Named `userNotifications` to avoid conflict with the Notifiable trait's
     * built-in `notifications()` relationship.
     *
     * @return HasMany<UserNotification, $this>
     */
    public function userNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    /**
     * Override default Notifiable unreadNotifications relation to use our simple notifications table.
     *
     * @return HasMany<UserNotification, $this>
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class)->where('is_read', false);
    }

    /** @return HasMany<Upload, $this> */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    /** @return HasMany<UserDevice, $this> */
    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class);
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    /** Determine whether the user has configured a transaction PIN. */
    public function hasPinSet(): bool
    {
        return ! is_null($this->pin_hash);
    }

    /** Check if the user holds the warehouse role. */
    public function isWarehouse(): bool
    {
        return $this->role === 'warehouse';
    }

    /** Check if the user holds the admin role. */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
