<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Reservation Registry')]
class Bookings extends Component
{
    public string $search = '';
    public string $filterStatus = '';

    // Check In Modal
    public bool $showCheckInModal = false;
    public ?int $bookingIdToCheckIn = null;
    public float $depositAmount = 50000;

    // Check Out Modal
    public bool $showCheckOutModal = false;
    public ?int $bookingIdToCheckOut = null;
    public string $paymentMethod = 'cash';

    public function render(): \Illuminate\Contracts\View\View
    {
        $bookings = Booking::with(['room.roomType', 'guestBill'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('guest_name', 'like', '%' . $this->search . '%')
                      ->orWhere('guest_id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->get();

        $checkInBooking = $this->bookingIdToCheckIn ? Booking::with('room.roomType')->find($this->bookingIdToCheckIn) : null;
        $checkOutBooking = $this->bookingIdToCheckOut ? Booking::with(['room.roomType', 'guestBill', 'bookingItems.service'])->find($this->bookingIdToCheckOut) : null;

        return view('livewire.dashboard.bookings', [
            'bookings' => $bookings,
            'checkInBooking' => $checkInBooking,
            'checkOutBooking' => $checkOutBooking,
        ]);
    }

    public function cancelBooking(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        
        if ($booking->status === 'confirmed' || $booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);
            $booking->room->update(['booking_status' => 'available']);
            session()->flash('success', "Booking for {$booking->guest_name} cancelled successfully.");
        } else {
            session()->flash('error', "Cannot cancel a booking that is already {$booking->status}.");
        }
    }

    // CHECK-IN OPERATIONS
    public function openCheckInModal(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->bookingIdToCheckIn = $booking->id;
        $this->depositAmount = 50000;
        $this->showCheckInModal = true;
    }

    public function closeCheckInModal(): void
    {
        $this->showCheckInModal = false;
        $this->bookingIdToCheckIn = null;
    }

    public function checkInGuest(BookingService $bookingService): void
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:0',
        ]);

        $booking = Booking::findOrFail($this->bookingIdToCheckIn);

        try {
            $bookingService->checkIn($booking, (float) $this->depositAmount);
            session()->flash('success', "Guest {$booking->guest_name} checked in successfully!");
            $this->closeCheckInModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    // CHECK-OUT OPERATIONS
    public function openCheckOutModal(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->bookingIdToCheckOut = $booking->id;
        $this->paymentMethod = 'cash';
        $this->showCheckOutModal = true;
    }

    public function closeCheckOutModal(): void
    {
        $this->showCheckOutModal = false;
        $this->bookingIdToCheckOut = null;
    }

    public function checkOutGuest(BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($this->bookingIdToCheckOut);

        try {
            $bookingService->checkOut($booking, $this->paymentMethod);
            session()->flash('success', "Guest {$booking->guest_name} checked out successfully!");
            $this->closeCheckOutModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
