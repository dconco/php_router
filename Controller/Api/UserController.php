<?php
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
$url = explode("?", $uri);

require "Models/database.php";

switch ($url[0]) {
    case "/api/account/user/register":
        /**
         * REGISTER ENDPOINT
         */
        $response = [];

        // if Request Method is not POST
        if ($method !== "POST") {
            $response = [
                "status" => 405,
                "statusText" => "Method Not Allowed",
                "message" =>
                    "The method used in the request is not allowed for the resource.",
            ];
        } else {
            /* MAIN ENDPOINT */
            $requestData = json_decode(file_get_contents("php://input"), true);

            $user = [
                "name" => htmlspecialchars($requestData["name"]),
                "email" => htmlspecialchars($requestData["email"]),
                "password" => htmlspecialchars($requestData["password"]),
            ];
            
            $response = [
                "status" => 405,
                "statusText" => "Method Not Allowed",
                "message" =>
                    "The method used in the request is not allowed for the resource.",
            ];
            //require __DIR__ . "/Auth/signup_auth.php";
        }

        /* RETURN RESPONSE TEXT */
        http_response_code($response["status"]);
        header("Content-Type: application/json");

        $res = json_encode($response);
        echo $res;

        break;
    /* // END REGISTER ENDPOINT */

    /* // GET USER ENDPOINT */
    case "/api/user/{$req["user_id"]}":
        $response = [];

        // if Request Method is not POST
        if ($method !== "GET") {
            $response = [
                "status" => 405,
                "statusText" => "Method Not Allowed",
                "message" =>
                    "The method used in the request is not allowed for the resource.",
            ];
        } else {
            $get_id = $req["user_id"];
            include_once __DIR__ . "/Auth/get_users.php";
        }

        /* RETURN RESPONSE TEXT */
        header("HTTP/1.1 {$response["status"]} {$response["statusText"]}");
        header("Content-type: application/json");

        $res = json_encode($response);
        echo $res;

        break;

    case "/api/post":
        $requestData = json_decode(file_get_contents("php://input"), true);

        if ($requestData) {
            $response = [
                "status" => 200,
                "statusText" => "OK",
                "message" => "Posted Data Success",
                "data" => $requestData,
            ];
        }

        http_response_code($response["status"]);
        header("Content-type: application/json");

        $res = json_encode($response);
        echo $res;

        break;

    default:
        header("HTTP/1.1 400 Bad Request");
        break;
}
