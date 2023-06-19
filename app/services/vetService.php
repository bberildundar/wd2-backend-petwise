<?php

namespace Services;

use Repositories\VetRepository;
use Models\Vet;

class VetService{
    private $vetRepository;

    function __construct(){
        $this-> vetRepository = new VetRepository();
    }

    function getAll($offset = null, $limit = null) {
        return $this->vetRepository->getAll($offset, $limit);
    }

    function getById($id) {
        return $this->vetRepository->getById($id);
    }

    function create(Vet $vet) {
        return $this->vetRepository->create($vet);
    }

    function update(Vet $vet, $id) {
        return $this->vetRepository->update($vet, $id);
    }

    function delete($id) {
        return $this->vetRepository->delete($id);
    }
}