<?php

namespace Karja\EmailConfig\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Karja\EmailConfig\Models\EmailConfiguration;

/**
 * @mixin EmailConfiguration
 */
class EmailConfigurationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'slug' => $this->slug,
            'html_content' => $this->html_content,
            'text_content' => $this->text_content,
            'variables' => $this->variables,
            'is_active' => $this->is_active,
            'type' => $this->type,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
