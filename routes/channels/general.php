<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel untuk notifikasi
Broadcast::channel('desa-module-template.notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});