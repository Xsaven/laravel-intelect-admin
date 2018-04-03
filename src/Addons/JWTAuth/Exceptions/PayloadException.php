<?php

namespace Lia\Addons\JWTAuth\Exceptions;

class PayloadException extends JWTException
{
    /**
     * @var int
     */
    protected $statusCode = 500;
}
