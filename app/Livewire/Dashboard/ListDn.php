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
#[Title('Active Delivery Notes')]
class ListDn extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $statusFilter = 'all';

    /**
     * Reset pagination when search or filter changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Reset pagination when status filter changes.
     */
    public function updatedStatusFilter(): void
    {
        $this->resetPage();
    }

    /**
     * Set delivery note status to in_transit.
     */
    public function startTransit(int $id): void
    {
        $dn = DeliveryNote::forUser(auth()->id())->findOrFail($id);
        $dn->update(['status' => 'in_transit']);

        \App\Services\NotificationService::deliveryCheckpoint(auth()->user(), $dn->dn_number, 'In Transit');

        session()->flash('success', "Delivery note {$dn->dn_number} is now in transit.");
    }

    /**
     * Set delivery note status to completed.
     */
    public function completeTransit(int $id): void
    {
        $dn = DeliveryNote::forUser(auth()->id())->findOrFail($id);
        $dn->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        \App\Services\NotificationService::deliveryCheckpoint(auth()->user(), $dn->dn_number, 'Completed');

        session()->flash('success', "Delivery note {$dn->dn_number} has been completed successfully.");
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $deliveryNotes = DeliveryNote::forUser(auth()->id())
            ->active()
            ->when($this->search !== '', function ($query) {
                $query->where(function ($q) {
                    $q->where('dn_number', 'like', "%{$this->search}%")
                        ->orWhere('origin', 'like', "%{$this->search}%")
                        ->orWhere('destination', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard.list-dn', [
            'deliveryNotes' => $deliveryNotes,
        ]);
    }
}
