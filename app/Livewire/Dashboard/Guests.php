<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Guest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Guests Directory')]
class Guests extends Component
{
    public string $search = '';

    public function render(): \Illuminate\Contracts\View\View
    {
        $guests = Guest::withCount('bookings')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('identity_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard.guests', [
            'guests' => $guests,
        ]);
    }
}
