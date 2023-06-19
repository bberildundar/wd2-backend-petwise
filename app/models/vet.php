<?php

namespace Models;

class Vet implements \JsonSerializable {
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $specialization;
    private string $imageURL;

    #[\ReturnTypeWillChange]
    public function jsonSerialize() {
        $vars = get_object_vars($this);
        return $vars;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getSpecialization(): string {
        return $this->specialization;
    }

    public function getImageURL(): string {
        return $this->imageURL;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function setFirstName(string $firstName): self {
        $this->firstName = $firstName;
        return $this;
    }

    public function setLastName(string $lastName): self {
        $this->lastName = $lastName;
        return $this;
    }

    public function setSpecialization(string $specialization): self {
        $this->specialization = $specialization;
        return $this;
    }

    public function setImageURL(string $imageURL): self {
        $this->imageURL = $imageURL;
        return $this;
    }
}
?>