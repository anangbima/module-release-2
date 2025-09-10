<?php

namespace Modules\DesaModuleTemplate\Http\Requests\Web\Shared;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // max 2MB
        ];
    }
}
