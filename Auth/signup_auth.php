<?php

include_once 'Auth/jwt_auth.php';

$db = new DB();

$user_id = "";
for ($i = 0; $i < 2; $i++) {
    $id = rand(time(), 99999999);
    $user_id .= $id;
}

$user_data = [
    "user_id" => $user_id,
    "fullname" => $user["fullname"],
    "email" => base64_encode($user["email"]),
    "access_token" => getenv('JWT_SECRET_KEY'),
    "password" => password_hash($user["password"], PASSWORD_DEFAULT),
];

$jwt_data['aud'] = $user_data["user_id"];

$dbResponse = $db->REGISTER_USER($user_data);
$response = $dbResponse;