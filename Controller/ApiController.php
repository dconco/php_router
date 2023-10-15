<?php

namespace Api;

/**
 * HANDLING EACH API ENDPOINTS
 * 
 * @author DaveConco <concodave@gmail.com>
 * @method mixed $method
 * @method mixed $response
 * @method mixed Register()
 * @method mixed Login()
 */
class Endpoint {

    static $method = $_SERVER['REQUEST_METHOD'];
    static $response = [];

    /**
     * REGISTER ENDPOINT
     */
    static function Register() {

        if (Endpoint::$method !== 'POST') 
        {
            Endpoint::$response = [
                'status' => 405,
                'statusText' => 'Method Not Allowed',
                'message' => 'The method used in the request is not allowed for the resource.',
            ];
        }
        else 
        {
            /* MAIN ENDPOINT */
            $req_data = json_decode(file_get_contents('php://input'), true);

            if ((empty($req_data['fullname']) || empty($req_data['email']) || empty($req_data['password'])) || 
                strlen($req_data['password']) < 5 || !filter_var($req_data['email'], FILTER_VALIDATE_EMAIL)) 
            {
                Endpoint::$response = [
                    'status' => 400,
                    'statusText' => 'Bad Request',
                    'message' => 'Invalid Request Body',
                ];
            } 
            else 
            {
                $user = [
                    'fullname' => htmlspecialchars($req_data['fullname']),
                    'email' => htmlspecialchars($req_data['email']),
                    'password' => htmlspecialchars($req_data['password']),
                ];

                include_once 'Auth/signup_auth.php';
            }
        }

        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . Endpoint::$response['status'] . ' ' . Endpoint::$response['statusText']);

        $res = json_encode(Endpoint::$response);
        return $res;
    }
    // END REGISTER ENDPOINT

    
    /**
     * LOGIN ENDPOINT
     */
    static function Login() {
        
        if (Endpoint::$method !== 'POST') 
        {
            Endpoint::$response = [
                'status' => 405,
                'statusText' => 'Method Not Allowed',
                'message' => 'The method used in the request is not allowed for the resource.',
            ];
        }
        else 
        {
            /* MAIN ENDPOINT */
            $req_data = json_decode(file_get_contents('php://input'), true);

            if (
                empty($req_data['email']) ||
                empty($req_data['password'])
            ) {
                Endpoint::$response = [
                    'status' => 400,
                    'statusText' => 'Bad Request',
                    'message' => 'Invalid Request Body',
                ];
            } else {
                $user = [
                    'email' => htmlspecialchars($req_data['email']),
                    'password' => htmlspecialchars($req_data['password']),
                ];

                require 'Auth/login_auth.php';
            }
        }

        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . Endpoint::$response['status'] . ' ' . Endpoint::$response['statusText']);

        $res = json_encode(Endpoint::$response);
        return $res;
    }
    // END LOGIN ENDPOINT


    /**
     * PROFILE INFO ENDPOINT
     */
    static function Profile() {
        if (Endpoint::$method !== "GET") 
        {
            Endpoint::$response = [
                "status" => 405,
                "statusText" => "Method Not Allowed",
                "message" => "The method used in the request is not allowed for the resource.",
            ];
        }
        else
        {

            $req_id = $req['user_id'];
            include_once 'Auth/profile_auth.php';
        }
        
        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . Endpoint::$response['status'] . ' ' . Endpoint::$response['statusText']);

        $res = json_encode(Endpoint::$response);
        return $res;
    }
    // END PROFILE ENDPOINT 

}