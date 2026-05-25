<?php

namespace Karja\EmailConfig\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmailConfigurationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('email_configurations', 'name')],
            'subject' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('email_configurations', 'slug')],
            'html_content' => ['required', 'string'],
            'text_content' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'type' => ['nullable', 'string', 'max:255'],
        ];
    }
}
