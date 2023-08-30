<?php
$method = $_SERVER["REQUEST_METHOD"];
$uri = $_SERVER["REQUEST_URI"];
$url = explode("?", $uri);
$req_uri = preg_replace("/(^\/)|(\/$)/", "", $url[0]);

require "Models/database.php";
echo $req_uri;

switch ($req_uri) {
    case "api/account/register":
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

            if (
                empty($requestData["name"]) ||
                empty($requestData["email"]) ||
                empty($requestData["password"])
            ) {
                $response = [
                    "status" => 400,
                    "statusText" => "Bad Request",
                    "message" => "Invalid Request Body",
                ];
            } else {
                $user = [
                    "name" => htmlspecialchars($requestData["name"]),
                    "email" => htmlspecialchars($requestData["email"]),
                    "password" => htmlspecialchars($requestData["password"]),
                ];

                require __DIR__ . "/Auth/signup_auth.php";
            }
        }

        /* RETURN RESPONSE TEXT */
        http_response_code($response["status"]);
        header("Content-Type: application/json");

        $res = json_encode($response);
        echo $res;

        break;
    /* // END REGISTER ENDPOINT */

    /* // GET USER ENDPOINT */
    case "api/user/{$req["user_id"]}":
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
            include_once __DIR__ . "/Auth/get_user_auth.php";
        }

        /* RETURN RESPONSE TEXT */
        header("HTTP/1.1 {$response["status"]} {$response["statusText"]}");
        header("Content-type: application/json");

        $res = json_encode($response);
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
        break;
}
