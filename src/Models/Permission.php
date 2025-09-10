<?php

namespace Modules\DesaModuleTemplate\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUlids;

    public function getConnectionName()
    {
        return config('desamoduletemplate.permission.connection', 'desa_module_template');
    }

    public function getTable()
    {
        return config('desamoduletemplate.permission.table_names.permissions', 'desa_module_template_permissions');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            config('desamoduletemplate.permission.table_names.role_has_permissions', 'desa_module_template_role_has_permissions'),
            'permission_id',
            'role_id'
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            config('desamoduletemplate.permission.table_names.model_has_permissions', 'desa_module_template_model_has_permissions'),
            'permission_id',
            'model_id'
        )->withPivot('model_type');
    }
}
