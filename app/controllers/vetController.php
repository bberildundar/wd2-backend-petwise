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

    function getAll(){
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
        $product = $this->vetService->getById($id);

        if (!$product) {
            $this->respondWithError(404, "Vet not found");
            return;
        }

        $this->respond($product);
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
}
