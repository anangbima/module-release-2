<?php

namespace Modules\ModuleRelease2\Http\Requests\Api\Auth;

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
                Rule::exists(config(module_release_2_meta('snake').'.database.database_connection', module_release_2_meta('snake')).'.'.config('users.tables.users', 'users'), 'email'),
            ],
        ];
    }
}
