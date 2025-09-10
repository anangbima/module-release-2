<?php

namespace Modules\DesaModuleTemplate\Models;

use Modules\DesaModuleTemplate\Traits\HasSlug;

class ApiClient extends BaseModel
{
    use HasSlug;

    /**
     * Resolve table name from config
     */
    protected function resolveTableName(): string
    {
        return config('desa_module_template.tables.api_clients', 'api_clients');
    }

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'slug',
        'api_key',
        'secret_key',
        'is_active',
    ];

    protected $casts = [
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Get the Route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
