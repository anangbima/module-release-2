<?php

namespace Modules\ModuleRelease2\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Modules\ModuleRelease2\Database\Factories\NotificationFactory;

class Notification extends DatabaseNotification
{
    use HasFactory, HasUlids;

    protected $connection = 'module_release_2';
    protected $table = 'notifications';

    protected static function newFactory()
    {
        return NotificationFactory::new();
    }
}
