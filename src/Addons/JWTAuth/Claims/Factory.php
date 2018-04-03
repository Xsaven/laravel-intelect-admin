<?php

namespace Lia\Addons\JWTAuth\Claims;

class Factory
{
    /**
     * @var array
     */
    private static $classMap = [
        'aud' => 'Lia\Addons\JWTAuth\Claims\Audience',
        'exp' => 'Lia\Addons\JWTAuth\Claims\Expiration',
        'iat' => 'Lia\Addons\JWTAuth\Claims\IssuedAt',
        'iss' => 'Lia\Addons\JWTAuth\Claims\Issuer',
        'jti' => 'Lia\Addons\JWTAuth\Claims\JwtId',
        'nbf' => 'Lia\Addons\JWTAuth\Claims\NotBefore',
        'sub' => 'Lia\Addons\JWTAuth\Claims\Subject',
    ];

    /**
     * Get the instance of the claim when passing the name and value.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @return \Lia\Addons\JWTAuth\Claims\Claim
     */
    public function get($name, $value)
    {
        if ($this->has($name)) {
            return new self::$classMap[$name]($value);
        }

        return new Custom($name, $value);
    }

    /**
     * Check whether the claim exists.
     *
     * @param  string  $name
     * @return bool
     */
    public function has($name)
    {
        return array_key_exists($name, self::$classMap);
    }
}
