<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Guest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Guests Directory')]
class Guests extends Component
{
    use WithPagination;

    public string $search = '';

    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $guestsQuery = Guest::withCount('bookings')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('identity_number', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $guests = $guestsQuery->paginate(10);

        if ($this->getPage() > $guests->lastPage()) {
            $this->setPage(max(1, $guests->lastPage()));
            $guests = $guestsQuery->paginate(10);
        }

        return view('livewire.dashboard.guests', [
            'guests' => $guests,
        ]);
    }
}
