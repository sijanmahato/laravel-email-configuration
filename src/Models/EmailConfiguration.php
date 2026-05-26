<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class emailConfiguration extends Model
{
    
    protected $table = 'email_configurations';

    protected $fillable = [
        'name',
        'subject',
        'slug',
        'html_content',
        'text_content',
        'variables',
        'is_active',
        'type',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];
}
