<?php

namespace Karja\EmailConfig\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Karja\EmailConfig\Models\EmailConfiguration;

class UpdateEmailConfigurationRequest extends FormRequest
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
        /** @var EmailConfiguration $configuration */
        $configuration = $this->route('email_configuration');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('email_configurations', 'name')->ignore($configuration->getKey())],
            'subject' => ['sometimes', 'required', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('email_configurations', 'slug')->ignore($configuration->getKey())],
            'html_content' => ['sometimes', 'required', 'string'],
            'text_content' => ['nullable', 'string'],
            'variables' => ['nullable', 'array'],
            'variables.*' => ['string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'type' => ['nullable', 'string', 'max:255'],
        ];
    }
}
