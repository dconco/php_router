<?php

namespace JWT\Token;
use Firebase\JWT\JWT;
use DateTimeImmutable;

/**
 * HANDLING JWT WEB TOKEN
 * Create new JWT web token for use
 * Verify token
 * 
 * @author DaveConco <concodave@gmail.com>
 * @method array create()
 */

class Token {
    /**
     * Create new JWT Web Token
     */
    static function create(array $data = []) {
        $secret_key = secret_key_generate(32);

        $date = new DateTimeImmutable();

        $expire_at = $date->modify('+6 minutes')->getTimestamp();

        $jwt_data = [
            'iss' => getenv('SERVER_HOST'),
            'iat' => $date->getTimestamp(),
            'nbf' => $date->getTimestamp(),
            'exp' => $expire_at,
            'data' => $data
        ];

        $token = JWT::encode(
            $jwt_data,
            $secret_key,
            'HS512'
        );

        putenv("JWT_SECRET_KEY=$secret_key");
        putenv("JWT_SECRET_TOKEN=$token");

        $token_info = [
            'access_token' => $token,
            'access_key' => $secret_key
        ];
        return $token_info;
    }


    /**
     * Verify JWT Web Token
     * Check if the user is authenticated
     */
}