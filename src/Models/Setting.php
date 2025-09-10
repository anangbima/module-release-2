<?php

namespace Modules\ModuleRelease2\Models;

use Modules\ModuleRelease2\Database\Factories\SettingFactory;

class Setting extends BaseModel
{
    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config('module_release_2.tables.settings', 'settings');
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'key',
        'value',
        'type',
    ];
    
    protected static function newFactory()
    {
        return SettingFactory::new();
    }
}
