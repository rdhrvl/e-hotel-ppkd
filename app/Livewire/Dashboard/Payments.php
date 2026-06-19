<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Payment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Payment Transactions')]
class Payments extends Component
{
    public string $search = '';

    public function render(): \Illuminate\Contracts\View\View
    {
        $branchId = session('selected_branch_id', 1);

        $payments = Payment::with(['booking.guest', 'booking.room'])
            ->whereHas('booking.room', function ($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->when($this->search, function ($query) {
                $query->whereHas('booking.guest', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhere('method', 'like', '%' . $this->search . '%')
                  ->orWhere('status', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.dashboard.payments', [
            'payments' => $payments,
        ]);
    }
}
