<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Web\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'string', 'unique:desa_module_template.users,email,' . $this->route('user')->slug. ',slug'],
            'role' => ['required', 'string', 'exists:desa_module_template.desa_module_template_roles,id'],
            'province' => ['required', 'string'],
            'city' => ['required', 'string'],
            'district' => ['required', 'string'],
            'village' => ['required', 'string'],
        ];
    }
}
