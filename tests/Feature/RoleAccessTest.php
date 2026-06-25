<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /** @var array<string, Role> */
    private array $roles = [];

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin', 'front_desk', 'housekeeping', 'fnb'] as $slug) {
            $this->roles[$slug] = Role::create(['name' => $slug, 'slug' => $slug]);
        }
    }

    private function userWithRole(string $slug): User
    {
        return User::create([
            'name' => $slug.' user',
            'phone' => '081234567890',
            'email' => $slug.'@test.example',
            'password' => Hash::make('password'),
            'role_id' => $this->roles[$slug]->id,
        ]);
    }

    /**
     * @return array<string, array{string, string, int}>
     */
    public static function accessMatrix(): array
    {
        return [
            // front desk: own pages allowed, others forbidden
            'front_desk → bookings' => ['front_desk', '/bookings', 200],
            'front_desk → guests' => ['front_desk', '/guests', 200],
            'front_desk → payments' => ['front_desk', '/payments', 200],
            'front_desk → housekeeping (forbidden)' => ['front_desk', '/housekeeping', 403],
            'front_desk → fnb (forbidden)' => ['front_desk', '/fnb', 403],
            'front_desk → dashboard (shared)' => ['front_desk', '/dashboard', 200],
            'front_desk → reports (forbidden)' => ['front_desk', '/reports', 403],
            'front_desk → rooms (forbidden)' => ['front_desk', '/rooms', 403],

            // housekeeping: own page + shared dashboard
            'housekeeping → housekeeping' => ['housekeeping', '/housekeeping', 200],
            'housekeeping → dashboard (shared)' => ['housekeeping', '/dashboard', 200],
            'housekeeping → bookings (forbidden)' => ['housekeeping', '/bookings', 403],
            'housekeeping → booking create (forbidden)' => ['housekeeping', '/booking', 403],
            'housekeeping → fnb (forbidden)' => ['housekeeping', '/fnb', 403],
            'housekeeping → reports (forbidden)' => ['housekeeping', '/reports', 403],

            // fnb: own page + shared dashboard
            'fnb → fnb' => ['fnb', '/fnb', 200],
            'fnb → dashboard (shared)' => ['fnb', '/dashboard', 200],
            'fnb → bookings (forbidden)' => ['fnb', '/bookings', 403],
            'fnb → housekeeping (forbidden)' => ['fnb', '/housekeeping', 403],
            'fnb → reports (forbidden)' => ['fnb', '/reports', 403],

            // admin: everything
            'admin → dashboard' => ['admin', '/dashboard', 200],
            'admin → bookings' => ['admin', '/bookings', 200],
            'admin → housekeeping' => ['admin', '/housekeeping', 200],
            'admin → fnb' => ['admin', '/fnb', 200],
            'admin → rooms' => ['admin', '/rooms', 200],

            // superadmin: everything (helpers return true across the board)
            'superadmin → dashboard' => ['superadmin', '/dashboard', 200],
            'superadmin → housekeeping' => ['superadmin', '/housekeeping', 200],
            'superadmin → fnb' => ['superadmin', '/fnb', 200],

            // settings: open to all authenticated
            'housekeeping → settings' => ['housekeeping', '/settings', 200],
            'fnb → settings' => ['fnb', '/settings', 200],
        ];
    }

    #[DataProvider('accessMatrix')]
    public function test_role_page_access(string $slug, string $path, int $expected): void
    {
        $response = $this->actingAs($this->userWithRole($slug))->get($path);

        $response->assertStatus($expected);
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function homeRouteRedirects(): array
    {
        return [
            'admin → dashboard' => ['admin', '/dashboard'],
            'superadmin → dashboard' => ['superadmin', '/dashboard'],
            'front_desk → dashboard' => ['front_desk', '/dashboard'],
            'housekeeping → dashboard' => ['housekeeping', '/dashboard'],
            'fnb → dashboard' => ['fnb', '/dashboard'],
        ];
    }

    #[DataProvider('homeRouteRedirects')]
    public function test_root_redirects_to_role_home(string $slug, string $expectedPath): void
    {
        $response = $this->actingAs($this->userWithRole($slug))->get('/');

        $response->assertRedirect($expectedPath);
    }
}
