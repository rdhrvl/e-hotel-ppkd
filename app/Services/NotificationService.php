<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Notification;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Str;

class NotificationService
{
    /**
     * Handle room status change and route notifications to the correct roles.
     */
    public function handleRoomStatusChange(Room $room, ?User $actor, string $newStatus, ?string $extraDetails = null): ?Notification
    {
        // 1. Resolve actor role or default to 'system'
        $actorRole = 'system';
        $actorId = null;
        if ($actor) {
            $actorId = $actor->id;
            if ($actor->role) {
                $actorRole = $actor->role->slug;
            }
        }

        // 2. Identify target roles, priority, urgency, and formulate message
        $targetRoles = ['superadmin']; // Super admin always gets everything
        $priority = 'medium';
        $isUrgent = false;
        $message = "";

        // Status mapping to human-readable names
        $statusLabels = [
            'available' => 'Available',
            'reserved' => 'Reserved',
            'occupied' => 'Occupied',
            'cleaning' => 'Cleaning',
            'maintenance' => 'under Maintenance',
        ];

        $statusStr = $statusLabels[$newStatus] ?? $newStatus;

        // Custom note overrides standard alert messages if it is a custom housekeeping or front desk note
        if (!empty($extraDetails) && !Str::contains($extraDetails, ['Arrival Time:', 'Profession:', 'Member No:'])) {
            $message = $extraDetails;
            if (!Str::contains(strtolower($message), ['room', $room->room_number])) {
                $message = "Room {$room->room_number} is {$message}";
            }
        } else {
            // Routing logic based on who updated it
            if ($actorRole === 'front_desk') {
                // Front Desk updates HK related statuses
                $targetRoles[] = 'housekeeping';
                
                if ($newStatus === 'maintenance') {
                    $priority = 'high';
                    $isUrgent = true;
                    $message = "Room {$room->room_number} is under Maintenance";
                } elseif ($newStatus === 'cleaning') {
                    $priority = 'medium';
                    $message = "Room {$room->room_number} needs Cleaning";
                } else {
                    $priority = 'low';
                    $message = "Room {$room->room_number} status updated to {$statusStr}";
                }
            } elseif ($actorRole === 'housekeeping') {
                // Housekeeping updates FD related statuses
                $targetRoles[] = 'front_desk';

                if ($newStatus === 'available') {
                    $priority = 'medium';
                    $message = "Room {$room->room_number} cleaning process is done";
                } elseif ($newStatus === 'maintenance') {
                    $priority = 'high';
                    $isUrgent = true;
                    $message = "Room {$room->room_number} is under Maintenance";
                } else {
                    $priority = 'medium';
                    $message = "Room {$room->room_number} is now {$statusStr}";
                }
            } else {
                // System or Admin updates - notify everyone
                $targetRoles[] = 'front_desk';
                $targetRoles[] = 'housekeeping';

                if ($newStatus === 'available') {
                    $priority = 'medium';
                    $message = "Room {$room->room_number} is available now";
                } elseif ($newStatus === 'maintenance') {
                    $priority = 'high';
                    $isUrgent = true;
                    $message = "Room {$room->room_number} is under Maintenance";
                } else {
                    $priority = 'low';
                    $message = "Room {$room->room_number} status changed to {$statusStr}";
                }
            }
        }

        // 3. Persist notification to DB
        $activeBooking = \App\Models\Booking::where('room_id', $room->id)
            ->where('status', 'checked_in')
            ->latest()
            ->first();

        $actionUrl = '/bookings';
        if (in_array('housekeeping', $targetRoles)) {
            $actionUrl = '/housekeeping?open_room_id=' . $room->id;
        } elseif ($activeBooking) {
            $actionUrl = '/bookings?checkout_booking_id=' . $activeBooking->id;
        }

        return Notification::create([
            'room_id' => $room->id,
            'actor_id' => $actorId,
            'target_roles' => $targetRoles,
            'message' => $message,
            'priority' => $priority,
            'is_urgent' => $isUrgent,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Dispatch a custom alert (e.g. Towel missing)
     */
    public function dispatchCustomAlert(Room $room, User $actor, string $message, string $priority = 'high', bool $isUrgent = true): Notification
    {
        $actorRole = $actor->role ? $actor->role->slug : 'system';
        $targetRoles = ['superadmin'];

        if ($actorRole === 'housekeeping') {
            $targetRoles[] = 'front_desk';
        } else {
            $targetRoles[] = 'housekeeping';
        }

        $activeBooking = \App\Models\Booking::where('room_id', $room->id)
            ->where('status', 'checked_in')
            ->latest()
            ->first();

        $actionUrl = '/bookings';
        if (in_array('housekeeping', $targetRoles)) {
            $actionUrl = '/housekeeping?open_room_id=' . $room->id;
        } elseif ($activeBooking) {
            $actionUrl = '/bookings?checkout_booking_id=' . $activeBooking->id;
        }

        return Notification::create([
            'room_id' => $room->id,
            'actor_id' => $actor->id,
            'target_roles' => $targetRoles,
            'message' => $message,
            'priority' => $priority,
            'is_urgent' => $isUrgent,
            'action_url' => $actionUrl,
        ]);
    }

    /**
     * Dispatch an alert for F&B ordering.
     */
    public function dispatchFoodOrderAlert(\App\Models\Booking $booking, string $message, string $targetRole, string $priority = 'medium', bool $isUrgent = false): Notification
    {
        $actorId = auth()->id();
        $targetRoles = ['superadmin', $targetRole];

        return Notification::create([
            'room_id' => $booking->room_id,
            'actor_id' => $actorId,
            'target_roles' => $targetRoles,
            'message' => $message,
            'priority' => $priority,
            'is_urgent' => $isUrgent,
            'action_url' => $targetRole === 'fnb' ? '/fnb' : '/bookings',
        ]);
    }
}
