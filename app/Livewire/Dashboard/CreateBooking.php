<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Room;
use App\Services\BookingService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Support\Carbon;

#[Layout('layouts.app')]
#[Title('Book a Room')]
class CreateBooking extends Component
{
    // Section 1: Room Details
    public ?int $roomId = null;
    public ?Room $room = null;
    public int $noOfRoom = 1;
    public int $noOfPerson = 1;
    public string $receptionist = '';
    public string $additionalNotes = '';

    // Section 2: Guest Information
    public string $guestName = '';
    public string $guestProfession = '';
    public string $guestCompany = '';
    public string $guestNationality = 'Indonesian';
    public string $guestKtp = '';
    public string $guestBirthDate = '';

    // Section 3: Contact Details
    public string $guestAddress = '';
    public string $guestPhone = '';
    public string $guestEmail = '';
    public string $guestMemberNo = '';

    // Section 4: Stay Dates
    public string $arrivalTime = '';
    public string $arrivalDate = '';
    public string $departureDate = '';

    // Section 5: Safety Deposit Box
    public string $boxNo = '';
    public string $boxIssuedBy = '';
    public string $boxDate = '';

    protected $queryString = ['roomId' => ['as' => 'room_id']];

    public function mount(): void
    {
        if (!$this->roomId) {
            $this->redirect(route('room-availability'));
            return;
        }

        $this->room = Room::with('roomType')->find($this->roomId);
        if (!$this->room || !$this->room->isAvailable()) {
            $this->redirect(route('room-availability'));
            return;
        }

        $this->receptionist = auth()->user()->name;
        $this->boxIssuedBy = auth()->user()->name;
        $this->arrivalDate = now()->format('Y-m-d');
        $this->departureDate = now()->addDay()->format('Y-m-d');
        $this->arrivalTime = now()->format('H:i');
        $this->boxDate = now()->format('Y-m-d');
    }

    public function confirmBooking(BookingService $bookingService)
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'arrivalDate' => 'required|date|after_or_equal:today',
            'departureDate' => 'required|date|after:arrivalDate',
            'guestKtp' => 'required|string|max:50',
            'guestPhone' => 'required|string|max:20',
            'noOfPerson' => 'required|integer|min:1',
        ]);

        try {
            // Check room availability
            if (!$bookingService->isRoomAvailable($this->roomId, $this->arrivalDate, $this->departureDate)) {
                $this->addError('roomId', 'The selected room is not available for these dates.');
                return;
            }

            // Create booking using existing service
            $booking = $bookingService->createBooking([
                'guest_name' => $this->guestName,
                'guest_id' => $this->guestKtp,
                'guest_phone' => $this->guestPhone,
                'guest_email' => $this->guestEmail,
                'guest_address' => $this->guestAddress,
                'room_id' => $this->roomId,
                'check_in_date' => $this->arrivalDate,
                'check_out_date' => $this->departureDate,
                'number_of_guests' => $this->noOfPerson,
            ]);

            // Save notes/time details to room notes if applicable
            $notesText = 'Arrival Time: ' . $this->arrivalTime . '. Profession: ' . $this->guestProfession . '. Member No: ' . $this->guestMemberNo;
            if ($this->additionalNotes) {
                $notesText .= '. Notes: ' . $this->additionalNotes;
            }
            if ($this->boxNo) {
                $notesText .= '. Safety Box: ' . $this->boxNo;
            }

            $booking->room->update([
                'notes' => $notesText
            ]);

            session()->flash('success', 'Booking for Room ' . $this->room->room_number . ' confirmed successfully!');
            return redirect()->route('room-availability');
        } catch (\Exception $e) {
            $this->addError('roomId', $e->getMessage());
        }
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard.create-booking');
    }
}
