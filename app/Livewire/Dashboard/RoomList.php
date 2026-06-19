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
#[Title('Room Availability')]
class RoomList extends Component
{
    // Filters
    public string $filterType = '';
    public string $filterStatus = '';
    public string $filterBedType = '';
    public string $filterBreakfast = '';

    // Selected Room Details
    public ?int $selectedRoomId = null;

    // Booking form fields (used inline in the sidebar panel)
    public string $guestName = '';
    public string $guestId = '';
    public string $guestPhone = '';
    public string $guestEmail = '';
    public string $guestAddress = '';
    public int $numberGuests = 1;
    public string $checkInDate = '';
    public string $checkOutDate = '';
    public array $bookingServices = []; // Selected extra service IDs

    // Check In form fields
    public float $depositAmount = 50000;

    // Check Out form fields
    public string $paymentMethod = 'cash';

    // Add Service form fields
    public ?int $selectedServiceId = null;
    public int $serviceQuantity = 1;
    public string $serviceNotes = '';

    // Active IDs
    public ?int $bookingIdToCheckIn = null;
    public ?int $bookingIdToCheckOut = null;
    public ?int $bookingIdToAddService = null;

    protected $listeners = ['branchChanged' => '$refresh'];

    public function mount(): void
    {
        // Default check-in/out to today and tomorrow
        $this->checkInDate = now()->format('Y-m-d');
        $this->checkOutDate = now()->addDay()->format('Y-m-d');
    }

    public function selectRoom(int $roomId): void
    {
        $this->selectedRoomId = $roomId;
        $room = Room::find($roomId);
        if ($room) {
            $booking = Booking::where('room_id', $roomId)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->first();

            if ($booking) {
                $this->bookingIdToCheckIn = $booking->id;
                $this->bookingIdToCheckOut = $booking->id;
                $this->bookingIdToAddService = $booking->id;
            } else {
                $this->bookingIdToCheckIn = null;
                $this->bookingIdToCheckOut = null;
                $this->bookingIdToAddService = null;
            }
            
            // Reset fields
            $this->guestName = '';
            $this->guestId = '';
            $this->guestPhone = '';
            $this->guestEmail = '';
            $this->guestAddress = '';
            $this->numberGuests = 1;
            $this->bookingServices = [];
            $this->depositAmount = 50000;
            $this->paymentMethod = 'cash';
            $this->selectedServiceId = Service::first()?->id;
            $this->serviceQuantity = 1;
            $this->serviceNotes = '';
            $this->resetErrorBag();
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        $rooms = Room::with(['roomType', 'activeBooking.guest'])
            ->where('branch_id', $branchId)
            ->when($this->filterType, fn($q) => $q->where('room_type_id', $this->filterType))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterBedType, function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('bed_type', $this->filterBedType));
            })
            ->when($this->filterBreakfast !== '', function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('has_breakfast', $this->filterBreakfast === '1'));
            })
            ->orderBy('room_number')
            ->get();

        $roomTypes = RoomType::all();
        $services = Service::all();

        $selectedRoom = $this->selectedRoomId ? Room::with(['roomType', 'activeBooking.guest'])->find($this->selectedRoomId) : null;
        $checkInBooking = $this->bookingIdToCheckIn ? Booking::with('guest')->find($this->bookingIdToCheckIn) : null;
        $checkOutBooking = $this->bookingIdToCheckOut ? Booking::with(['guestBill', 'bookingItems.service', 'guest'])->find($this->bookingIdToCheckOut) : null;

        return view('livewire.dashboard.room-list', [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'services' => $services,
            'selectedRoom' => $selectedRoom,
            'checkInBooking' => $checkInBooking,
            'checkOutBooking' => $checkOutBooking,
        ]);
    }

    // Status Toggle
    public function updateCleaningStatus(int $roomId, string $status): void
    {
        $room = Room::findOrFail($roomId);
        $room->update(['status' => $status]);
        session()->flash('success', "Room {$room->room_number} status updated to {$status}.");
        $this->selectRoom($roomId);
    }

    // BOOKING OPERATIONS
    public function bookRoom(BookingService $bookingService): void
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'guestId' => 'required|string|max:50',
            'guestPhone' => 'nullable|string|max:20',
            'guestEmail' => 'nullable|email|max:255',
            'guestAddress' => 'nullable|string',
            'numberGuests' => 'required|integer|min:1|max:10',
            'checkInDate' => 'required|date|after_or_equal:today',
            'checkOutDate' => 'required|date|after:checkInDate',
        ]);

        try {
            $booking = $bookingService->createBooking([
                'guest_name' => $this->guestName,
                'guest_id' => $this->guestId,
                'guest_phone' => $this->guestPhone,
                'guest_email' => $this->guestEmail,
                'guest_address' => $this->guestAddress,
                'room_id' => $this->selectedRoomId,
                'check_in_date' => $this->checkInDate,
                'check_out_date' => $this->checkOutDate,
                'number_of_guests' => $this->numberGuests,
            ]);

            foreach ($this->bookingServices as $serviceId) {
                $service = Service::find($serviceId);
                if ($service) {
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
            $this->selectRoom((int) $this->selectedRoomId);
        } catch (\Exception $e) {
            $this->addError('checkInDate', $e->getMessage());
        }
    }

    // CHECK-IN OPERATIONS
    public function checkInGuest(BookingService $bookingService): void
    {
        $this->validate([
            'depositAmount' => 'required|numeric|min:0',
        ]);

        $booking = Booking::findOrFail($this->bookingIdToCheckIn);

        try {
            $bookingService->checkIn($booking, (float) $this->depositAmount);
            session()->flash('success', "Guest {$booking->guest->name} checked in successfully!");
            $this->selectRoom((int) $this->selectedRoomId);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    // CHECK-OUT OPERATIONS
    public function checkOutGuest(BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($this->bookingIdToCheckOut);

        try {
            $bookingService->checkOut($booking, $this->paymentMethod);
            session()->flash('success', "Guest {$booking->guest->name} checked out successfully!");
            $this->selectRoom((int) $this->selectedRoomId);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    // EXTRA SERVICE CHARGES OPERATIONS
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
            $this->selectRoom((int) $this->selectedRoomId);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
