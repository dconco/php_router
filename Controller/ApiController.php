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
 * @method mixed Logout()
 * @method mixed Profile()
 */
class Endpoint {

    private static $method = REQUEST_METHOD;
    private static $response = [];

    /**
     * REGISTER ENDPOINT
     */
    public static function Register() {

        if (self::$method !== 'POST') 
        {
            self::$response = [
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
                self::$response = [
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
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END REGISTER ENDPOINT

    
    /**
     * LOGIN ENDPOINT
     */
    public static function Login() {
        
        if (self::$method !== 'POST') 
        {
            self::$response = [
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
                self::$response = [
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
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END LOGIN ENDPOINT
    
    
    /**
     * LOGOUT ENDPOINT
     */
    public static function Logout() {
        session_start();
        session_destroy();
        
        if (isset($_COOKIE["user_id"])) {
            setcookie('user_id', '', time() - (86400 * 30), '/');
            header("location: ./login.php");
        }
        if (isset($_COOKIE["access_token"])) {
            setcookie('access_token', '', time() - (86400 * 30), '/');
            header("location: ./login.php");
        }

        self::$response = [
            'status' => 200,
            'statusText' => 'OK',
            'message' => 'User Logged out success.',
        ];
        
        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END LOGOUT ENDPOINT


    /**
     * PROFILE INFO ENDPOINT
     */
    public static function Profile() {
        if (self::$method !== "GET") 
        {
            self::$response = [
                "status" => 405,
                "statusText" => "Method Not Allowed",
                "message" => "The method used in the request is not allowed for the resource.",
            ];
        }
        else
        {
            global $req;
            $req_id = $req['user_id'];
            include_once 'Auth/profile_auth.php';
        }
        
        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END PROFILE ENDPOINT 

}