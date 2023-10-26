<?php

use JWT\Token\Token;

session_start();

chdir(dirname(__DIR__));

$response = [];

// CHECK IF ACCESS TOKEN IS SET
if (!isset($_COOKIE['access_token']))
{ //if token is not set
    $response = [
        'status' => 401,
        'statusText' => 'Unauthorized',
        'message' => 'User is not authorized to access this function or page.'
    ];

    return $response;
}
else
{
    $user_id      = $_SESSION['user_id'] = $_COOKIE['user_id'];
    $access_token = $_SESSION['access_token'] = $_COOKIE['access_token'];
}

// CONNECT TO DATABASE
$db         = new DB();
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

// if check user from database successful
$user = $user_query['query']->fetch_object();

Token::verify($access_token, $user->access_token, $user->email);