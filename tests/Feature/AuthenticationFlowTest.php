<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    private Role $adminRole;
    private Role $frontDeskRole;
    private Role $housekeeperRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'Admin', 'slug' => 'admin']);
        $this->frontDeskRole = Role::create(['name' => 'Front Desk', 'slug' => 'front_desk']);
        $this->housekeeperRole = Role::create(['name' => 'Housekeeper', 'slug' => 'housekeeping']);
    }

    /**
     * Test guest users are redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated admin can access the dashboard.
     */
    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'phone' => '081234567890',
            'email' => 'admin-dash@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test housekeeping role is restricted from admin room config page.
     */
    public function test_housekeeper_cannot_access_room_config(): void
    {
        $user = User::create([
            'name' => 'Housekeeper User',
            'phone' => '081234567890',
            'email' => 'housekeeper@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->housekeeperRole->id,
        ]);

        $response = $this->actingAs($user)->get('/rooms');

        $response->assertStatus(403);
    }

    /**
     * Test admin role can access admin room config page.
     */
    public function test_admin_can_access_room_config(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'phone' => '081234567890',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $this->adminRole->id,
        ]);

        $response = $this->actingAs($user)->get('/rooms');

        $response->assertStatus(200);
    }
}
