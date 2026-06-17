<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\GuestBill;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Service;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BookingService
{
    /**
     * Check if a room is booked/occupied during a specified date range.
     */
    public function isRoomAvailable(int $roomId, string $checkIn, string $checkOut, ?int $excludeBookingId = null): bool
    {
        $start = Carbon::parse($checkIn)->format('Y-m-d');
        $end = Carbon::parse($checkOut)->format('Y-m-d');

        if ($start >= $end) {
            throw new InvalidArgumentException('Check-out date must be after check-in date.');
        }

        return !Booking::where('room_id', $roomId)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->when($excludeBookingId, function ($query) use ($excludeBookingId) {
                return $query->where('id', '!=', $excludeBookingId);
            })
            ->where(function ($query) use ($start, $end) {
                $query->where('check_in_date', '<', $end)
                      ->where('check_out_date', '>', $start);
            })
            ->exists();
    }

    /**
     * Create a new booking reservation.
     */
    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data) {
            $roomId = (int) $data['room_id'];
            $checkIn = $data['check_in_date'];
            $checkOut = $data['check_out_date'];

            // Prevent double booking
            if (!$this->isRoomAvailable($roomId, $checkIn, $checkOut)) {
                throw new InvalidArgumentException('The selected room is not available for the chosen dates.');
            }

            // Create booking
            $booking = Booking::create([
                'guest_name' => $data['guest_name'],
                'guest_id' => $data['guest_id'],
                'room_id' => $roomId,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'number_of_guests' => $data['number_of_guests'] ?? 1,
                'status' => 'confirmed',
            ]);

            // Calculate room charges
            $room = Room::findOrFail($roomId);
            $nights = $booking->nights;
            $roomCharges = $room->effective_price * $nights;

            // Create empty guest bill
            GuestBill::create([
                'booking_id' => $booking->id,
                'deposit_amount' => 0.00,
                'total_room_charges' => $roomCharges,
                'total_extra_charges' => 0.00,
                'paid_amount' => 0.00,
                'status' => 'unpaid',
            ]);

            // Set room status to booked
            $room->update(['booking_status' => 'booked']);

            return $booking;
        });
    }

    /**
     * Check-in a guest for their booking.
     */
    public function checkIn(Booking $booking, float $depositAmount): void
    {
        DB::transaction(function () use ($booking, $depositAmount) {
            if ($booking->status !== 'confirmed' && $booking->status !== 'pending') {
                throw new InvalidArgumentException('Only pending or confirmed bookings can be checked in.');
            }

            $booking->update(['status' => 'checked_in']);
            $booking->room->update(['booking_status' => 'occupied']);

            $bill = $booking->guestBill;
            if ($bill) {
                $bill->update([
                    'deposit_amount' => $depositAmount,
                ]);

                if ($depositAmount > 0) {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $depositAmount,
                        'payment_method' => 'cash', // Default deposit to cash
                        'status' => 'confirmed',
                    ]);
                }
            }
        });
    }

    /**
     * Charge a service (extra bed, food, laundry) to a booking.
     */
    public function addServiceCharge(Booking $booking, int $serviceId, int $quantity, ?string $notes = null): BookingItem
    {
        return DB::transaction(function () use ($booking, $serviceId, $quantity, $notes) {
            if ($booking->status !== 'checked_in') {
                throw new InvalidArgumentException('Services can only be added to currently checked-in guests.');
            }

            $service = Service::findOrFail($serviceId);
            $price = (float) $service->price;

            // Create booking item
            $item = BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $serviceId,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => $notes,
            ]);

            // Update bill extra charges
            $bill = $booking->guestBill;
            if ($bill) {
                $bill->increment('total_extra_charges', $price * $quantity);
            }

            return $item;
        });
    }

    /**
     * Complete check-out, record final payment, and free the room.
     */
    public function checkOut(Booking $booking, string $paymentMethod): void
    {
        DB::transaction(function () use ($booking, $paymentMethod) {
            if ($booking->status !== 'checked_in') {
                throw new InvalidArgumentException('Only checked-in bookings can be checked out.');
            }

            $bill = $booking->guestBill;
            if (!$bill) {
                throw new InvalidArgumentException('Guest bill not found.');
            }

            // Calculate final payment due: total - deposit - already paid
            $totalAmount = $bill->total_room_charges + $bill->total_extra_charges;
            $dueAmount = $totalAmount - $bill->deposit_amount - $bill->paid_amount;

            if ($dueAmount > 0) {
                // Record final payment
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $dueAmount,
                    'payment_method' => $paymentMethod,
                    'status' => 'confirmed',
                ]);
            }

            // Update bill
            $bill->update([
                'paid_amount' => $bill->paid_amount + $dueAmount,
                'status' => 'paid',
            ]);

            // Update booking status
            $booking->update(['status' => 'checked_out']);

            // Free room: booking status back to available, and mark it dirty for housekeeping
            $booking->room->update([
                'booking_status' => 'available',
                'cleaning_status' => 'dirty',
            ]);
        });
    }
}
