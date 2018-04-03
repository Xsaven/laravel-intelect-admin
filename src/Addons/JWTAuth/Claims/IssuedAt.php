<?php

namespace Lia\Addons\JWTAuth\Claims;

class IssuedAt extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'iat';

    /**
     * Validate the issued at claim.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function validate($value)
    {
        return is_numeric($value);
    }
}
