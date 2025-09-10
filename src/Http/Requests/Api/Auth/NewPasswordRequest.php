<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class NewPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => ['required'],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::exists(
                    config(desa_module_template_meta('snake').'.database.database_connection', desa_module_template_meta('snake')) . '.' . config('users.tables.users', 'users'),
                    'email'
                ),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }
}
