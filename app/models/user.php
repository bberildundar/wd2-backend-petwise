<?php

namespace Models;

class User implements \JsonSerializable {
    private int $id;
    private string $email;
    private string $password;
    private bool $role;

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

    public function getRole(): bool {
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

    public function setRole(bool $role): self {
        $this->role = $role;
        return $this;
    }
   
}
?>