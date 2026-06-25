<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\GuestBill;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Service;
use App\Models\Guest;
use App\Models\HousekeepingTask;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class BookingService
{
    /**
     * Helper to write audit logs.
     */
    protected function logActivity(string $action, ?string $entityType = null, ?int $entityId = null, ?array $oldValue = null, ?array $newValue = null): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

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

            // Find or create guest
            $guest = Guest::firstOrCreate(
                ['identity_number' => $data['guest_id']],
                [
                    'name' => $data['guest_name'],
                    'phone' => $data['guest_phone'] ?? null,
                    'email' => $data['guest_email'] ?? null,
                    'address' => $data['guest_address'] ?? null,
                ]
            );

            // Update/fill with latest registration details
            $guest->fill([
                'name' => $data['guest_name'],
                'phone' => $data['guest_phone'] ?? $guest->phone,
                'email' => $data['guest_email'] ?? $guest->email,
                'address' => $data['guest_address'] ?? $guest->address,
                'profession' => $data['guest_profession'] ?? null,
                'company' => $data['guest_company'] ?? null,
                'nationality' => $data['guest_nationality'] ?? 'Indonesian',
                'birth_date' => !empty($data['guest_birth_date']) ? $data['guest_birth_date'] : null,
                'member_no' => $data['guest_member_no'] ?? null,
            ])->save();

            // Prevent double booking
            if (!$this->isRoomAvailable($roomId, $checkIn, $checkOut)) {
                throw new InvalidArgumentException('The selected room is not available for the chosen dates.');
            }

            $room = Room::findOrFail($roomId);
            
            // Calculate nights
            $nights = (int) Carbon::parse($checkIn)->diffInDays(Carbon::parse($checkOut));
            $roomCharges = $room->effective_price * $nights;

            // Create booking
            $booking = Booking::create([
                'guest_id' => $guest->id,
                'room_id' => $roomId,
                'check_in_date' => $checkIn,
                'check_out_date' => $checkOut,
                'number_of_guests' => $data['number_of_guests'] ?? 1,
                'status' => 'confirmed',
                'total_price' => $roomCharges,
                'arrival_time' => $data['arrival_time'] ?? null,
                'box_no' => $data['box_no'] ?? null,
                'box_issued_by' => $data['box_issued_by'] ?? null,
                'box_date' => !empty($data['box_date']) ? $data['box_date'] : null,
                'payment_method' => $data['payment_method'] ?? 'cash',
                'notes' => $data['notes'] ?? null,
                'book_by' => $data['book_by'] ?? null,
            ]);

            // Create guest bill
            GuestBill::create([
                'booking_id' => $booking->id,
                'deposit_amount' => 0.00,
                'total_room_charges' => $roomCharges,
                'total_extra_charges' => 0.00,
                'paid_amount' => 0.00,
                'status' => 'unpaid',
            ]);

            // Set room status to reserved
            $room->update(['status' => 'reserved']);

            $this->logActivity(
                action: 'Create Booking',
                entityType: Booking::class,
                entityId: $booking->id,
                newValue: $booking->toArray()
            );

            return $booking;
        });
    }

    /**
     * Derive bill status from current totals.
     * unpaid → partially_paid → paid
     */
    private function recalculateBillStatus(GuestBill $bill): string
    {
        $bill->refresh();
        return $bill->calculateStatus();
    }

    /**
     * Check-in a guest for their booking.
     */
    public function checkIn(Booking $booking, float $depositAmount, string $paymentMethod = 'cash'): void
    {
        DB::transaction(function () use ($booking, $depositAmount, $paymentMethod) {
            if ($booking->status !== 'confirmed' && $booking->status !== 'pending') {
                throw new InvalidArgumentException('Only pending or confirmed bookings can be checked in.');
            }

            if ($booking->room->status !== 'ready') {
                throw new InvalidArgumentException('Room status must be ready (Ready for Check-In) before check-in.');
            }

            $oldBooking = $booking->toArray();
            $booking->update(['status' => 'checked_in']);
            $booking->room->update(['status' => 'occupied']);

            $bill = $booking->guestBill;
            if ($bill) {
                $bill->increment('deposit_amount', $depositAmount);

                if ($depositAmount > 0) {
                    Payment::create([
                        'booking_id' => $booking->id,
                        'amount' => $depositAmount,
                        'method' => $paymentMethod === 'bank_transfer' ? 'transfer' : $paymentMethod,
                        'status' => 'paid',
                    ]);
                }

                $bill->update(['status' => $this->recalculateBillStatus($bill)]);
            }

            $this->logActivity(
                action: 'Check-In Guest',
                entityType: Booking::class,
                entityId: $booking->id,
                oldValue: $oldBooking,
                newValue: $booking->fresh()->toArray()
            );
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

            $item = BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $serviceId,
                'quantity' => $quantity,
                'price' => $price,
                'notes' => $notes,
            ]);

            $bill = $booking->guestBill;
            if ($bill) {
                $bill->increment('total_extra_charges', $price * $quantity);
                $bill->update(['status' => $this->recalculateBillStatus($bill)]);
            }

            $this->logActivity(
                action: 'Add Service Charge',
                entityType: BookingItem::class,
                entityId: $item->id,
                newValue: $item->toArray()
            );

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

            $totalAmount = (float) $bill->total_room_charges + (float) $bill->total_extra_charges;
            $dueAmount = $totalAmount - (float) $bill->deposit_amount - (float) $bill->paid_amount;

            if ($dueAmount > 0) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $dueAmount,
                    'method' => $paymentMethod === 'bank_transfer' ? 'transfer' : $paymentMethod,
                    'status' => 'paid',
                ]);
                $bill->increment('paid_amount', $dueAmount);
            } elseif ($dueAmount < 0) {
                // ponytail: record overpayment for front desk to handle (refund or credit)
                $bill->update(['overpayment_amount' => abs($dueAmount)]);
            }

            $bill->update(['status' => 'paid']);

            $oldBooking = $booking->toArray();
            $booking->update(['status' => 'checked_out']);

            $booking->room->update(['status' => 'cleaning']);

            // Avoid duplicate housekeeping tasks if one was already requested and is in progress/scheduled
            $hasActiveTask = HousekeepingTask::where('room_id', $booking->room_id)
                ->where('created_at', '>=', $booking->created_at)
                ->whereIn('status', ['scheduled', 'in_progress'])
                ->exists();

            if (!$hasActiveTask) {
                $housekeeper = User::whereHas('role', function ($q) {
                    $q->where('slug', 'housekeeping');
                })->first();

                HousekeepingTask::create([
                    'room_id' => $booking->room_id,
                    'staff_id' => $housekeeper ? $housekeeper->id : 1,
                    'schedule_date' => Carbon::now()->toDateString(),
                    'status' => 'in_progress',
                ]);
            }

            $this->logActivity(
                action: 'Check-Out Guest',
                entityType: Booking::class,
                entityId: $booking->id,
                oldValue: $oldBooking,
                newValue: $booking->fresh()->toArray()
            );
        });
    }
}
