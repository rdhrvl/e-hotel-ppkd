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
    public int $page = 1;

    // Filters
    public string $filterType = '';
    public string $filterStatus = '';
    public string $filterBedType = '';
    public string $filterBreakfast = '';

    // Selected Room Details
    public ?int $selectedRoomId = null;

    // Cart details
    public array $cart = [];
    public array $modalExtras = [];
    public bool $isCartEditMode = false;

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
        $this->cart = session('cart', []);
    }

    public function selectRoom(int $roomId): void
    {
        $this->selectedRoomId = $roomId;
        $room = Room::with('roomType')->find($roomId);
        if ($room) {
            $this->cart = session('cart', []);
            $cartItem = collect($this->cart)->firstWhere('room_id', $roomId);
            if ($cartItem) {
                $this->isCartEditMode = true;
                $this->modalExtras = $cartItem['extras'] ?? [];
            } else {
                $this->isCartEditMode = false;
                $this->modalExtras = [];
            }

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

    public function updatingFilterType(): void
    {
        $this->page = 1;
    }

    public function updatingFilterStatus(): void
    {
        $this->page = 1;
    }

    public function updatingFilterBedType(): void
    {
        $this->page = 1;
    }

    public function updatingFilterBreakfast(): void
    {
        $this->page = 1;
    }

    public function gotoPage(int $page): void
    {
        $this->page = $page;
    }

    public function previousPage(): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage(int $totalPages): void
    {
        if ($this->page < $totalPages) {
            $this->page++;
        }
    }

    public function toggleStatusFilter(string $status): void
    {
        if ($this->filterStatus === $status) {
            $this->filterStatus = '';
        } else {
            $this->filterStatus = $status;
        }
        $this->page = 1;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        // Fetch counts for all rooms in the branch under current type/bed/breakfast filters (excluding status filter)
        $baseRoomsQuery = Room::where('branch_id', $branchId)
            ->when($this->filterType, fn($q) => $q->where('room_type_id', $this->filterType))
            ->when($this->filterBedType, function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('bed_type', $this->filterBedType));
            })
            ->when($this->filterBreakfast !== '', function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('has_breakfast', $this->filterBreakfast === '1'));
            });

        $totalRoomsCount = (clone $baseRoomsQuery)->count();
        $availableRoomsCount = (clone $baseRoomsQuery)->where('status', 'available')->count();
        $reservedRoomsCount = (clone $baseRoomsQuery)->where('status', 'reserved')->count();
        $occupiedRoomsCount = (clone $baseRoomsQuery)->where('status', 'occupied')->count();

        // Fetch distinct floors matching the criteria
        $allFloors = Room::where('branch_id', $branchId)
            ->when($this->filterType, fn($q) => $q->where('room_type_id', $this->filterType))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterBedType, function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('bed_type', $this->filterBedType));
            })
            ->when($this->filterBreakfast !== '', function($q) {
                $q->whereHas('roomType', fn($sub) => $sub->where('has_breakfast', $this->filterBreakfast === '1'));
            })
            ->select('floor')
            ->distinct()
            ->orderBy('floor')
            ->pluck('floor')
            ->values();

        $totalItems = $allFloors->count();
        $itemsPerPage = 2;
        $totalPages = (int) ceil($totalItems / $itemsPerPage);

        // Auto-correct current page if data changes
        if ($this->page > $totalPages) {
            $this->page = max(1, $totalPages);
        }

        // Get the floors for the current page
        $offset = ($this->page - 1) * $itemsPerPage;
        $floorNumbers = $allFloors->slice($offset, $itemsPerPage)->toArray();

        $rooms = Room::with(['roomType', 'activeBooking.guest', 'currentBooking.guest'])
            ->where('branch_id', $branchId)
            ->whereIn('floor', $floorNumbers)
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
        $services = Service::where('type', '!=', 'f_and_b')->get();

        $selectedRoom = $this->selectedRoomId ? Room::with(['roomType', 'activeBooking.guest', 'currentBooking.guest'])->find($this->selectedRoomId) : null;
        $checkInBooking = $this->bookingIdToCheckIn ? Booking::with('guest')->find($this->bookingIdToCheckIn) : null;
        $checkOutBooking = $this->bookingIdToCheckOut ? Booking::with(['guestBill', 'bookingItems.service', 'guest'])->find($this->bookingIdToCheckOut) : null;

        // Resolve cart items
        $this->cart = session('cart', []);
        $cartItems = [];
        $grandTotal = 0;
        foreach ($this->cart as $item) {
            $roomItem = Room::with('roomType')->find($item['room_id']);
            if ($roomItem) {
                $roomPrice = (float)($roomItem->price ?: $roomItem->roomType->base_price);
                $extrasList = [];
                $extrasCost = 0;
                foreach ($item['extras'] as $serviceId) {
                    $serviceItem = $services->firstWhere('id', $serviceId);
                    if ($serviceItem) {
                        $extrasList[] = $serviceItem;
                        $extrasCost += (float)$serviceItem->price;
                    }
                }
                $totalItemCost = $roomPrice + $extrasCost;
                $grandTotal += $totalItemCost;
                
                $cartItems[] = [
                    'room' => $roomItem,
                    'extras' => $extrasList,
                    'room_price' => $roomPrice,
                    'extras_cost' => $extrasCost,
                    'total_cost' => $totalItemCost,
                ];
            }
        }

        return view('livewire.dashboard.room-list', [
            'rooms' => $rooms,
            'roomTypes' => $roomTypes,
            'services' => $services,
            'selectedRoom' => $selectedRoom,
            'checkInBooking' => $checkInBooking,
            'checkOutBooking' => $checkOutBooking,
            'totalItems' => $totalItems,
            'itemsPerPage' => $itemsPerPage,
            'totalPages' => $totalPages,
            'currentPage' => $this->page,
            'totalRoomsCount' => $totalRoomsCount,
            'availableRoomsCount' => $availableRoomsCount,
            'reservedRoomsCount' => $reservedRoomsCount,
            'occupiedRoomsCount' => $occupiedRoomsCount,
            'cartItems' => $cartItems,
            'grandTotal' => $grandTotal,
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

    // CART OPERATIONS
    public function addToCart(): void
    {
        if (!$this->selectedRoomId) return;

        $room = Room::findOrFail($this->selectedRoomId);
        if (!$room->isAvailable()) {
            session()->flash('error', "Room {$room->room_number} is not available.");
            return;
        }

        $this->cart = session('cart', []);
        
        $exists = collect($this->cart)->contains('room_id', $this->selectedRoomId);
        if ($exists) {
            session()->flash('error', "Room {$room->room_number} is already in the cart.");
            return;
        }

        $this->cart[] = [
            'room_id' => $this->selectedRoomId,
            'extras' => $this->modalExtras,
        ];

        session(['cart' => $this->cart]);
        $this->selectedRoomId = null;
        session()->flash('success', "Room {$room->room_number} added to cart.");
    }

    public function updateCart(): void
    {
        if (!$this->selectedRoomId) return;

        $room = Room::findOrFail($this->selectedRoomId);
        $this->cart = session('cart', []);

        foreach ($this->cart as $key => $item) {
            if ($item['room_id'] == $this->selectedRoomId) {
                $this->cart[$key]['extras'] = $this->modalExtras;
                break;
            }
        }

        session(['cart' => $this->cart]);
        $this->selectedRoomId = null;
        session()->flash('success', "Room {$room->room_number} cart items updated.");
    }

    public function removeFromCart(?int $roomId = null): void
    {
        $idToRemove = $roomId ?: $this->selectedRoomId;
        if (!$idToRemove) return;

        $room = Room::find($idToRemove);
        $this->cart = session('cart', []);

        $this->cart = collect($this->cart)->reject(function ($item) use ($idToRemove) {
            return $item['room_id'] == $idToRemove;
        })->values()->toArray();

        session(['cart' => $this->cart]);
        
        if ($idToRemove == $this->selectedRoomId) {
            $this->selectedRoomId = null;
        }

        $roomNumber = $room ? $room->room_number : '';
        session()->flash('success', "Room {$roomNumber} removed from cart.");
    }

    public function clearCart(): void
    {
        session()->forget('cart');
        $this->cart = [];
        session()->flash('success', "Cart cleared.");
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
