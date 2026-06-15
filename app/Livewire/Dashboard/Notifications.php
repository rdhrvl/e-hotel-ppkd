<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\UserNotification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Notifications')]
class Notifications extends Component
{
    use WithPagination;

    #[Url]
    public string $filterType = 'all';

    /**
     * Reset pagination when filter type changes.
     */
    public function updatedFilterType(): void
    {
        $this->resetPage();
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(int $id): void
    {
        $notification = UserNotification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->update(['is_read' => true]);

        session()->flash('success', 'Notification marked as read.');
    }

    /**
     * Mark all unread notifications as read for the current user.
     */
    public function markAllAsRead(): void
    {
        UserNotification::where('user_id', auth()->id())
            ->unread()
            ->update(['is_read' => true]);

        session()->flash('success', 'All notifications marked as read.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $notifications = UserNotification::where('user_id', auth()->id())
            ->when($this->filterType !== 'all', function ($query) {
                $query->where('type', $this->filterType);
            })
            ->latest()
            ->paginate(15);

        return view('livewire.dashboard.notifications', [
            'notifications' => $notifications,
        ]);
    }
}
