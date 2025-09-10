<?php

namespace Modules\DesaModuleTemplate\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUlids;
    
    public function getConnectionName()
    {
        return config('desamoduletemplate.permission.connection', 'desa_module_template');
    }

    public function getTable()
    {
        return config('desamoduletemplate.permission.table_names.roles', 'desa_module_template_roles');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            config('desamoduletemplate.permission.table_names.role_has_permissions', 'desa_module_template_role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            config('desamoduletemplate.permission.table_names.model_has_roles', 'desa_module_template_model_has_roles'),
            'role_id',
            'model_id'
        )->withPivot('model_type');
    }

}
