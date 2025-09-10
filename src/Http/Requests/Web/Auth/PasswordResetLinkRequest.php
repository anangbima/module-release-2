<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Web\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PasswordResetLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::exists(config(desa_module_template_meta('snake').'.database.database_connection', desa_module_template_meta('snake')) . '.' . config('users.tables.users', 'users'), 'email'),
            ],
        ];
    }
}
