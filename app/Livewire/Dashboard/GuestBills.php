<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Booking;
use App\Models\GuestBill;
use App\Models\Service;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Billing & Invoicing')]
class GuestBills extends Component
{
    public string $search = '';
    
    // Add service charge form states
    public ?int $selectedBookingId = null;
    public ?int $selectedServiceId = null;
    public int $serviceQuantity = 1;
    public string $serviceNotes = '';

    public function render(): \Illuminate\Contracts\View\View
    {
        // Load active or checked out bookings with their bills
        $bookings = Booking::with(['room.roomType', 'guestBill', 'bookingItems.service'])
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('guest_name', 'like', '%' . $this->search . '%')
                      ->orWhere('guest_id', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('status', 'asc') // Active checked_in first
            ->orderBy('updated_at', 'desc')
            ->get();

        $services = Service::all();

        return view('livewire.dashboard.guest-bills', [
            'bookings' => $bookings,
            'services' => $services,
        ]);
    }

    public function selectBookingForService(int $bookingId): void
    {
        $this->selectedBookingId = $bookingId;
        $this->selectedServiceId = Service::first()?->id;
        $this->serviceQuantity = 1;
        $this->serviceNotes = '';
    }

    public function addServiceCharge(BookingService $bookingService): void
    {
        $this->validate([
            'selectedBookingId' => 'required|exists:bookings,id',
            'selectedServiceId' => 'required|exists:services,id',
            'serviceQuantity' => 'required|integer|min:1',
            'serviceNotes' => 'nullable|string|max:255',
        ]);

        $booking = Booking::findOrFail($this->selectedBookingId);

        try {
            $bookingService->addServiceCharge(
                $booking,
                (int) $this->selectedServiceId,
                (int) $this->serviceQuantity,
                $this->serviceNotes
            );
            session()->flash('success', 'Extra service charge added to guest bill successfully.');
            $this->selectedBookingId = null; // Close form panel
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }
}
