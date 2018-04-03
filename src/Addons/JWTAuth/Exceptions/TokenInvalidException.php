<?php

namespace Lia\Addons\JWTAuth\Exceptions;

class TokenInvalidException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 400;
}
