<?php

namespace Controllers;

use Exception;
use Services\UserService;
use Models\User;
use \Firebase\JWT\JWT;

class UserController extends Controller
{
    private $userService;

    // initialize services
    function __construct()
    {
        $this->userService = new UserService();
    }

    public function getAll(){ //used while an admin views the Users list
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
        
        $offset = NULL;
        $limit = NULL;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }

        $vets = $this->userService->getAll($offset, $limit);

        $this->respond($vets);
    }

    public function getByEmail($email) //used for logging in.
    {
        $user = $this->userService->getByEmail($email);

        if (!$user) {
            $this->respondWithError(404, "User with email ". $email . " not found");
            return;
        }

        $this->respond($user);
    }

    public function getById($id) //used while editing a user
    {
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }

        $user = $this->userService->getById($id);

        if (!$user) {
            $this->respondWithError(404, "User with id ". $id . " not found");
            return;
        }

        $this->respond($user);
    }

    public function create() //used while registering
    {
        try {
            $requestBody = file_get_contents('php://input');
            $userData = json_decode($requestBody);
    
            $newUser = new User();
            $newUser->setEmail($userData->email)
                ->setPassword($userData->password)
                ->setRole($userData->role);    
                
            $user = $this->userService->checkEmail($newUser->getEmail());

            if(!$user) {
                $this->respondWithError(409, "Email is already is in use."); //used 409 because it's a conflict error. source: https://umbraco.com/knowledge-base/http-status-codes/
                return;
            }
            
            $newUser = $this->userService->create($newUser);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($newUser);
    }

    public function update($id) { //used while updating the user
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
        try {
            $requestBody = file_get_contents('php://input');
            $userData = json_decode($requestBody);
    
            $user = new User();
            $user->setEmail($userData->email)
                ->setPassword($userData->password)
                ->setRole($userData->role);                
            
            
            $userToUpdate = $this->userService->update($user, $id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($userToUpdate);
    }

    public function delete($id) //used while deleting the user
    {
        $admin = $this->checkForAdmin();
        if (!$admin) {
            return;
        }
        try {
            $this->userService->delete($id);
        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond(true);
    }

    public function login() {
        
        $postedUser = $this->createObjectFromPostedJson("Models\\User");

        $user = $this->userService->checkEmailPassword($postedUser->getEmail(), $postedUser->getPassword());

        if(!$user) {
            $this->respondWithError(401, "Invalid login");
            return;
        }

        $tokenResponse = $this->generateJwt($user);       

        $this->respond($tokenResponse);    
    }

    public function generateJwt($user) {
        $secret_key = "megasuperamazinglysecurekey";

        $issuer = "THE_ISSUER"; 
        $audience = "THE_AUDIENCE"; 

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before 
        $expire = $issuedAt + 600; //(10 minutes)

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->getId(),
                "email" => $user->getEmail(),
                "role" => $user->getRole()
        ));

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return 
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "email" => $user->getEmail(),
                "role" => $user->getRole(),
                "expireAt" => $expire
            );
    }    
}
