<?php

session_start();

declare(strict_types=1);
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

chdir(dirname(__DIR__));

require_once './vendor/autoload.php';
require_once './Models/database.php';
require_once './secret_key.php';
require_once './env.php';

// CHECK IF ACCESS TOKEN IS SET
$response = [];

if (!isset($_COOKIE['access_token'])) 
{ //if token is not set
    $response = [
        'status' => 401,
        'statusText' => 'Unauthorized',
        'message' => 'User is not authorized to access this function or page.'
    ];
    
    // header('location: /projects/php_router/login');
    return $response;
} 
else
{
    $user_id = $_SESSION['user_id'] = $_COOKIE['user_id'];
    $access_token = $_SESSION['access_token'] = $_COOKIE['access_token'];
}

$db = new DB();
$user_query = $db->GET("users", "access_token", "WHERE user_id=$user_id");
$user = $user_query['query']->fetch_object();

$decodedToken = JWT::decode(
    $access_token, 
    new Key($user->access_token, 'HS512')
);

$now = new DateTimeImmutable();

if ($decodedToken->iss !== getenv('SERVER') || $decodedToken->aud !== $user->email || $decodedToken->nbf > $now->getTimestamp() || $decodedToken->exp < $now->getTimestamp()) 
{
    $response = [
        'status' => 403,
        'statusText' => 'Forbidden',
        'message' => 'Invalid or Expired Token.'
    ];

    return $response;
}
else 
{
    $response = [
        'status' => 200,
        'statusText' => 'OK',
        'message' => 'User is authorized'
    ];

    return $response;
}