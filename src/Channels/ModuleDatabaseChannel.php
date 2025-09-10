<?php

namespace Modules\DesaModuleTemplate\Channels;

use Illuminate\Notifications\Notification;
use Modules\DesaModuleTemplate\Models\Notification as ModuleNotification;
use Illuminate\Support\Facades\Log;

class ModuleDatabaseChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->routeNotificationFor('database')) {
            Log::info('ModuleDatabaseChannel: No database route for notifiable.', [
                'notifiable_type' => get_class($notifiable),
                'notifiable_id' => $notifiable->getKey(),
            ]);
            return;
        }

        $data = $notification->toDatabase($notifiable);

        Log::info('ModuleDatabaseChannel: Creating notification.', [
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'type' => get_class($notification),
            'data' => $data,
        ]);

        ModuleNotification::create([
            'notifiable_type' => get_class($notifiable),
            'notifiable_id' => $notifiable->getKey(),
            'type' => get_class($notification),
            'data' => $data,
            'read_at' => null,
        ]);
    }
}
