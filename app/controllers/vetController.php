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

    public function getAll(){ //used in homepage while showing the vets

        //im not checking for token because visitors (not logged in) should also see the vets.
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

    public function getById($id) //used while editing the vet
    {
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
        $vet = $this->vetService->getById($id);

        if (!$vet) {
            $this->respondWithError(404, "Vet not found");
            return;
        }

        $this->respond($vet);
    }

    public function create() //used while adding the vet 
    {
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }

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

    public function update($id) { //used while updating the vet info
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
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

    public function delete($id) //used while deleting the vet from the system
    {
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
        try {
            $this->vetService->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }
}
