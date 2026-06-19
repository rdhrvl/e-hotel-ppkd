<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Food & Beverage Operations')]
class Fnb extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        // Restrict access to F&B, Admins, and Superadmins
        if (! auth()->user()->isFnb()) {
            abort(403, 'Unauthorized access to F&B Dashboard.');
        }

        // Get all Food & Beverage type services
        $menuItems = Service::where('type', 'f_and_b')->get();

        return view('livewire.dashboard.fnb', [
            'menuItems' => $menuItems,
        ]);
    }
}
