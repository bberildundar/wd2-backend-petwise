<?php

namespace Models;
use Models\Role;

class User implements \JsonSerializable {
    private int $id;
    private string $email;
    private string $password;
    private Role $role;

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getRole(): Role {
        return $this->role;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function setEmail(string $email): self {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): self {
        $this->password = $password;
        return $this;
    }

    public function setRole(int $role): self {
        $this->role = Role::tryFrom($role);
        return $this;
    }   
}
?>