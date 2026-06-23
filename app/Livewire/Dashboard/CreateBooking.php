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

    public array $cartItems = [];

    // Upfront Payment Properties
    public bool $payUpfront = true;
    public string $paymentMethod = 'cash';

    protected $queryString = ['roomId' => ['as' => 'room_id']];

    public function mount(): void
    {
        $this->receptionist = auth()->user()->name;
        $this->boxIssuedBy = auth()->user()->name;
        $this->arrivalDate = now()->format('Y-m-d');
        $this->departureDate = now()->addDay()->format('Y-m-d');
        $this->arrivalTime = now()->format('H:i');
        $this->boxDate = now()->format('Y-m-d');

        if ($this->roomId) {
            $this->room = Room::with('roomType')->find($this->roomId);
            if (!$this->room || !$this->room->isAvailable()) {
                $this->redirect(route('room-availability'));
                return;
            }
            $this->cartItems = [
                [
                    'room_id' => $this->roomId,
                    'room' => $this->room,
                    'extras' => []
                ]
            ];
            $this->noOfRoom = 1;
        } else {
            $cart = session('cart', []);
            if (empty($cart)) {
                $this->redirect(route('room-availability'));
                return;
            }

            $allServices = \App\Models\Service::all();
            foreach ($cart as $item) {
                $roomObj = Room::with('roomType')->find($item['room_id']);
                if ($roomObj && $roomObj->isAvailable()) {
                    $extrasList = [];
                    foreach ($item['extras'] as $serviceId) {
                        $serviceItem = $allServices->firstWhere('id', $serviceId);
                        if ($serviceItem) {
                            $extrasList[] = $serviceItem;
                        }
                    }
                    $this->cartItems[] = [
                        'room_id' => $item['room_id'],
                        'room' => $roomObj,
                        'extras' => $extrasList
                    ];
                }
            }

            if (empty($this->cartItems)) {
                $this->redirect(route('room-availability'));
                return;
            }

            $this->noOfRoom = count($this->cartItems);
        }
    }

    public function confirmBooking(BookingService $bookingService)
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'arrivalDate' => 'required|date',
            'departureDate' => 'required|date|after:arrivalDate',
            'guestKtp' => 'required|string|max:50',
            'guestPhone' => 'required|string|max:20',
            'noOfPerson' => 'required|integer|min:1',
        ]);

        try {
            foreach ($this->cartItems as $item) {
                if (!$bookingService->isRoomAvailable($item['room_id'], $this->arrivalDate, $this->departureDate)) {
                    $this->addError('roomId', "Room {$item['room']->room_number} is not available for these dates.");
                    return;
                }
            }

            \DB::transaction(function () use ($bookingService) {
                foreach ($this->cartItems as $item) {
                    $booking = $bookingService->createBooking([
                        'guest_name' => $this->guestName,
                        'guest_id' => $this->guestKtp,
                        'guest_phone' => $this->guestPhone,
                        'guest_email' => $this->guestEmail,
                        'guest_address' => $this->guestAddress,
                        'guest_profession' => $this->guestProfession,
                        'guest_company' => $this->guestCompany,
                        'guest_nationality' => $this->guestNationality,
                        'guest_birth_date' => $this->guestBirthDate,
                        'guest_member_no' => $this->guestMemberNo,
                        'room_id' => $item['room_id'],
                        'check_in_date' => $this->arrivalDate,
                        'check_out_date' => $this->departureDate,
                        'number_of_guests' => $this->noOfPerson,
                        'arrival_time' => $this->arrivalTime,
                        'box_no' => $this->boxNo,
                        'box_issued_by' => $this->boxIssuedBy,
                        'box_date' => $this->boxDate,
                        'payment_method' => $this->paymentMethod,
                        'notes' => $this->additionalNotes,
                        'book_by' => $this->receptionist,
                    ]);

                    $bookingUpfrontAmount = 0.0;
                    if ($this->payUpfront) {
                        $nights = (int) Carbon::parse($this->arrivalDate)->diffInDays(Carbon::parse($this->departureDate));
                        $nights = max(1, $nights);
                        $roomCharges = (float)$booking->room->effective_price * $nights;
                        $extrasTotal = 0.0;
                        foreach ($item['extras'] as $extraService) {
                            $extrasTotal += (float)$extraService->price;
                        }
                        $bookingUpfrontAmount = $roomCharges + $extrasTotal;
                    }

                    foreach ($item['extras'] as $extraService) {
                        $bill = $booking->guestBill;
                        if ($bill) {
                            \App\Models\BookingItem::create([
                                'booking_id' => $booking->id,
                                'service_id' => $extraService->id,
                                'quantity' => 1,
                                'price' => $extraService->price,
                            ]);
                            $bill->increment('total_extra_charges', $extraService->price);
                        }
                    }

                    if ($this->payUpfront && $bookingUpfrontAmount > 0) {
                        $bill = $booking->guestBill;
                        if ($bill) {
                            $bill->update([
                                'deposit_amount' => $bookingUpfrontAmount,
                            ]);
                            \App\Models\Payment::create([
                                'booking_id' => $booking->id,
                                'amount' => $bookingUpfrontAmount,
                                'method' => $this->paymentMethod === 'bank_transfer' ? 'transfer' : $this->paymentMethod,
                                'status' => 'paid',
                            ]);
                        }
                    }

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
                }
            });

            session()->forget('cart');

            session()->flash('success', 'Bookings confirmed successfully!');
            return redirect()->route('bookings');
        } catch (\Exception $e) {
            $this->addError('roomId', $e->getMessage());
        }
    }

    public function calculateNights(): int
    {
        try {
            $nights = (int) Carbon::parse($this->arrivalDate)->diffInDays(Carbon::parse($this->departureDate));
            return max(1, $nights);
        } catch (\Exception $e) {
            return 1;
        }
    }

    public function calculateRoomsTotal(): float
    {
        $total = 0;
        $nights = $this->calculateNights();
        foreach ($this->cartItems as $item) {
            $total += (float)$item['room']->effective_price * $nights;
        }
        return $total;
    }

    public function calculateExtrasTotal(): float
    {
        $total = 0;
        foreach ($this->cartItems as $item) {
            foreach ($item['extras'] as $extra) {
                $total += (float)$extra->price;
            }
        }
        return $total;
    }

    public function calculateGrandTotal(): float
    {
        return $this->calculateRoomsTotal() + $this->calculateExtrasTotal();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard.create-booking');
    }
}
