<?php

declare(strict_types=1);
use Firebase\JWT\JWT;

require_once '../vendor/autoload.php';
require_once '../secret_key.php';

$secret_key = secret_key_generate(32);

$date = new DateTimeImmutable();

$expire_at = $date->modify('+6 minutes')->getTimestamp();
$server_url = 'localhost';
$username = 'username';

$jwt_data = [
    'iss' => $server_url,
    'iat' => $date->getTimestamp(),
    'nbf' => $date->getTimestamp(),
    'exp' => $expire_at,
    'data' => []
];


echo JWT::encode(
    $jwt_data,
    $secret_key,
    'HS512'
);