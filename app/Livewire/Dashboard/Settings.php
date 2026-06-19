<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('System Settings')]
class Settings extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard.settings');
    }
}
