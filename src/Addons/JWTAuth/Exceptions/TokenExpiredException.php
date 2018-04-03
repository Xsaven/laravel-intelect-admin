<?php

namespace Lia\Addons\JWTAuth\Exceptions;

class TokenExpiredException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 401;
}
