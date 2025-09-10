<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePermissionRequest extends FormRequest
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
                'unique:desa_module_template.desa_module_template_permissions,name',
            ],
            'module_name' => [
                'string',
            ],
        ];
    }
}
