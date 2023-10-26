<?php

use JWT\Token\Token;

$db = new DB();

$user_id    = rand(time(), 99999999) . rand(99999999, time());
$user_email = base64_encode($user["email"]);

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

$dbResponse     = $db->REGISTER_USER($user_data);
self::$response = $dbResponse;