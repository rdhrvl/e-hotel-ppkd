<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Settings')]
class Index extends Component
{
    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.settings.index');
    }
}
