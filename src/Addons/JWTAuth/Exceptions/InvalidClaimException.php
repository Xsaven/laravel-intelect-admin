<?php

namespace Lia\Addons\JWTAuth\Exceptions;

class InvalidClaimException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 400;
}
