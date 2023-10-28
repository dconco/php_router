<?php

use Dconco\Token\Token;

chdir(dirname(__DIR__));

$response = [];
$user_id = $_SESSION['user_id'] = $_COOKIE['user_id'];

try
{
    if (!preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches))
    {
        $response = [
            'status' => 400,
            'statusText' => 'Bad Request',
            'message' => 'Token not found in request'
        ];

        return $response;
    }

    $access_token = $matches[1];
}
catch ( Exception $e )
{
    $response = [
        'status' => 401,
        'statusText' => 'Unauthorized',
        'message' => $e->getMessage()
    ];

    return $response;
}


// CONNECT TO DATABASE
$db = new DB();
$user_query = $db->GET("users", "access_token, email", "user_id = $user_id"); // get the user access_token from database

// if error while trying to check user from database
if ($user_query['status'] !== 200 && $user_query['query']->num_rows <= 0)
{
    $response = [
        'status' => 401,
        'statusText' => 'Unauthorized',
        'message' => 'User is not authorized to access this function or page.'
    ];

    return $response;
}

// verify token
$user = $user_query['query']->fetch_object();

$response = Token::verify($access_token, $user->access_token, $user->email);
return $response;