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

    public function getAll(){
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

    public function getByEmail($email)
    {
        $user = $this->userService->getByEmail($email);

        if (!$user) {
            $this->respondWithError(404, "User with email ". $email . " not found");
            return;
        }

        $this->respond($user);
    }

    public function getById($id)
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

    public function create()
    {
        try {
            $requestBody = file_get_contents('php://input');
            $userData = json_decode($requestBody);
    
            $newUser = new User();
            $newUser->setEmail($userData->email)
                ->setPassword($userData->password)
                ->setRole($userData->role);                
            
            $newUser = $this->userService->create($newUser);

        } catch (Exception $e) {
            $this->respondWithError(500, $e->getMessage());
        }

        $this->respond($newUser);
    }

    public function update($id) {
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

    public function delete($id)
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

        // read user data from request body
        $postedUser = $this->createObjectFromPostedJson("Models\\User");

        // get user from db
        $user = $this->userService->checkUsernamePassword($postedUser->getEmail(), $postedUser->getPassword());

        // if the method returned false, the username and/or password were incorrect
        if(!$user) {
            $this->respondWithError(401, "Invalid login");
            return;
        }

        // generate jwt
        $tokenResponse = $this->generateJwt($user);       

        $this->respond($tokenResponse);    
    }

    public function generateJwt($user) {
        $secret_key = "megasuperamazinglysecurekey";

        $issuer = "THE_ISSUER"; // this can be the domain/servername that issues the token
        $audience = "THE_AUDIENCE"; // this can be the domain/servername that checks the token

        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before 
        $expire = $issuedAt + 600; // expiration time is set at +600 seconds (10 minutes)

        // JWT expiration times should be kept short (10-30 minutes)
        // A refresh token system should be implemented if we want clients to stay logged in for longer periods

        // note how these claims are 3 characters long to keep the JWT as small as possible
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
                "expireAt" => $expire
            );
    }    
}
