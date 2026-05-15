<?php

namespace Karja\EmailConfig\Support;

use Karja\EmailConfig\Contracts\UserIdResolver;

class DefaultUserIdResolver implements UserIdResolver
{
    public function resolve(): ?int
    {
        $user = auth()->user();

        if ($user === null) {
            return null;
        }

        return (int) $user->getAuthIdentifier();
    }
}
