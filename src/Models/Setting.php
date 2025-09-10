<?php

namespace Modules\DesaModuleTemplate\Models;

use Modules\DesaModuleTemplate\Database\Factories\SettingFactory;

class Setting extends BaseModel
{
    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config('desa_module_template.tables.settings', 'settings');
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
