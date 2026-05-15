<?php

namespace Karja\EmailConfig\Contracts;

interface UserIdResolver
{
    public function resolve(): ?int;
}
