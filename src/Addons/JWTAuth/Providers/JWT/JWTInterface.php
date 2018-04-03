<?php

namespace Lia\Addons\JWTAuth\Providers\JWT;

interface JWTInterface
{
    /**
     * @param  array  $payload
     * @return string
     */
    public function encode(array $payload);

    /**
     * @param  string  $token
     * @return array
     */
    public function decode($token);
}
