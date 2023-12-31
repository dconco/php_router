<?php

namespace Dconco\Token;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;

/**
 * HANDLING JWT WEB TOKEN
 * Create new JWT web token for use
 * Verify token
 * 
 * @author DaveConco <concodave@gmail.com>
 * @link https://github.com/dconco/php_router
 * @category generate_token
 * @package token
 * @version ${1:1.0.0}
 * @return void
 * @method array create()
 * @method array verify()
 */

class Token
{
    /**
     * Create new JWT Web Token
     */
    static function create(array $data = [])
    {
        $secret_key = secret_key_generate(32);

        $date = new DateTimeImmutable();

        $expire_at = $date->modify('+24 hours')->getTimestamp();

        $jwt_data = [
            'iss' => getenv('APP_SERVER'),
            'iat' => $date->getTimestamp(),
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
    static function verify($access_token, $secret_key, $email)
    {
        try
        {
            $decodedToken = JWT::decode(
                $access_token,
                new Key($secret_key, 'HS512')
            );

            $now = new DateTimeImmutable();

            if ($decodedToken->iss !== getenv('APP_SERVER') || $decodedToken->data->email !== $email || $decodedToken->iat > $now->getTimestamp() || $decodedToken->exp < $now->getTimestamp())
            {
                $response = [
                    'status' => 403,
                    'statusText' => 'Forbidden',
                    'message' => 'Invalid or Expired Token.'
                ];

                return $response;
            }

            $response = [
                'status' => 200,
                'statusText' => 'OK',
                'message' => 'User is authorized'
            ];

            return $response;
        }
        catch ( Exception $e )
        {
            $response = [
                'status' => 500,
                'statusText' => 'Internal Server Error',
                'message' => $e->getMessage()
            ];

            return $response;
        }
    }
}