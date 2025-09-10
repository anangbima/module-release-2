<?php

namespace Modules\DesaModuleTemplate\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\DatabaseNotification;
use Modules\DesaModuleTemplate\Database\Factories\NotificationFactory;

class Notification extends DatabaseNotification
{
    use HasFactory, HasUlids;

    protected $connection = 'desa_module_template';
    protected $table = 'notifications';

    protected static function newFactory()
    {
        return NotificationFactory::new();
    }
}
