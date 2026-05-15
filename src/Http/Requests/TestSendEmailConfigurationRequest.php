<?php

namespace Karja\EmailConfig\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TestSendEmailConfigurationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'to' => ['required', 'email'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['nullable'],
        ];
    }
}
