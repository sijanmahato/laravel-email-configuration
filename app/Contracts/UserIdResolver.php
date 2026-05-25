<?php

namespace App\Contracts;

interface UserIdResolver
{
    public function resolve(): ?int;
}
