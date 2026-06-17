<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Service;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Room Status Board')]
class RoomList extends Component
{
    // Filters
    public string $filterType = '';
    public string $filterStatus = '';
    public string $filterCleaning = '';

    // Booking Modal
    public bool $showBookingModal = false;
    public ?int $selectedRoomId = null;
    public string $guestName = '';
    public string $guestId = '';
    public int $numberGuests = 1;
    public string $checkInDate = '';
    public string $checkOutDate = '';
    public array $bookingServices = []; // Selected extra service IDs

    // Check In Modal
    public bool $showCheckInModal = false;
    public ?int $bookingIdToCheckIn = null;
    public float $depositAmount = 0;

    // Check Out Modal
    public bool $showCheckOutModal = false;
    public ?int $bookingIdToCheckOut = null;
    public string $paymentMethod = 'cash';

    // Add Service Charge Modal
    public bool $showAddServiceModal = false;
    public ?int $bookingIdToAddService = null;
    public ?int $selectedServiceId = null;
    public int $serviceQuantity = 1;
    public string $serviceNotes = '';

    public function mount(): void
    {
        // Default check-in/out to today and tomorrow
        $this->checkInDate = now()->format('Y-m-d');
        $this->checkOutDate = now()->addDay()->format('Y-m-d');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $rooms = Room::with('roomType')
            ->when($this->filterType, fn($q) => $q->where('room_type_id', $this->filterType))
            ->when($this->filterStatus, fn($q) => $q->where('booking_status', $this->filterStatus))
            ->when($this->filterCleaning, fn($q) => $q->where('cleaning_status', $this->filterCleaning))
            ->orderBy('room_number')
            ->get();

        $roomTypes = RoomType::all();
        $services = Service::all();

        // Load active booking details if modal is open
        $checkInBooking = $this->bookingIdToCheckIn ? Booking::with('room.roomType')->find($this->bookingIdToCheckIn) : null;
        $checkOutBooking = $this->bookingIdToCheckOut ? Booking::with(['room.roomType', 'guestBill', 'bookingItems.service'])->find($this->bookingIdToCheckOut) : null;
        $addServiceBooking = $this->bookingIdToAddService ? Booking::find($this->bookingIdToAddService) : null;

        return view('livewire.dashboard.room-list', [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'services' => $services,
            'checkInBooking' => $checkInBooking,
            'checkOutBooking' => $checkOutBooking,
            'addServiceBooking' => $addServiceBooking,
        ]);
    }

    // Housekeeping Status Toggle
    public function updateCleaningStatus(int $roomId, string $status): void
    {
        $room = Room::findOrFail($roomId);
        $room->update(['cleaning_status' => $status]);
        session()->flash('success', "Room {$room->room_number} status updated to {$status}.");
    }

    // BOOKING OPERATIONS
    public function openBookingModal(int $roomId): void
    {
        $this->selectedRoomId = $roomId;
        $this->guestName = '';
        $this->guestId = '';
        $this->numberGuests = 1;
        $this->bookingServices = [];
        $this->showBookingModal = true;
    }

    public function closeBookingModal(): void
    {
        $this->showBookingModal = false;
        $this->resetErrorBag();
    }

    public function bookRoom(BookingService $bookingService): void
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'guestId' => 'required|string|max:50',
            'numberGuests' => 'required|integer|min:1|max:10',
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
        ]);

        try {
            $booking = $bookingService->createBooking([
                'guest_name' => $this->guestName,
                'guest_id' => $this->guestId,
                'room_id' => $this->selectedRoomId,
                'check_in_date' => $this->checkInDate,
                'check_out_date' => $this->checkOutDate,
                'number_of_guests' => $this->numberGuests,
            ]);

            // Add initial services selected in booking
            foreach ($this->bookingServices as $serviceId) {
                // Since this is booked (pre-arrival), we temporarily add it to bill when checking in,
                // but the seeder handles it. In actual system, we associate services to bookings.
                // We'll charge services via addServiceCharge later when guest checks in.
                $service = Service::find($serviceId);
                if ($service) {
                    // We can charge standard services immediately if checked in, but since booking is "confirmed" (pre-arrival),
                    // they will be loaded as items when check-in happens, or we can add them to the bill now.
                    // Let's add them to the bill's extra charges!
                    $bill = $booking->guestBill;
                    if ($bill) {
                        \App\Models\BookingItem::create([
                            'booking_id' => $booking->id,
                            'service_id' => $service->id,
                            'quantity' => 1,
                            'price' => $service->price,
                        ]);
                        $bill->increment('total_extra_charges', $service->price);
                    }
                }
            }

            session()->flash('success', 'Room booked successfully!');
            $this->closeBookingModal();
        } catch (\Exception $e) {
            $this->addError('checkInDate', $e->getMessage());
        }
    }

    // CHECK-IN OPERATIONS
    public function openCheckInModal(int $roomId): void
    {
        $room = Room::findOrFail($roomId);
        // Find the active confirmed booking for this room
        $booking = Booking::where('room_id', $roomId)
            ->where('status', 'confirmed')
            ->first();

        if ($booking) {
            $this->bookingIdToCheckIn = $booking->id;
            $this->depositAmount = 50000; // Default deposit
            $this->showCheckInModal = true;
        } else {
            session()->flash('error', 'No confirmed booking found for this room. Please reserve first.');
        }
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
    public function openCheckOutModal(int $roomId): void
    {
        $booking = Booking::where('room_id', $roomId)
            ->where('status', 'checked_in')
            ->first();

        if ($booking) {
            $this->bookingIdToCheckOut = $booking->id;
            $this->paymentMethod = 'cash';
            $this->showCheckOutModal = true;
        } else {
            session()->flash('error', 'No checked-in guest found in this room.');
        }
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

    // EXTRA SERVICE CHARGES OPERATIONS
    public function openAddServiceModal(int $roomId): void
    {
        $booking = Booking::where('room_id', $roomId)
            ->where('status', 'checked_in')
            ->first();

        if ($booking) {
            $this->bookingIdToAddService = $booking->id;
            $this->selectedServiceId = Service::first()?->id;
            $this->serviceQuantity = 1;
            $this->serviceNotes = '';
            $this->showAddServiceModal = true;
        } else {
            session()->flash('error', 'Only active guests can receive additional service charges.');
        }
    }

    public function closeAddServiceModal(): void
    {
        $this->showAddServiceModal = false;
        $this->bookingIdToAddService = null;
        $this->resetErrorBag();
    }

    public function addServiceCharge(BookingService $bookingService): void
    {
        $this->validate([
            'selectedServiceId' => 'required|exists:services,id',
            'serviceQuantity' => 'required|integer|min:1',
            'serviceNotes' => 'nullable|string|max:255',
        ]);

        $booking = Booking::findOrFail($this->bookingIdToAddService);

        try {
            $bookingService->addServiceCharge(
                $booking,
                (int) $this->selectedServiceId,
                (int) $this->serviceQuantity,
                $this->serviceNotes
            );
            session()->flash('success', 'Service charge added to bill.');
            $this->closeAddServiceModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
