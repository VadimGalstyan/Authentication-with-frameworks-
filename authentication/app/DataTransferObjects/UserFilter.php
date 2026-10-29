<?php

namespace App\DataTransferObjects;

final readonly class UserFilter
{
    public function __construct(public string $search = '',public ?string $role = null,public ?bool $verified = null,) 
    {
    }
}