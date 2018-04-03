<?php

namespace Lia\Addons\JWTAuth\Claims;

class NotBefore extends Claim
{
    /**
     * The claim name.
     *
     * @var string
     */
    protected $name = 'nbf';

    /**
     * Validate the not before claim.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function validate($value)
    {
        return is_numeric($value);
    }
}
