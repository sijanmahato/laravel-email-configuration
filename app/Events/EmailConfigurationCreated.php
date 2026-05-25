<?php

namespace App\Events;

use App\Models\EmailConfiguration;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailConfigurationCreated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public EmailConfiguration $emailConfiguration)
    {
    }
}
