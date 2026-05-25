<?php

namespace Karja\EmailConfig\Models;

use Illuminate\Database\Eloquent\Model;
use Karja\EmailConfig\Events\EmailConfigurationCreated;
use Karja\EmailConfig\Events\EmailConfigurationDeleted;
use Karja\EmailConfig\Events\EmailConfigurationUpdated;

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
