<?php

namespace App\Models;

use App\Events\EmailConfigurationCreated;
use App\Events\EmailConfigurationDeleted;
use App\Events\EmailConfigurationUpdated;
use Illuminate\Database\Eloquent\Model;

class EmailConfiguration extends Model
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

    protected $dispatchesEvents = [
        'created' => EmailConfigurationCreated::class,
        'updated' => EmailConfigurationUpdated::class,
        'deleted' => EmailConfigurationDeleted::class,
    ];
}
