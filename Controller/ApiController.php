<?php

namespace Dconco\Api;

/**
 * HANDLING EACH API ENDPOINTS
 * 
 * @author DaveConco <concodave@gmail.com>
 *
 */
class Endpoint
{

    private static $method = REQUEST_METHOD;
    private static $response = [];

    /**
     * REGISTER ENDPOINT
     */
    public static function Register()
    {

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
            (empty($req_data['fullname']) || empty($req_data['email']) || empty($req_data['password'])) ||
            strlen($req_data['password']) < 5 || !filter_var($req_data['email'], FILTER_VALIDATE_EMAIL)
            )
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
    public static function Login()
    {

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
            )
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
    public static function Logout()
    {
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
            session_start();
            session_destroy();

            setcookie('user_id', '', time() - (86400 * 30), '/');
            setcookie('access_token', '', time() - (86400 * 30), '/');

            self::$response = [
                'status' => 200,
                'statusText' => 'OK',
                'message' => 'User Logged out success.'
            ];
        }

        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END LOGOUT ENDPOINT


    /**
     * PROFILE INFO ENDPOINT
     * Get other users profile details, and get logged-in user details
     */
    public static function Profile($user_id)
    {
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
            $user_id = (int)$user_id;

            if ($user_id == $_SESSION['user_id'])
            { // user_id is logged-in user
                include_once 'Controller/AuthController.php';

                if ($response['status'] === 200)
                {
                    include_once 'Auth/profile_auth.php';
                }
                else
                {
                    self::$response = $response;
                }
            }
            else
            {
                // include_once 'Auth/get_user_auth.php';
            }
        }

        /* RETURN RESPONSE TEXT */
        header('HTTP/1.1 ' . self::$response['status'] . ' ' . self::$response['statusText']);

        $res = json_encode(self::$response);
        return $res;
    }
    // END PROFILE ENDPOINT 

}