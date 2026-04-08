<?php

declare(strict_types=1);

namespace App\Models;

class User
{
    public function __construct(
        private string $name,
        private string $email,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return "{$this->name} <{$this->email}>";
    }
}