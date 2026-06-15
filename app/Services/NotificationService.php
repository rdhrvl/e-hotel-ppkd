<?php

namespace App\Services;

use App\Models\UserNotification;
use App\Models\User;

class NotificationService
{
    public static function create(User $user, string $type, string $title, string $message): UserNotification
    {
        return UserNotification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    public static function passwordChanged(User $user): UserNotification
    {
        return self::create(
            $user,
            'password_change',
            'Password Updated',
            'Your account password has been successfully changed.'
        );
    }

    public static function pinChanged(User $user): UserNotification
    {
        return self::create(
            $user,
            'pin_change',
            'PIN Code Updated',
            'Your 6-digit transaction PIN has been successfully changed.'
        );
    }

    public static function uploadSuccess(User $user, string $uploadType): UserNotification
    {
        $typeName = $uploadType === 'e_sign' ? 'E-Signature' : 'E-Stamp';
        return self::create(
            $user,
            'upload_success',
            $typeName . ' Uploaded',
            'Your ' . $typeName . ' file has been successfully uploaded and saved.'
        );
    }

    public static function eSignUploaded(User $user): UserNotification
    {
        return self::uploadSuccess($user, 'e_sign');
    }

    public static function eStampUploaded(User $user): UserNotification
    {
        return self::uploadSuccess($user, 'e_stamp');
    }

    public static function deliveryCheckpoint(User $user, string $dnNumber, string $checkpoint): UserNotification
    {
        return self::create(
            $user,
            'delivery_checkpoint',
            'Delivery Note Update',
            'Delivery Note ' . $dnNumber . ' status updated to ' . $checkpoint . '.'
        );
    }
}
