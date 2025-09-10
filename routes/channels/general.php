<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel untuk notifikasi
Broadcast::channel('module-release-2.notifications.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});