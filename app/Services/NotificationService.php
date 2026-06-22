<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Collection;

class NotificationService
{
    public function notify(User $user, string $title, string $message, string $type = 'system', ?array $data = null): UserNotification
    {
        return UserNotification::query()->create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function notifyRoles(array $roles, string $title, string $message, string $type = 'system', ?array $data = null): void
    {
        User::query()->role($roles)->each(function (User $user) use ($title, $message, $type, $data) {
            $this->notify($user, $title, $message, $type, $data);
        });
    }

    public function notifyPlacement(string $employeeName, string $roomLabel): void
    {
        $this->notifyRoles(
            ['super_admin', 'hr', 'dorm_manager'],
            'Yeni Yerleşim',
            "{$employeeName} → {$roomLabel} odasına yerleştirildi.",
            'placement'
        );
    }

    public function forUser(User $user, int $limit = 50): Collection
    {
        return UserNotification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function markRead(UserNotification $notification): void
    {
        $notification->update(['read_at' => now()]);
    }

    public function markAllRead(User $user): void
    {
        UserNotification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
