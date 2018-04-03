<?php

namespace Lia\Addons\JWTAuth\Validators;

use Lia\Addons\JWTAuth\Exceptions\TokenInvalidException;

class TokenValidator extends AbstractValidator
{
    /**
     * Check the structure of the token.
     *
     * @param string  $value
     * @return void
     */
    public function check($value)
    {
        $this->validateStructure($value);
    }

    /**
     * @param  string  $token
     * @throws \Lia\Addons\JWTAuth\Exceptions\TokenInvalidException
     * @return bool
     */
    protected function validateStructure($token)
    {
        if (count(explode('.', $token)) !== 3) {
            throw new TokenInvalidException('Wrong number of segments');
        }

        return true;
    }
}
