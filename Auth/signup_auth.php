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
    "email" => $user["email"],
    "secret_key" => $secret_key,
    "password" => password_hash($user["password"], PASSWORD_DEFAULT),
];
array_push($jwt_data['data'], ["user_id" => $user_id]);

$send = $db->POST("users", $user_data);
$response = $send;