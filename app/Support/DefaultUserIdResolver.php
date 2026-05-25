<?php

namespace App\Support;

use App\Contracts\UserIdResolver;

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
