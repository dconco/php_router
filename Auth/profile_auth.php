<?php

$db = new DB();

$result     = $db->GET("users", "id, user_id, fullname, reg_date", "user_id = {$_SESSION['user_id']}");
$user_email = $db->GET("users", "email", "user_id = {$_SESSION['user_id']}");

if ($result['status'] === 200 && $user_email['status'] === 200)
{
    $user       = $result['query']->fetch_array();
    $user_email = [ 'email' => base64_decode($user_email['query']->fetch_array()["email"]) ];

    $response = array_merge($user, $user_email);

    $response = [
        'status' => 200,
        'statusText' => 'OK',
        'data' => (object) $response,
        'message' => 'Get user successfully'
    ];
}
else
{
    $response = [
        'status' => 500,
        'statusText' => 'Internal Server Error',
        'message' => 'Not able to complete the request'
    ];
}

self::$response = $response;