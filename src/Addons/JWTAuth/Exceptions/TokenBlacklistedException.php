<?php

namespace Lia\Addons\JWTAuth\Exceptions;

class TokenBlacklistedException extends TokenInvalidException
{
    /**
     * @var int
     */
    protected $statusCode = 401;
}
