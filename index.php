<?php

$api_dir = __DIR__ . "/Controller/Api/";
require $api_dir . "BaseController.php";

$route = new Route();

$views = __DIR__ . "/views/";

// create new public views routes
$route->add("/", $views . "index.php");
$route->add("/index", $views . "index.php");

$route->add("/login", $views . "login.php");
$route->add("/signup", $views . "signup.php");

$route->add("/new/upload", $views . "upload.php");

$route->add("/user/{username}", $views . "profile.php");

// handling API's
$route->add("/api/account/register", $api_dir . "UserController.php");
$route->add("/api/account/login", $api_dir . "UserController.php");
$route->add("/api/user/{user_id}", $api_dir . "UserController.php");

// handling not found errors
$route->notFound($views . "errors/404.php");
