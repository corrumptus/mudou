<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use TypeError;

class JWTHelper
{
    /**
     * Cria (encode) um token JWT para um usuário.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @return string|null
     */
    public static function encode(object $user): ?string
    {
        try {
            return JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            return null;
        }
    }

    /**
     * Decodifica (decode) um token JWT e retorna o payload.
     *
     * @param string $token
     * @return object|null
     */
    public static function decode(string | null $token): ?object
    {
        try {
            return JWTAuth::setToken($token)->toUser();
        } catch (TypeError | JWTException $e) {
            return null;
        }
    }
}
