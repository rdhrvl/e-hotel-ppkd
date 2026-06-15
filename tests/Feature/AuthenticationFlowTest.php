<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test guest users are redirected to login.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test first-time user login redirects to PIN creation page.
     */
    public function test_first_time_login_without_pin_redirects_to_create_pin(): void
    {
        $user = User::create([
            'name' => 'First Time User',
            'phone' => '081234567890',
            'email' => 'first@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => null, // No PIN configured yet
            'role' => 'driver',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/create-pin');
    }

    /**
     * Test returning user with PIN configured can access the dashboard.
     */
    public function test_user_with_pin_can_access_dashboard(): void
    {
        $user = User::create([
            'name' => 'Returning User',
            'phone' => '081234567890',
            'email' => 'returning@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('123456'), // PIN configured
            'role' => 'driver',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test warehouse role restriction on e-stamp uploads.
     */
    public function test_warehouse_user_can_access_estamp_upload(): void
    {
        $user = User::create([
            'name' => 'Warehouse User',
            'phone' => '081234567890',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('123456'),
            'role' => 'warehouse',
        ]);

        $response = $this->actingAs($user)->get('/settings/upload-stamp');

        $response->assertStatus(200);
    }

    /**
     * Test driver role is rejected from e-stamp uploads.
     */
    public function test_driver_user_cannot_access_estamp_upload(): void
    {
        $user = User::create([
            'name' => 'Driver User',
            'phone' => '081234567890',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('123456'),
            'role' => 'driver',
        ]);

        $response = $this->actingAs($user)->get('/settings/upload-stamp');

        $response->assertStatus(403);
    }
}
