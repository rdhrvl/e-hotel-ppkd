<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Reservation Registry')]
class Bookings extends Component
{
    use WithPagination;

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

    // Inspection & Damage Settlement
    public bool $confirmInspection = false;
    public string $damageDescription = '';
    public float $damageAmount = 0.00;

    // FD Housekeeping Report Review
    public bool $showReviewModal = false;
    public ?int $bookingIdForReview = null;

    // F&B Ordering
    public bool $showOrderFoodModal = false;
    public ?int $bookingIdForOrder = null;
    public array $orderItems = []; // service_id => quantity
    public ?string $selectedFoodCategory = null;

    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';

    protected $listeners = ['branchChanged' => '$refresh'];

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function mount(): void
    {
        $checkoutId = request()->query('checkout_booking_id');
        if ($checkoutId) {
            $this->openCheckOutModal((int) $checkoutId);
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        $bookingsQuery = Booking::with(['room.roomType', 'guestBill', 'guest'])
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
            ->orderBy($this->sortField, $this->sortDirection);

        $bookings = $bookingsQuery->paginate(10);

        if ($this->getPage() > $bookings->lastPage()) {
            $this->setPage(max(1, $bookings->lastPage()));
            $bookings = $bookingsQuery->paginate(10);
        }

        $checkInBooking = $this->bookingIdToCheckIn ? Booking::with('room.roomType')->find($this->bookingIdToCheckIn) : null;
        $checkOutBooking = $this->bookingIdToCheckOut ? Booking::with(['room.roomType', 'guestBill', 'bookingItems.service', 'payments'])->find($this->bookingIdToCheckOut) : null;

        $latestTask = $checkOutBooking ? \App\Models\HousekeepingTask::with('staff')->where('room_id', $checkOutBooking->room_id)->where('created_at', '>=', $checkOutBooking->created_at)->latest()->first() : null;
        $roomIssues = $checkOutBooking ? \App\Models\Notification::where('room_id', $checkOutBooking->room_id)->where('priority', 'high')->where('created_at', '>=', $checkOutBooking->created_at)->get() : collect();

        $reviewBooking = $this->bookingIdForReview ? Booking::with(['room.roomType', 'guestBill', 'bookingItems.service', 'payments'])->find($this->bookingIdForReview) : null;
        $reviewTask = $reviewBooking ? \App\Models\HousekeepingTask::with('staff')->where('room_id', $reviewBooking->room_id)->where('created_at', '>=', $reviewBooking->created_at)->latest()->first() : null;
        $reviewIssues = $reviewBooking ? \App\Models\Notification::where('room_id', $reviewBooking->room_id)->where('priority', 'high')->where('created_at', '>=', $reviewBooking->created_at)->get() : collect();

        $fnbServices = \App\Models\Service::where('type', 'f_and_b')
            ->where('is_active', true)
            ->get();
        $orderBooking = $this->bookingIdForOrder ? Booking::with(['room.roomType', 'guest'])->find($this->bookingIdForOrder) : null;

        return view('livewire.dashboard.bookings', [
            'bookings' => $bookings,
            'checkInBooking' => $checkInBooking,
            'checkOutBooking' => $checkOutBooking,
            'latestTask' => $latestTask,
            'roomIssues' => $roomIssues,
            'reviewBooking' => $reviewBooking,
            'reviewTask' => $reviewTask,
            'reviewIssues' => $reviewIssues,
            'fnbServices' => $fnbServices,
            'orderBooking' => $orderBooking,
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

        if ($booking->room->status !== 'ready') {
            session()->flash('error', 'The room must be marked as Ready for Check-In by Housekeeping before you can check in the guest.');
            return;
        }

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
        
        $this->confirmInspection = false;
        $this->damageDescription = '';
        $this->damageAmount = 0.00;
        
        $this->showCheckOutModal = true;
    }

    public function closeCheckOutModal(): void
    {
        $this->showCheckOutModal = false;
        $this->bookingIdToCheckOut = null;
    }

    public function applyDamageCharge(): void
    {
        $this->validate([
            'damageDescription' => 'required|string|max:255',
            'damageAmount' => 'required|numeric|min:0.01',
            'bookingIdToCheckOut' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::findOrFail($this->bookingIdToCheckOut);

        \DB::transaction(function () use ($booking) {
            $service = \App\Models\Service::firstOrCreate(
                ['name' => 'Damage / Missing Fine'],
                ['price' => 0.00, 'type' => 'general']
            );

            \App\Models\BookingItem::create([
                'booking_id' => $booking->id,
                'service_id' => $service->id,
                'quantity' => 1,
                'price' => $this->damageAmount,
                'notes' => $this->damageDescription,
            ]);

            $bill = $booking->guestBill;
            if ($bill) {
                $bill->increment('total_extra_charges', $this->damageAmount);
            }
        });

        $this->damageDescription = '';
        $this->damageAmount = 0.00;
        session()->flash('success', 'Extra charge added to settlement.');
    }

    public function requestPreCheckInInspection(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $room = $booking->room;

        // Create housekeeping task
        $staff = \App\Models\User::whereHas('role', function ($q) {
            $q->where('slug', 'housekeeping');
        })->first();

        if (!$staff) {
            $staff = auth()->user() ?? \App\Models\User::first();
        }

        \App\Models\HousekeepingTask::create([
            'room_id' => $room->id,
            'staff_id' => $staff->id,
            'schedule_date' => \Illuminate\Support\Carbon::now()->toDateString(),
            'status' => 'in_progress',
        ]);

        // Update the room status to cleaning
        $room->update(['status' => 'cleaning']);

        // Dispatch alert to Housekeeping
        app(\App\Services\NotificationService::class)->dispatchCustomAlert(
            $room,
            auth()->user() ?? \App\Models\User::first(),
            "Room {$room->room_number} pre-check-in readiness inspection requested.",
            'medium',
            true
        );

        session()->flash('success', 'Pre-check-in room readiness inspection requested.');
    }

    public function checkOutGuest(BookingService $bookingService): void
    {
        $booking = Booking::findOrFail($this->bookingIdToCheckOut);

        try {
            $bookingService->checkOut($booking, $this->paymentMethod);
            session()->flash('success', "Guest {$booking->guest->name} checked out successfully! Post-checkout cleaning and inspection task assigned to housekeeping.");
            $this->closeCheckOutModal();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function openReviewModal(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->bookingIdForReview = $booking->id;
        $this->bookingIdToCheckOut = $booking->id; // for applyDamageCharge compatibility
        $this->damageDescription = '';
        $this->damageAmount = 0.00;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->bookingIdForReview = null;
        $this->bookingIdToCheckOut = null;
    }

    public function releaseRoom(string $targetStatus): void
    {
        $booking = Booking::findOrFail($this->bookingIdForReview);
        $room = $booking->room;

        $room->update([
            'status' => $targetStatus,
        ]);

        session()->flash('success', "Room {$room->room_number} released successfully as " . ucfirst($targetStatus) . ".");
        $this->closeReviewModal();
    }

    public function openOrderFoodModal(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->bookingIdForOrder = $booking->id;
        
        // Reset quantities of all food items
        $this->orderItems = [];
        $this->selectedFoodCategory = null;
        $fnbServices = \App\Models\Service::where('type', 'f_and_b')
            ->where('is_active', true)
            ->get();
        foreach ($fnbServices as $service) {
            $this->orderItems[$service->id] = 0;
        }

        $this->showOrderFoodModal = true;
    }

    public function closeOrderFoodModal(): void
    {
        $this->showOrderFoodModal = false;
        $this->bookingIdForOrder = null;
        $this->orderItems = [];
        $this->selectedFoodCategory = null;
    }

    public function updateFoodQuantity(int $serviceId, int $change): void
    {
        $current = $this->orderItems[$serviceId] ?? 0;
        $newQty = max(0, $current + $change);
        $this->orderItems[$serviceId] = $newQty;
    }

    public function confirmFoodOrder(BookingService $bookingService, \App\Services\NotificationService $notificationService): void
    {
        $booking = Booking::findOrFail($this->bookingIdForOrder);
        
        // Filter items with quantity > 0
        $itemsToOrder = array_filter($this->orderItems, fn($qty) => $qty > 0);

        if (empty($itemsToOrder)) {
            session()->flash('error', 'Please select at least one menu item.');
            return;
        }

        \DB::transaction(function () use ($booking, $itemsToOrder, $bookingService, $notificationService) {
            $totalPrice = 0.0;
            $orderItemsData = [];
            $orderedNames = [];

            foreach ($itemsToOrder as $serviceId => $qty) {
                $service = \App\Models\Service::findOrFail($serviceId);
                $subtotal = (float)$service->price * $qty;
                $totalPrice += $subtotal;

                $orderItemsData[] = [
                    'service_id' => $serviceId,
                    'quantity' => $qty,
                    'price' => $service->price,
                ];

                $orderedNames[] = "{$qty}x {$service->name}";
            }

            // 1. Create FoodOrder
            $order = \App\Models\FoodOrder::create([
                'booking_id' => $booking->id,
                'status' => 'processed',
                'total_price' => $totalPrice,
            ]);

            // 2. Create FoodOrderItems and Service Charges
            foreach ($orderItemsData as $itemData) {
                $order->items()->create($itemData);
                
                // Add service charge to guest bill
                $bookingService->addServiceCharge(
                    $booking,
                    (int) $itemData['service_id'],
                    (int) $itemData['quantity'],
                    "Room Service Order #{$order->id}"
                );
            }

            // 3. Dispatch Notification to FnB
            $itemsSummary = implode(', ', $orderedNames);
            $message = "New food order placed for Room {$booking->room->room_number} (Order #{$order->id}): {$itemsSummary}";
            $notificationService->dispatchFoodOrderAlert($booking, $message, 'fnb', 'medium', false);
        });

        session()->flash('success', 'Food order successfully placed and added to guest bill!');
        $this->closeOrderFoodModal();
    }
}
