<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Service Management')]
class Services extends Component
{
    // List
    public string $search = '';

    // Add Service
    public bool $showAddModal = false;
    public string $name = '';
    public float $price = 0;
    public string $type = 'general';

    // Edit Service
    public bool $showEditModal = false;
    public ?int $editingServiceId = null;
    public string $editName = '';
    public float $editPrice = 0;
    public string $editType = 'general';

    // Delete
    public bool $showDeleteModal = false;
    public ?int $deletingServiceId = null;
    public string $deletingServiceName = '';

    /** Available service types. */
    public array $serviceTypes = [
        'general'   => 'General',
        'extra_bed' => 'Extra Bed',
        'f_and_b'   => 'Food & Beverage',
        'laundry'   => 'Laundry',
    ];

    public function render(): \Illuminate\Contracts\View\View
    {
        if (! auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $services = Service::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard.services', [
            'services'     => $services,
            'serviceTypes' => $this->serviceTypes,
        ]);
    }

    // ── Add ─────────────────────────────────────────────────────────────

    public function openAddModal(): void
    {
        $this->reset(['name', 'price', 'type']);
        $this->type = 'general';
        $this->showAddModal = true;
        $this->resetErrorBag();
    }

    public function closeAddModal(): void
    {
        $this->showAddModal = false;
        $this->resetErrorBag();
    }

    public function createService(): void
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'type'  => 'required|in:general,extra_bed,f_and_b,laundry',
        ]);

        Service::create([
            'name'  => $this->name,
            'price' => $this->price,
            'type'  => $this->type,
        ]);

        session()->flash('success', "Service \"{$this->name}\" added.");
        $this->closeAddModal();
    }

    // ── Edit ─────────────────────────────────────────────────────────────

    public function openEditModal(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);
        $this->editingServiceId = $serviceId;
        $this->editName = $service->name;
        $this->editPrice = (float) $service->price;
        $this->editType = $service->type;
        $this->showEditModal = true;
        $this->resetErrorBag();
    }

    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->editingServiceId = null;
        $this->resetErrorBag();
    }

    public function updateService(): void
    {
        $this->validate([
            'editName'  => 'required|string|max:255',
            'editPrice' => 'required|numeric|min:0',
            'editType'  => 'required|in:general,extra_bed,f_and_b,laundry',
        ]);

        $service = Service::findOrFail($this->editingServiceId);
        $service->update([
            'name'  => $this->editName,
            'price' => $this->editPrice,
            'type'  => $this->editType,
        ]);

        session()->flash('success', "Service \"{$service->name}\" updated.");
        $this->closeEditModal();
    }

    // ── Delete ─────────────────────────────────────────────────────────────

    public function confirmDelete(int $serviceId): void
    {
        $service = Service::findOrFail($serviceId);
        $this->deletingServiceId = $serviceId;
        $this->deletingServiceName = $service->name;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal(): void
    {
        $this->showDeleteModal = false;
        $this->deletingServiceId = null;
    }

    public function deleteService(): void
    {
        $service = Service::findOrFail($this->deletingServiceId);
        $name = $service->name;
        $service->delete();

        session()->flash('success', "Service \"{$name}\" deleted.");
        $this->closeDeleteModal();
    }
}
