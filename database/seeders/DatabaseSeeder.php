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

        User::create([
            'name' => 'John Doe (Front Desk)',
            'phone' => '081234567891',
            'email' => 'frontdesk@example.com',
            'password' => Hash::make('password'),
            'role_id' => $frontDeskRole->id,
        ]);

        User::create([
            'name' => 'Alice Smith (Housekeeper)',
            'phone' => '081234567892',
            'email' => 'housekeeping@example.com',
            'password' => Hash::make('password'),
            'role_id' => $housekeeperRole->id,
        ]);

        // 3. Seed Room Types
        $standard = RoomType::create([
            'name' => 'Standard Room',
            'description' => 'A cozy room equipped with a queen-size bed, private bathroom, and high-speed Wi-Fi.',
            'price_per_night' => 150000.00,
        ]);

        $deluxe = RoomType::create([
            'name' => 'Deluxe Room',
            'description' => 'A spacious room with a king-size bed, mini-fridge, beautiful city view, and flat-screen TV.',
            'price_per_night' => 250000.00,
        ]);

        $suite = RoomType::create([
            'name' => 'Suite Room',
            'description' => 'Luxury suite containing a separate living area, kitchenette, bathtub, and complimentary refreshments.',
            'price_per_night' => 500000.00,
        ]);

        $executive = RoomType::create([
            'name' => 'Executive Room',
            'description' => 'Top-tier accommodation featuring executive lounge access, private workspace, balcony, and premium amenities.',
            'price_per_night' => 850000.00,
        ]);

        // 4. Seed Rooms
        $rooms = [
            // Standard Rooms (101 - 105)
            ['room_number' => '101', 'room_type_id' => $standard->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],
            ['room_number' => '102', 'room_type_id' => $standard->id, 'booking_status' => 'booked', 'cleaning_status' => 'clean'],
            ['room_number' => '103', 'room_type_id' => $standard->id, 'booking_status' => 'occupied', 'cleaning_status' => 'dirty'],
            ['room_number' => '104', 'room_type_id' => $standard->id, 'booking_status' => 'available', 'cleaning_status' => 'maintenance'],
            ['room_number' => '105', 'room_type_id' => $standard->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],

            // Deluxe Rooms (201 - 205)
            ['room_number' => '201', 'room_type_id' => $deluxe->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],
            ['room_number' => '202', 'room_type_id' => $deluxe->id, 'booking_status' => 'occupied', 'cleaning_status' => 'clean'],
            ['room_number' => '203', 'room_type_id' => $deluxe->id, 'booking_status' => 'booked', 'cleaning_status' => 'clean'],
            ['room_number' => '204', 'room_type_id' => $deluxe->id, 'booking_status' => 'available', 'cleaning_status' => 'dirty'],
            ['room_number' => '205', 'room_type_id' => $deluxe->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],

            // Suite Rooms (301 - 303)
            ['room_number' => '301', 'room_type_id' => $suite->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],
            ['room_number' => '302', 'room_type_id' => $suite->id, 'booking_status' => 'occupied', 'cleaning_status' => 'clean'],
            ['room_number' => '303', 'room_type_id' => $suite->id, 'booking_status' => 'booked', 'cleaning_status' => 'clean'],

            // Executive Rooms (401 - 402)
            ['room_number' => '401', 'room_type_id' => $executive->id, 'booking_status' => 'available', 'cleaning_status' => 'clean'],
            ['room_number' => '402', 'room_type_id' => $executive->id, 'booking_status' => 'occupied', 'cleaning_status' => 'clean'],
        ];

        $roomModels = [];
        foreach ($rooms as $room) {
            $roomModels[$room['room_number']] = Room::create($room);
        }

        // 5. Seed Services
        $extraBed = Service::create(['name' => 'Extra Bed', 'price' => 50000.00, 'type' => 'extra_bed']);
        $breakfast = Service::create(['name' => 'Breakfast Buffet', 'price' => 30000.00, 'type' => 'f_and_b']);
        $laundry = Service::create(['name' => 'Express Laundry', 'price' => 15000.00, 'type' => 'laundry']);
        $roomService = Service::create(['name' => 'Room Service Food & Drink', 'price' => 45000.00, 'type' => 'f_and_b']);

        // 6. Seed Bookings, Bills, & Payments
        
        // Booking 1: Confirmed future booking (Room 102)
        $bookingConfirmed = Booking::create([
            'guest_name' => 'Michael Green',
            'guest_id' => 'KTP-992837482',
            'room_id' => $roomModels['102']->id,
            'check_in_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(5)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'confirmed',
        ]);

        $roomCharges1 = $standard->price_per_night * 3;
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
            'payment_method' => 'bank_transfer',
            'status' => 'confirmed',
        ]);

        // Booking 2: Occupied room with active billing (Room 103)
        $bookingOccupied1 = Booking::create([
            'guest_name' => 'Jane Wilson',
            'guest_id' => 'KTP-1122334455',
            'room_id' => $roomModels['103']->id,
            'check_in_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
            'number_of_guests' => 1,
            'status' => 'checked_in',
        ]);

        $roomCharges2 = $standard->price_per_night * 3;
        $billOccupied1 = GuestBill::create([
            'booking_id' => $bookingOccupied1->id,
            'deposit_amount' => 50000.00,
            'total_room_charges' => $roomCharges2,
            'total_extra_charges' => 65000.00, // 1 Breakfast + 1 Laundry + 1 Room Service (30k + 15k + 45k, let's say some items)
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
            'payment_method' => 'cash',
            'status' => 'confirmed',
        ]);

        // Booking 3: Occupied room (Room 202)
        $bookingOccupied2 = Booking::create([
            'guest_name' => 'David Brown',
            'guest_id' => 'PASSPORT-A992837',
            'room_id' => $roomModels['202']->id,
            'check_in_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'checked_in',
        ]);

        $roomCharges3 = $deluxe->price_per_night * 3;
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
            'payment_method' => 'bank_transfer',
            'status' => 'confirmed',
        ]);

        // Booking 4: Checked-out past booking (Room 101)
        $bookingCompleted = Booking::create([
            'guest_name' => 'Sarah Connor',
            'guest_id' => 'KTP-887766554',
            'room_id' => $roomModels['101']->id,
            'check_in_date' => Carbon::now()->subDays(6)->format('Y-m-d'),
            'check_out_date' => Carbon::now()->subDays(3)->format('Y-m-d'),
            'number_of_guests' => 2,
            'status' => 'checked_out',
        ]);

        $roomCharges4 = $standard->price_per_night * 3;
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
            'payment_method' => 'cash',
            'status' => 'confirmed',
        ]);

        // Final Payment
        Payment::create([
            'booking_id' => $bookingCompleted->id,
            'amount' => 410000.00,
            'payment_method' => 'cash',
            'status' => 'confirmed',
        ]);
    }
}
