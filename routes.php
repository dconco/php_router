<?php

require_once 'vendor/autoload.php';
require_once 'Controller/BaseController.php';

$route = new Route();

$api_dir = 'Controller/UserController.php';

// Register API's
$route->add("/api/v1/account/login", $api_dir);
$route->add("/api/v1/account/register", $api_dir);
$route->add("/api/v1/account/logout", $api_dir);
$route->add("/api/v1/profile/{user_id}", $api_dir);
$route->add("/api/v1/dashboard", $api_dir);

// Register views page
$route->add('/', 'views/index.php');
$route->add('/login', 'views/login.php');
$route->add('/signup', 'views/signup.php');
$route->add('/profile/{user_id}', 'views/profile.php');

// Handle not found errors
$route->notFound('views/errors/404.php');