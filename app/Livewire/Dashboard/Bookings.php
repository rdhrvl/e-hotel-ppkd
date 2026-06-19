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

    protected $listeners = ['branchChanged' => '$refresh'];

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        $bookings = Booking::with(['room.roomType', 'guestBill', 'guest'])
            ->whereHas('room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('guest', function ($g) {
                        $g->where('name', 'like', '%' . $this->search . '%')
                          ->orWhere('identity_number', 'like', '%' . $this->search . '%');
                    });
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
            $booking->room->update(['status' => 'available']);
            session()->flash('success', "Booking for {$booking->guest->name} cancelled successfully.");
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
            session()->flash('success', "Guest {$booking->guest->name} checked in successfully!");
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
            session()->flash('success', "Guest {$booking->guest->name} checked out successfully!");
            $this->closeCheckOutModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
