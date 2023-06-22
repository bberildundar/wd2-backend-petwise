<?php

namespace Models;

enum Role: int {
    case User = 0;
    case Admin = 1;

    public function toInt(): int {
        return $this->value;
    }

    public function toString(): string {
        return match ($this) {
            Role::User => 'User',
            Role::Admin => 'Admin',
        };
    }
}