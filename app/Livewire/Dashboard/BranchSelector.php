<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Branch;
use Livewire\Component;

class BranchSelector extends Component
{
    public $branches;
    public $selectedBranchId;

    public function mount()
    {
        $this->branches = Branch::all();
        
        // Default to first branch if none selected
        if (!session()->has('selected_branch_id') && $this->branches->count() > 0) {
            session(['selected_branch_id' => $this->branches->first()->id]);
        }
        
        $this->selectedBranchId = session('selected_branch_id');
    }

    public function updatedSelectedBranchId($value)
    {
        session(['selected_branch_id' => (int) $value]);
        $this->dispatch('branchChanged');
        $this->redirect(request()->header('Referer') ?? route('dashboard'));
    }

    public function render()
    {
        return view('livewire.dashboard.branch-selector');
    }
}
