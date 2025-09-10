<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Web\Shared;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:csv,xlsx,json,pdf|max:2048',
        ];
    }
}
