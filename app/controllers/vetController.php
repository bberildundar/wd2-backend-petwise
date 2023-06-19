<?php

namespace Controllers;

use Exception;
use Models\Vet;
use Services\VetService;

class VetController extends Controller{
    private $vetService;

    function __construct(){
        $this->vetService = new VetService();
    }

    public function getAll(){
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $vets = $this->vetService->getAll($offset, $limit);

        $this->respond($vets);
    }

    public function getById($id)
    {
        $vet = $this->vetService->getById($id);

        if (!$vet) {
            $this->respondWithError(404, "Vet not found");
            return;
        }

        $this->respond($vet);
    }

    public function create()
    {
        try {
            $requestBody = file_get_contents('php://input');
            $vetData = json_decode($requestBody);
    
            $newVet = new Vet();
            $newVet->setFirstName($vetData->firstName)
                ->setLastName($vetData->lastName)
                ->setSpecialization($vetData->specialization)
                ->setImageURL($vetData->imageURL);
            
            $newVet = $this->vetService->create($newVet);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($newVet);
    }

    public function update($id) {
        try {
            $requestBody = file_get_contents('php://input');
            $vetData = json_decode($requestBody);

            $vet = new Vet();
                $vet->setFirstName($vetData->firstName)
                    ->setLastName($vetData->lastName)
                    ->setSpecialization($vetData->specialization)
                    ->setImageURL($vetData->imageURL);
            
            $vetToUpdate = $this->vetService->update($vet, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($vetToUpdate);
    }

    public function delete($id)
    {
        try {
            $this->vetService->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}
