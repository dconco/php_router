<?php

define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);

require_once 'vendor/autoload.php';
require_once 'Models/database.php';
require_once 'ApiController.php';
require_once 'secret_key.php';
require_once 'env.config.php';
require_once 'Token.php';

use Api\Endpoint;

$uri     = $_REQUEST["uri"];
$url     = explode("?", $uri);
$req_uri = preg_replace("/(^\/)|(\/$)/", "", $url[0]);

session_start();

if (isset($_COOKIE['user_id']))
{
    $_SESSION['user_id'] = $_COOKIE['user_id'];
}

header('Content-Type: Application/json', false);

switch ($req_uri)
{
    case "api/v1/account/register":
        // FOR REGISTER API
        $response = Endpoint::Register();
        echo $response;

        break;


    case "api/v1/account/login":
        // FOR LOGIN API
        $response = Endpoint::Login();
        echo $response;

        break;


    case "api/v1/account/logout":
        // FOR LOGIN API
        $response = Endpoint::Logout();
        echo $response;

        break;


    case "api/v1/profile/{$req['user_id']}":
        // FOR PROFILE API
        $response = Endpoint::Profile($req['user_id']);
        echo $response;

        break;


    default:
        $response = [
            "status" => 404,
            "statusText" => "Not Found",
            "message" => "API Request Not Found",
        ];

        header("HTTP/1.1 404 Not Found");
        exit(json_encode($response));
}