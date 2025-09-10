<?php

namespace Modules\ModuleRelease2\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:module_release_2.module_release_2_roles,name,' . $this->route('role')->id,
            ],
            'permissions' => [
                'array',
                'exists:module_release_2.module_release_2_permissions,id',
            ],
        ];
    }
}
