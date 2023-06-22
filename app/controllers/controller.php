<?php

namespace Controllers;

use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
use Models\Role;

class Controller
{
    function checkForJwt() {
         if(!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->respondWithError(401, "No token provided");
            return;
        }

        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];
        $arr = explode(" ", $authHeader);
        $jwt = $arr[1];

        // Decode JWT
        $secret_key = "megasuperamazinglysecurekey";

        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));

                return $decoded;
            } catch (Exception $e) {
                $this->respondWithError(401, $e->getMessage());
                return;
            }
        }
    }

    function checkForAdmin():bool {
        $token = $this->checkForJWT();

        if (!$token) {
            return false;
        }

        if (Role::tryFrom($token->data->role) !== Role::Admin) {
            $this->respondWithError(403, "Access denied. You are not authorised for this action.");
            return false;
        }

        return true;
    }

    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpcode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpcode, $data);
    }

    private function respondWithCode($httpcode, $data)
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpcode);
        echo json_encode($data);
    }

    function createObjectFromPostedJson($className)
{
    $json = file_get_contents('php://input');
    $data = json_decode($json);

    $object = new $className();
    foreach ($data as $key => $value) {
        if (is_object($value)) {
            continue;
        }
        
        /*this method was using the direct variable like user->. But with 
        the following code it usese the setter method if available:*/
        $setterMethod = 'set' . ucfirst($key);
        if (method_exists($object, $setterMethod)) {
            $object->{$setterMethod}($value);
        }
    }
    return $object;
}

}
