<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->json('permissions')->nullable()->after('slug');
        });

        // Seed default permissions for roles
        $defaultPermissions = [
            'superadmin' => [
                'view_dashboard', 'view_rooms', 'view_users', 'view_services',
                'view_audit_logs', 'view_room_availability', 'view_booking',
                'view_bookings', 'view_guest_bills', 'view_guests', 'view_payments',
                'view_housekeeping', 'view_fnb'
            ],
            'admin' => [
                'view_dashboard', 'view_rooms', 'view_users', 'view_services',
                'view_audit_logs', 'view_room_availability', 'view_booking',
                'view_bookings', 'view_guest_bills', 'view_guests', 'view_payments',
                'view_housekeeping', 'view_fnb'
            ],
            'front_desk' => [
                'view_dashboard', 'view_room_availability', 'view_booking',
                'view_bookings', 'view_guest_bills', 'view_guests', 'view_payments'
            ],
            'housekeeping' => [
                'view_dashboard', 'view_housekeeping'
            ],
            'fnb' => [
                'view_dashboard', 'view_fnb'
            ]
        ];

        foreach ($defaultPermissions as $slug => $permissions) {
            DB::table('roles')
                ->where('slug', $slug)
                ->update(['permissions' => json_encode($permissions)]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('permissions');
        });
    }
};
