<?php

declare(strict_types=1);
require_once 'vendor/autoload.php';
require_once 'Models/database.php';
require_once 'ApiController.php'; 
require_once 'secret_key.php';
require_once 'Token.php';
require_once 'env.php';

use Api\Endpoint;
use JWT\Token\Token;

$uri = $_REQUEST["uri"];
$url = explode("?", $uri);
$req_uri = preg_replace("/(^\/)|(\/$)/", "", $url[0]);

header('Content-Type: Application/json', false);

switch ($req_uri) {
    case "api/v1/account/register":
        // FOR REGISTER API
        Endpoint::Register();
        echo $response;

        break;


    case "api/v1/account/login":
        // FOR LOGIN API
        $response = Endpoint::Login();
        echo $res;

        break;

    
    case "api/v1/profile/{$req['user_id']}":
        // FOR PROFILE API
        $response = Endpoint::Profile();
        echo $res;

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