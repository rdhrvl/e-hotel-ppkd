<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\RoomType;
use App\Models\Room;
use App\Models\Service;
use App\Models\Booking;
use App\Models\GuestBill;
use App\Models\BookingItem;
use App\Models\Payment;
use App\Models\Branch;
use App\Models\Guest;
use App\Models\HousekeepingTask;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $superAdminRole = Role::create([
            'name' => 'Super Administrator',
            'slug' => 'superadmin',
        ]);

        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
        ]);

        $frontDeskRole = Role::create([
            'name' => 'Front Desk Staff',
            'slug' => 'front_desk',
        ]);

        $housekeeperRole = Role::create([
            'name' => 'Housekeeping Staff',
            'slug' => 'housekeeping',
        ]);

        $fnbRole = Role::create([
            'name' => 'Food & Beverage Staff',
            'slug' => 'fnb',
        ]);

        // 2. Seed Users
        User::create([
            'name' => 'Super Admin',
            'phone' => '081234567899',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
        ]);

        User::create([
            'name' => 'Bob Johnson (Admin)',
            'phone' => '081234567890',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $frontDeskUser = User::create([
            'name' => 'John Doe (Front Desk)',
            'phone' => '081234567891',
            'email' => 'frontdesk@example.com',
            'password' => Hash::make('password'),
            'role_id' => $frontDeskRole->id,
        ]);

        $housekeeperUser = User::create([
            'name' => 'Alice Smith (Housekeeper)',
            'phone' => '081234567892',
            'email' => 'housekeeping@example.com',
            'password' => Hash::make('password'),
            'role_id' => $housekeeperRole->id,
        ]);

        User::create([
            'name' => 'Charlie Food (F&B)',
            'phone' => '081234567893',
            'email' => 'fnb@example.com',
            'password' => Hash::make('password'),
            'role_id' => $fnbRole->id,
        ]);

        // 3. Seed Branches
        $branch1 = Branch::create([
            'name' => 'Grand Central Branch',
            'address' => '123 Main St, Jakarta',
        ]);
        $branch2 = Branch::create([
            'name' => 'Seaside Resort Branch',
            'address' => '456 Beach Rd, Bali',
        ]);

        // 4. Seed Room Types
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'capacity' => 2,
            'base_price' => 150000.00,
            'description' => 'A cozy room equipped with a queen-size bed, private bathroom, and high-speed Wi-Fi.',
            'bed_type' => 'Queen',
            'has_breakfast' => false,
        ]);

        $deluxe = RoomType::create([
            'name' => 'Deluxe Room',
            'capacity' => 2,
            'base_price' => 250000.00,
            'description' => 'A spacious room with a king-size bed, mini-fridge, beautiful city view, and flat-screen TV.',
            'bed_type' => 'King',
            'has_breakfast' => true,
        ]);

        $suite = RoomType::create([
            'name' => 'Suite Room',
            'capacity' => 4,
            'base_price' => 500000.00,
            'description' => 'Luxury suite containing a separate living area, kitchenette, bathtub, and complimentary refreshments.',
            'bed_type' => 'King',
            'has_breakfast' => true,
        ]);

        $executive = RoomType::create([
            'name' => 'Executive Room',
            'capacity' => 2,
            'base_price' => 850000.00,
            'description' => 'Top-tier accommodation featuring executive lounge access, private workspace, balcony, and premium amenities.',
            'bed_type' => 'Super King',
            'has_breakfast' => true,
        ]);

        // 5. Seed Rooms
        $rooms = [
            // Floor 1 (101 - 120) — Mixed Room Types
            ['room_number' => '101', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 1, 'notes' => 'Balcony view'],
            ['room_number' => '102', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Extra clean requests'],
            ['room_number' => '103', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Double single beds'],
            ['room_number' => '104', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 1, 'notes' => 'Near stairs'],
            ['room_number' => '105', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Double single beds'],
            ['room_number' => '106', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 1, 'notes' => 'City view'],
            ['room_number' => '107', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Corner suite'],
            ['room_number' => '108', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 1, 'notes' => 'Balcony view'],
            ['room_number' => '109', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Street view'],
            ['room_number' => '110', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'cleaning', 'floor' => 1, 'notes' => 'Needs deep clean'],
            ['room_number' => '111', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => ''],
            ['room_number' => '112', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Double single beds'],
            ['room_number' => '113', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => ''],
            ['room_number' => '114', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Pool view'],
            ['room_number' => '115', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 1, 'notes' => 'Extra clean requests'],
            ['room_number' => '116', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => ''],
            ['room_number' => '117', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 1, 'notes' => 'Quiet area'],
            ['room_number' => '118', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'maintenance', 'floor' => 1, 'notes' => 'Electrical check'],
            ['room_number' => '119', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 1, 'notes' => 'Near elevator'],
            ['room_number' => '120', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 1, 'notes' => 'Near elevator'],
 
            // Floor 2 (201 - 220) — Mixed Room Types
            ['room_number' => '201', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 2, 'notes' => 'Garden view'],
            ['room_number' => '202', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => ''],
            ['room_number' => '203', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => ''],
            ['room_number' => '204', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'cleaning', 'floor' => 2, 'notes' => 'Post-checkout cleaning'],
            ['room_number' => '205', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Private lounge access'],
            ['room_number' => '206', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 2, 'notes' => ''],
            ['room_number' => '207', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Street view'],
            ['room_number' => '208', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 2, 'notes' => 'Street view'],
            ['room_number' => '209', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => ''],
            ['room_number' => '210', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'maintenance', 'floor' => 2, 'notes' => 'Plumbing fix'],
            ['room_number' => '211', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Quiet area'],
            ['room_number' => '212', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Double single beds'],
            ['room_number' => '213', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 2, 'notes' => 'Family stay'],
            ['room_number' => '214', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Quiet area'],
            ['room_number' => '215', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => ''],
            ['room_number' => '216', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 2, 'notes' => 'Near stairs'],
            ['room_number' => '217', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 2, 'notes' => 'Skyline view'],
            ['room_number' => '218', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => ''],
            ['room_number' => '219', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 2, 'notes' => 'Pool view'],
            ['room_number' => '220', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 2, 'notes' => 'Street view'],
 
            // Floor 3 (301 - 320) — Mixed Room Types
            ['room_number' => '301', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Near stairs'],
            ['room_number' => '302', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Garden view'],
            ['room_number' => '303', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Pool view'],
            ['room_number' => '304', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'maintenance', 'floor' => 3, 'notes' => 'AC repair'],
            ['room_number' => '305', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => ''],
            ['room_number' => '306', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 3, 'notes' => 'VIP suite'],
            ['room_number' => '307', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 3, 'notes' => ''],
            ['room_number' => '308', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 3, 'notes' => 'Near stairs'],
            ['room_number' => '309', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Garden view'],
            ['room_number' => '310', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Quiet area'],
            ['room_number' => '311', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 3, 'notes' => 'Skyline view'],
            ['room_number' => '312', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Quiet area'],
            ['room_number' => '313', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 3, 'notes' => 'Near stairs'],
            ['room_number' => '314', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'cleaning', 'floor' => 3, 'notes' => 'Needs deep clean'],
            ['room_number' => '315', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Near stairs'],
            ['room_number' => '316', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Extra clean requests'],
            ['room_number' => '317', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'Street view'],
            ['room_number' => '318', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 3, 'notes' => 'City view'],
            ['room_number' => '319', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 3, 'notes' => 'City view'],
            ['room_number' => '320', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 3, 'notes' => 'Skyline view'],
 
            // Floor 4 (401 - 420) — Mixed Room Types
            ['room_number' => '401', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Quiet area'],
            ['room_number' => '402', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Near elevator'],
            ['room_number' => '403', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Near elevator'],
            ['room_number' => '404', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Extra clean requests'],
            ['room_number' => '405', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'maintenance', 'floor' => 4, 'notes' => 'AC repair'],
            ['room_number' => '406', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 4, 'notes' => 'Pool view'],
            ['room_number' => '407', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Quiet area'],
            ['room_number' => '408', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'cleaning', 'floor' => 4, 'notes' => 'Post-checkout cleaning'],
            ['room_number' => '409', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => ''],
            ['room_number' => '410', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Family stay'],
            ['room_number' => '411', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Near stairs'],
            ['room_number' => '412', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 4, 'notes' => 'Garden view'],
            ['room_number' => '413', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 4, 'notes' => ''],
            ['room_number' => '414', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Pool view'],
            ['room_number' => '415', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'Corner suite'],
            ['room_number' => '416', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 4, 'notes' => 'Quiet area'],
            ['room_number' => '417', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 4, 'notes' => ''],
            ['room_number' => '418', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 4, 'notes' => 'Street view'],
            ['room_number' => '419', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 4, 'notes' => 'Quiet area'],
            ['room_number' => '420', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 4, 'notes' => 'City view'],
 
            // Floor 5 (501 - 520) — Mixed Room Types
            ['room_number' => '501', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => ''],
            ['room_number' => '502', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Needs deep clean'],
            ['room_number' => '503', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 5, 'notes' => 'Street view'],
            ['room_number' => '504', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Garden view'],
            ['room_number' => '505', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Street view'],
            ['room_number' => '506', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'cleaning', 'floor' => 5, 'notes' => 'Needs deep clean'],
            ['room_number' => '507', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 5, 'notes' => 'Quiet area'],
            ['room_number' => '508', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Street view'],
            ['room_number' => '509', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 5, 'notes' => 'City view'],
            ['room_number' => '510', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Street view'],
            ['room_number' => '511', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 5, 'notes' => 'Double single beds'],
            ['room_number' => '512', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Street view'],
            ['room_number' => '513', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'VIP suite'],
            ['room_number' => '514', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Corner suite'],
            ['room_number' => '515', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'maintenance', 'floor' => 5, 'notes' => 'Electrical check'],
            ['room_number' => '516', 'room_type_id' => $suite->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => ''],
            ['room_number' => '517', 'room_type_id' => $executive->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 5, 'notes' => ''],
            ['room_number' => '518', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'available', 'floor' => 5, 'notes' => 'Garden view'],
            ['room_number' => '519', 'room_type_id' => $deluxe->id, 'branch_id' => $branch1->id, 'status' => 'occupied', 'floor' => 5, 'notes' => 'Garden view'],
            ['room_number' => '520', 'room_type_id' => $standard->id, 'branch_id' => $branch1->id, 'status' => 'reserved', 'floor' => 5, 'notes' => 'Extra clean requests'],
        ];

        $roomModels = [];
        foreach ($rooms as $room) {
            $roomModels[$room['room_number']] = Room::create($room);
        }

        // 6. Seed Guests
        $guest1 = Guest::create(['name' => 'Michael Green', 'email' => 'michael@example.com', 'phone' => '081299998888', 'identity_number' => 'KTP-992837482', 'address' => 'St. Petersburg']);
        $guest2 = Guest::create(['name' => 'Jane Wilson', 'email' => 'jane@example.com', 'phone' => '081277776666', 'identity_number' => 'KTP-1122334455', 'address' => 'Los Angeles']);
        $guest3 = Guest::create(['name' => 'David Brown', 'email' => 'david@example.com', 'phone' => '081255554444', 'identity_number' => 'PASSPORT-A992837', 'address' => 'London']);
        $guest4 = Guest::create(['name' => 'Sarah Connor', 'email' => 'sarah@example.com', 'phone' => '081233332222', 'identity_number' => 'KTP-887766554', 'address' => 'San Francisco']);

        // 7. Seed Services
        $extraBed = Service::create(['name' => 'Extra Bed', 'price' => 50000.00, 'type' => 'extra_bed']);
        $breakfast = Service::create(['name' => 'Breakfast Buffet', 'price' => 30000.00, 'type' => 'f_and_b']);
        $laundry = Service::create(['name' => 'Express Laundry', 'price' => 15000.00, 'type' => 'laundry']);
        $roomService = Service::create(['name' => 'Room Service Food & Drink', 'price' => 45000.00, 'type' => 'f_and_b']);

        // 8. Seed Bookings, Bills, & Payments

        // Booking 1: Confirmed future booking (Room 102)
        $bookingConfirmed = Booking::create([
            'guest_id' => $guest1->id,
            'room_id' => $roomModels['102']->id,
            'check_in_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'confirmed',
            'total_price' => $standard->base_price * 3,
        ]);

        $roomCharges1 = $standard->base_price * 3;
        GuestBill::create([
            'booking_id' => $bookingConfirmed->id,
            'deposit_amount' => 100000.00,
            'total_room_charges' => $roomCharges1,
            'total_extra_charges' => 0.00,
            'paid_amount' => 0.00,
            'status' => 'unpaid',
        ]);

        Payment::create([
            'booking_id' => $bookingConfirmed->id,
            'amount' => 100000.00, // Deposit paid
            'method' => 'transfer',
            'status' => 'paid',
        ]);

        // Booking 2: Occupied room with active billing (Room 103)
        $bookingOccupied1 = Booking::create([
            'guest_id' => $guest2->id,
            'room_id' => $roomModels['103']->id,
            'check_in_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'number_of_guests' => 1,
            'status' => 'checked_in',
            'total_price' => $standard->base_price * 3,
        ]);

        $roomCharges2 = $standard->base_price * 3;
        $billOccupied1 = GuestBill::create([
            'booking_id' => $bookingOccupied1->id,
            'deposit_amount' => 50000.00,
            'total_room_charges' => $roomCharges2,
            'total_extra_charges' => 45000.00, // 1 Breakfast + 1 Laundry
            'paid_amount' => 0.00,
            'status' => 'unpaid',
        ]);

        BookingItem::create([
            'booking_id' => $bookingOccupied1->id,
            'service_id' => $breakfast->id,
            'quantity' => 1,
            'price' => $breakfast->price,
        ]);

        BookingItem::create([
            'booking_id' => $bookingOccupied1->id,
            'service_id' => $laundry->id,
            'quantity' => 1,
            'price' => $laundry->price,
        ]);

        Payment::create([
            'booking_id' => $bookingOccupied1->id,
            'amount' => 50000.00, // Deposit
            'method' => 'cash',
            'status' => 'paid',
        ]);

        // Booking 3: Occupied room (Room 202)
        $bookingOccupied2 = Booking::create([
            'guest_id' => $guest3->id,
            'room_id' => $roomModels['202']->id,
            'check_in_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'checked_in',
            'total_price' => $deluxe->base_price * 3,
        ]);

        $roomCharges3 = $deluxe->base_price * 3;
        GuestBill::create([
            'booking_id' => $bookingOccupied2->id,
            'deposit_amount' => 200000.00,
            'total_room_charges' => $roomCharges3,
            'total_extra_charges' => 50000.00, // Extra bed
            'paid_amount' => 0.00,
            'status' => 'unpaid',
        ]);

        BookingItem::create([
            'booking_id' => $bookingOccupied2->id,
            'service_id' => $extraBed->id,
            'quantity' => 1,
            'price' => $extraBed->price,
        ]);

        Payment::create([
            'booking_id' => $bookingOccupied2->id,
            'amount' => 200000.00, // Deposit
            'method' => 'transfer',
            'status' => 'paid',
        ]);

        // Booking 4: Checked-out past booking (Room 101)
        $bookingCompleted = Booking::create([
            'guest_id' => $guest4->id,
            'room_id' => $roomModels['101']->id,
            'check_in_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'checked_out',
            'total_price' => $standard->base_price * 3,
        ]);

        $roomCharges4 = $standard->base_price * 3;
        $billCompleted = GuestBill::create([
            'booking_id' => $bookingCompleted->id,
            'deposit_amount' => 100000.00,
            'total_room_charges' => $roomCharges4,
            'total_extra_charges' => 60000.00, // 2 breakfasts
            'paid_amount' => 410000.00, // (450 + 60) - 100 paid
            'status' => 'paid',
        ]);

        BookingItem::create([
            'booking_id' => $bookingCompleted->id,
            'service_id' => $breakfast->id,
            'quantity' => 2,
            'price' => $breakfast->price,
        ]);

        // Deposit
        Payment::create([
            'booking_id' => $bookingCompleted->id,
            'amount' => 100000.00,
            'method' => 'cash',
            'status' => 'paid',
        ]);

        // Final Payment
        Payment::create([
            'booking_id' => $bookingCompleted->id,
            'amount' => 410000.00,
            'method' => 'cash',
            'status' => 'paid',
        ]);

        // Seed some Housekeeping Tasks
        HousekeepingTask::create([
            'room_id' => $roomModels['204']->id,
            'staff_id' => $housekeeperUser->id,
            'schedule_date' => Carbon::now()->toDateString(),
            'status' => 'in_progress',
        ]);
        HousekeepingTask::create([
            'room_id' => $roomModels['103']->id,
            'staff_id' => $housekeeperUser->id,
            'schedule_date' => Carbon::now()->toDateString(),
            'status' => 'scheduled',
        ]);
    }
}
