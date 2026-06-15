<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DeliveryNote;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Users
        $driver = User::create([
            'name' => 'John Doe (Driver)',
            'phone' => '081234567890',
            'email' => 'driver@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('123456'),
            'role' => 'driver',
        ]);

        $warehouse = User::create([
            'name' => 'Alice Smith (Warehouse)',
            'phone' => '081234567891',
            'email' => 'warehouse@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => Hash::make('123456'),
            'role' => 'warehouse',
        ]);

        $admin = User::create([
            'name' => 'Bob Johnson (Admin)',
            'phone' => '081234567892',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'pin_hash' => null, // No PIN to test first-time setup redirect!
            'role' => 'admin',
        ]);

        // 2. Create Delivery Notes
        // 5 Active Delivery Notes for Driver
        for ($i = 1; $i <= 5; $i++) {
            DeliveryNote::create([
                'user_id' => $driver->id,
                'dn_number' => 'DN-2026-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'origin' => 'Warehouse Center ' . chr(64 + $i),
                'destination' => 'Client Location ' . $i,
                'items_count' => rand(10, 150),
                'status' => $i % 2 === 0 ? 'in_transit' : 'active',
                'notes' => 'Fragile cargo, handle with care.',
            ]);
        }

        // 5 Completed Delivery Notes for Driver
        for ($i = 6; $i <= 10; $i++) {
            DeliveryNote::create([
                'user_id' => $driver->id,
                'dn_number' => 'DN-2026-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'origin' => 'Warehouse Center ' . chr(64 + $i - 5),
                'destination' => 'Client Location ' . ($i - 5),
                'items_count' => rand(10, 150),
                'status' => 'completed',
                'completed_at' => now()->subDays(11 - $i)->subHours(rand(1, 10)),
                'notes' => 'Delivered on time.',
            ]);
        }

        // 5 Active Delivery Notes for Warehouse User
        for ($i = 11; $i <= 15; $i++) {
            DeliveryNote::create([
                'user_id' => $warehouse->id,
                'dn_number' => 'DN-2026-' . str_pad((string)$i, 4, '0', STR_PAD_LEFT),
                'origin' => 'Factory Headquarter',
                'destination' => 'Warehouse Bay ' . ($i - 10),
                'items_count' => rand(50, 500),
                'status' => 'active',
            ]);
        }

        // 3. Create Notifications
        UserNotification::create([
            'user_id' => $driver->id,
            'type' => 'delivery_checkpoint',
            'title' => 'New DN Assigned',
            'message' => 'Delivery Note DN-2026-0001 has been assigned to you.',
            'is_read' => false,
        ]);

        UserNotification::create([
            'user_id' => $driver->id,
            'type' => 'pin_change',
            'title' => 'PIN Code Configured',
            'message' => 'Your security transaction PIN has been set up successfully.',
            'is_read' => true,
        ]);

        UserNotification::create([
            'user_id' => $driver->id,
            'type' => 'delivery_checkpoint',
            'title' => 'Transit Started',
            'message' => 'Delivery Note DN-2026-0002 is now in transit.',
            'is_read' => false,
        ]);

        UserNotification::create([
            'user_id' => $driver->id,
            'type' => 'upload_success',
            'title' => 'E-Signature Saved',
            'message' => 'Your electronic signature was successfully uploaded and registered.',
            'is_read' => false,
        ]);

        UserNotification::create([
            'user_id' => $warehouse->id,
            'type' => 'delivery_checkpoint',
            'title' => 'Inbound Delivery Note',
            'message' => 'Inbound delivery note DN-2026-0011 requires verification.',
            'is_read' => false,
        ]);
        
        UserNotification::create([
            'user_id' => $warehouse->id,
            'type' => 'upload_success',
            'title' => 'E-Stamp Saved',
            'message' => 'Warehouse e-stamp has been successfully registered.',
            'is_read' => true,
        ]);
    }
}
