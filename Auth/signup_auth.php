<?php

declare(strict_types=1);
use JWT\Token\Token;

include_once 'Token.php';
require_once 'vendor/autoload.php';
require_once 'secret_key.php';
require_once 'env.php';


$db = new DB();

$user_id = "";
$user_email = base64_encode($user["email"]);

for ($i = 0; $i < 2; $i++) {
    $id = rand(time(), 99999999);
    $user_id .= $id;
}

$data = [
    'email' => $user_email
];

$token = Token::create($data);

$user_data = [
    "user_id" => $user_id,
    "fullname" => $user["fullname"],
    "email" => $user_email,
    "access_token" => $token['access_key'],
    "password" => password_hash($user["password"], PASSWORD_DEFAULT),
];

$dbResponse = $db->REGISTER_USER($user_data);
$response = $dbResponse;