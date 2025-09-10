<?php

namespace Modules\ModuleRelease2\Http\Requests\Web\Admin;

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
                'unique:module_release_2.module_release_2_permissions,name',
            ],
            'module_name' => [
                'string',
            ],
        ];
    }
}
