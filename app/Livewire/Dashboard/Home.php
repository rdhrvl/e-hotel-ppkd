<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\DeliveryNote;
use App\Models\UserNotification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Home extends Component
{
    /**
     * Get the count of active delivery notes for the current user.
     */
    #[Computed]
    public function activeDnCount(): int
    {
        return DeliveryNote::forUser(auth()->id())->active()->count();
    }

    /**
     * Get the count of completed delivery notes for the current user.
     */
    #[Computed]
    public function completedDnCount(): int
    {
        return DeliveryNote::forUser(auth()->id())->completed()->count();
    }

    /**
     * Get the count of unread notifications for the current user.
     */
    #[Computed]
    public function unreadNotifications(): int
    {
        return UserNotification::where('user_id', auth()->id())
            ->unread()
            ->count();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard.home');
    }
}
