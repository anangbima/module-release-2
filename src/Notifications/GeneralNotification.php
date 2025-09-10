<?php

namespace Modules\ModuleRelease2\Notifications;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $title, public string $message, public int|string $userId, public string $actionUrl, public string $type){}

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['module_release_2_database', 'broadcast'];
    }

    /**
     * To be stored in the database.
     */
    public function toDatabase($notifiable): array
    {
        return [
            'action_url' => $this->actionUrl,
            'title' => $this->title,
            'message' => $this->message,
            'user_id' => $this->userId,
            'type' => $this->type,
        ];
    }
    
    /**
     * To be broadcasted.
     */
    public function toBroadcast($notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'action_url' => $this->actionUrl,
            'title' => $this->title,
            'message' => $this->message,
            'user_id' => $this->userId,
            'type' => $this->type,
        ]);
    }

    public function broadcastOn() : array
    {
        return [
            new PrivateChannel(
                'module-release-2.notifications.' . $this->userId
            ),
        ];
    }

}
