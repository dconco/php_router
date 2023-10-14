<?php

if (! preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) 
{
    header('HTTP/1.0 400 Bad Request');
    echo 'Token not found in request';
    exit;
}

$jwt = $matches[1];

if (!$jwt) 
{
    // No token was able to be extracted from the authorization header
    header('HTTP/1.0 400 Bad Request');
    exit;
}
else 
{
    $secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $decodedToken = JWT::decode(
        $jwt, 
        new Key($secretKey, 'HS512')
    );
    
    $now = new DateTimeImmutable();
    $server_url = 'localhost';

    if ($decodedToken->iss !== $server_url || $decodedToken->nbf > $now->getTimestamp() || $decodedToken->exp < $now->getTimestamp()) 
    {
        header('HTTP/1.0 401 Unauthorized');
        exit;
    }
}