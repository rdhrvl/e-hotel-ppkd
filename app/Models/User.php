<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 * User model for the Hotel Management System.
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $password
 * @property int|null $role_id
 * @property Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }

    // ---------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------

    /** @return BelongsTo<Role, $this> */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    // ---------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------

    /** Check if the user holds the superadmin role (unrestricted access). */
    public function isSuperAdmin(): bool
    {
        return $this->role?->slug === 'superadmin';
    }

    /** Check if the user holds the admin role (also true for superadmin). */
    public function isAdmin(): bool
    {
        return in_array($this->role?->slug, ['admin', 'superadmin'], true);
    }

    /** Check if the user holds the front desk role. */
    public function isFrontDesk(): bool
    {
        return in_array($this->role?->slug, ['front_desk', 'superadmin'], true);
    }

    /** Check if the user holds the housekeeping role. */
    public function isHousekeeping(): bool
    {
        return in_array($this->role?->slug, ['housekeeping', 'superadmin'], true);
    }

    /** Check if the user holds the food & beverage role. */
    public function isFnb(): bool
    {
        return in_array($this->role?->slug, ['fnb', 'superadmin'], true);
    }

    /** Landing route name after login. All roles share the dashboard, which renders role-specific analytics. */
    public function homeRoute(): string
    {
        return 'dashboard';
    }
}
