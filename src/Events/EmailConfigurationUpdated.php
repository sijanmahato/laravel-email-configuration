<?php

namespace Karja\EmailConfig\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Karja\EmailConfig\Models\EmailConfiguration;

class EmailConfigurationUpdated
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(public EmailConfiguration $emailConfiguration)
    {
    }
}
