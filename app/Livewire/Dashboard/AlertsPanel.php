<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Models\Notification;
use App\Models\NotificationReadStatus;
use Livewire\Component;

class AlertsPanel extends Component
{
    public array $notificationsList = [];
    public int $unreadCount = 0;
    public bool $showDrawer = false;
    public array $dispatchedAlertIds = [];

    public function mount(): void
    {
        $this->fetchNotifications(false);
    }

    public function toggleDrawer(): void
    {
        $this->showDrawer = !$this->showDrawer;
        if ($this->showDrawer) {
            $this->fetchNotifications(false);
        }
    }

    /**
     * Retrieve notifications targeted at this user's role
     */
    public function fetchNotifications(bool $triggerAudio = true): void
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return;
        }

        $userRole = $user->role->slug;

        // Fetch the latest 10 notifications for this user's role
        $notifications = Notification::with('room', 'actor')
            ->whereJsonContains('target_roles', $userRole)
            ->latest()
            ->limit(10)
            ->get();

        // Map notifications list and append read status
        $this->notificationsList = $notifications->map(function ($notif) use ($user, $triggerAudio) {
            $readStatus = NotificationReadStatus::where('notification_id', $notif->id)
                ->where('user_id', $user->id)
                ->first();

            $isRead = (bool) $readStatus;

            // If it is unread and has not been popped up yet, dispatch the alert
            if (!$isRead && !in_array($notif->id, $this->dispatchedAlertIds, true)) {
                $this->dispatchedAlertIds[] = $notif->id;
                
                $this->dispatch('new-alert-received', [
                    'id' => $notif->id,
                    'message' => $notif->message,
                    'priority' => $notif->priority,
                    'is_urgent' => $notif->is_urgent,
                    'url' => $notif->action_url,
                    'trigger_sound' => $triggerAudio,
                ]);
            }

            return [
                'id' => $notif->id,
                'message' => $notif->message,
                'priority' => $notif->priority,
                'is_urgent' => $notif->is_urgent,
                'action_url' => $notif->action_url,
                'room_number' => $notif->room ? $notif->room->room_number : null,
                'actor_name' => $notif->actor ? $notif->actor->name : 'System',
                'time_ago' => $notif->created_at ? $notif->created_at->diffForHumans() : '',
                'read_at' => $readStatus ? $readStatus->read_at : null,
            ];
        })->toArray();

        // Calculate unread count
        $this->unreadCount = Notification::whereJsonContains('target_roles', $userRole)
            ->whereDoesntHave('readStatuses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->count();
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead(string $notificationId): void
    {
        $user = auth()->user();
        if (!$user) {
            return;
        }

        NotificationReadStatus::updateOrCreate(
            ['notification_id' => $notificationId, 'user_id' => $user->id],
            ['read_at' => now()]
        );

        $this->fetchNotifications(false);
    }

    /**
     * Mark all notifications for this user as read
     */
    public function markAllAsRead(): void
    {
        $user = auth()->user();
        if (!$user || !$user->role) {
            return;
        }

        $userRole = $user->role->slug;
        $unreadNotifications = Notification::whereJsonContains('target_roles', $userRole)
            ->whereDoesntHave('readStatuses', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->get();

        foreach ($unreadNotifications as $notif) {
            NotificationReadStatus::updateOrCreate(
                ['notification_id' => $notif->id, 'user_id' => $user->id],
                ['read_at' => now()]
            );
        }

        $this->fetchNotifications(false);
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.dashboard.alerts-panel');
    }
}
