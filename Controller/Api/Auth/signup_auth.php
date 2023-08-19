<?php
$db = new DB();

$user_id = "";
for ($i = 0; $i < 2; $i++) {
    $id = rand(000000000, 999999999);
    $user_id .= $id;
}

$user_data = [
    "user_id" => $user_id,
    "name" => $user["name"],
    "email" => $user["email"],
    "password" => password_hash($user["password"], PASSWORD_BCRYPT),
];

$send = $db->POST("users", $user_data);

$response = $send;
