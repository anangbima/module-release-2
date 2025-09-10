<?php

namespace Modules\ModuleRelease2\Services\Shared;

use DesaDigitalSupport\NotificationProvider\Contracts\NotificationProviderInterface;
use Illuminate\Support\Collection;
use Modules\ModuleRelease2\Repositories\Interfaces\UserRepositoryInterface;
use Modules\ModuleRelease2\Notifications\GeneralNotification;

class NotificationService implements NotificationProviderInterface
{
    public string $module = 'module-release-2';
    
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected LogActivityService $logActivityService,
    ){ }

    public function getAllByUser(?string $userId = null): array
    {
        if (empty($userId)) {
            $userId = module_release_2_auth_user()->id;
        }

        $user = $this->userRepository->find($userId);

        // balikin array biar sesuai kontrak
        return $user?->notifications?->toArray() ?? [];
    }

    /**
     * Get all notifications for a user.
     */
    public function getAll(int|string $userId): Collection
    {
        $user = $this->userRepository->find($userId);

        return $user?->notifications ?? collect();
    }

    /**
     * Get unread notifications for a user.
     */
    public function getUnread(int|string $userId): Collection
    {
        $user = $this->userRepository->find($userId);

        return $user?->unreadNotifications ?? collect();
    }

    /**
     * Get read notifications for a user.
     */
    public function getRead(int|string $userId): Collection
    {
        $user = $this->userRepository->find($userId);

        return $user?->readNotifications ?? collect();
    }
    
    /**
     * Send a general notification.
     */
    public function sendToUser(string $title, string $message, int|string $userId, string $actionUrl, string $type): void
    {
        $notification = new GeneralNotification($title, $message, $userId, $actionUrl, $type);

        $user = $this->userRepository->find($userId);

        if ($user) {
            $user->notify($notification);
        }
    }

    /**
     * Send a general notification to multiple users.
     */
    public function sendToUsers(string $title, string $message, array $userIds, string $actionUrl, string $type): void
    {
        foreach ($userIds as $userId) {
            $this->sendToUser($title, $message, $userId, $actionUrl, $type);
        }
    }

    /**
     * Read a notification for a user.
     */
    public function read(string $notificationId, int|string $userId)
    {
        $user = $this->userRepository->find($userId);

        if ($user) {
            $notification = $user->notifications()->find($notificationId);

            if ($notification && is_null($notification->read_at)) {
                $before = ['read_at' => null];

                $notification->markAsRead();

                $after = ['read_at' => $notification->read_at];

                // Log the activity
                $this->logActivityService->log(
                    action: 'read_notification',
                    model: $notification,
                    description: sprintf(
                        'User "%s" read a notification titled "%s".',
                        $user->name,
                        $notification->data['title'] ?? 'No Title'
                    ),
                    before: $before,
                    after: $after
                );
            }

            return $notification;
        }

        return null;
    }

    /**
     * Read all notifications for a user.
     */
    public function readAll(int|string $userId): void
    {
        $user = $this->userRepository->find($userId);

        if ($user) {
            $unread = $user->unreadNotifications;

            if ($unread->isNotEmpty()) {
                // Ambil daftar ID unread sebelum
                $before = $unread->pluck('read_at', 'id')->map(fn($v) => ['read_at' => $v])->toArray();

                // Tandai semua sebagai read
                $unread->markAsRead();

                // Ambil state sesudah
                $after = $unread->pluck('read_at', 'id')->map(fn($v) => ['read_at' => $v])->toArray();

                // Log activity
                $this->logActivityService->log(
                    action: 'read_all_notifications',
                    model: $user,
                    description: sprintf(
                        'User "%s" marked %d notifications as read.',
                        $user->name,
                        $unread->count()
                    ),
                    before: $before,
                    after: $after
                );
            }
        }
    }


    /**
     * Delete a notification for a user.
     */
    public function delete(string $notificationId, int|string $userId): void
    {
        $user = $this->userRepository->find($userId);

        if ($user) {
            $notification = $user->notifications()->find($notificationId);

            if ($notification) {
                // Simpan state sebelum dihapus
                $before = [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? null,
                    'message' => $notification->data['message'] ?? null,
                    'type' => $notification->data['type'] ?? null,
                    'created_at' => $notification->created_at,
                ];

                // Simpan judul untuk deskripsi log (biar aman setelah delete)
                $title = $notification->data['title'] ?? 'No Title';

                $notification->delete();

                // Log activity
                $this->logActivityService->log(
                    action: 'delete_notification',
                    model: $user, // lebih aman log ke user daripada model yang udah dihapus
                    description: 'Deleted notification: ' . $title,
                    before: $before,
                    after: []
                );
            }
        }
    }


    /**
     * Clear all notifications for a user.
     */
    public function clearAll(int|string $userId): void
    {
        $user = $this->userRepository->find($userId);

        if ($user) {
            $notifications = $user->notifications;

            if ($notifications->isNotEmpty()) {
                // Simpan state sebelum dihapus
                $before = $notifications->mapWithKeys(function ($n) {
                    return [
                        $n->id => [
                            'title' => $n->data['title'] ?? null,
                            'message' => $n->data['message'] ?? null,
                            'type' => $n->data['type'] ?? null,
                            'created_at' => $n->created_at,
                        ]
                    ];
                })->toArray();

                $count = $notifications->count();

                // Hapus semua notifikasi
                $user->notifications()->delete();

                // Log activity
                $this->logActivityService->log(
                    action: 'clear_all_notifications',
                    model: $user,
                    description: "Cleared {$count} notifications for {$user->name}",
                    before: $before,
                    after: []
                );
            }
        }
    }

    /**
     * Grouping data by created_at
     */
    public function getGrouped(int|string $userId): Collection
    {
        return $this->getAll($userId)
            ->groupBy(function ($notification) {
                if ($notification->created_at->isToday()) {
                    return 'Today';
                } elseif ($notification->created_at->isYesterday()) {
                    return 'Yesterday';
                }
                return $notification->created_at->format('d M Y');
            });
    }
}