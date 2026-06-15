<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\DeliveryNote;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Delivery History')]
class HistoryDn extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    /**
     * Reset pagination when search changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when date filters change.
     */
    public function updatedDateFrom(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when date filters change.
     */
    public function updatedDateTo(): void
    {
        $this->resetPage();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $deliveryNotes = DeliveryNote::forUser(auth()->id())
            ->completed()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('dn_number', 'like', "%{$this->search}%")
                        ->orWhere('origin', 'like', "%{$this->search}%")
                        ->orWhere('destination', 'like', "%{$this->search}%");
                });
            })
            ->when($this->dateFrom !== '', function ($query) {
                $query->whereDate('completed_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo !== '', function ($query) {
                $query->whereDate('completed_at', '<=', $this->dateTo);
            })
            ->latest('completed_at')
            ->paginate(10);

        return view('livewire.dashboard.history-dn', [
            'deliveryNotes' => $deliveryNotes,
        ]);
    }
}
