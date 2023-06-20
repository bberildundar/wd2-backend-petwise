<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

error_reporting(E_ALL);
ini_set("display_errors", 1);

require __DIR__ . '/../vendor/autoload.php';

// Create Router instance
$router = new \Bramus\Router\Router();

$router->setNamespace('Controllers');
///////


// routes for the USERS endpoint
$router->post('/users/login', 'UserController@login');
$router->get('/users', 'UserController@getAll');
$router->get('/users/(\d+)', 'UserController@getById');
$router->get('/users/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})',
 'UserController@getByEmail'); /* made this like that so it can match with the email addresses. 
 because otherwise it searches for getById method, tried w+ but didnt work so i did it like that*/
$router->post('/users', 'UserController@create');
$router->put('/users/(\d+)', 'UserController@update');
$router->delete('/users/(\d+)', 'UserController@delete');

// routes for the VETS endpoint
$router->get('/vets', 'VetController@getAll');
$router->get('/vets/(\d+)', 'VetController@getById');
$router->post('/vets', 'VetController@create');
$router->put('/vets/(\d+)', 'VetController@update');
$router->delete('/vets/(\d+)', 'VetController@delete');


// Run it!
$router->run();