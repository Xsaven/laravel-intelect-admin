<?php

namespace Lia\Addons\JWTAuth\Validators;

use Lia\Addons\JWTAuth\Utils;
use Lia\Addons\JWTAuth\Exceptions\TokenExpiredException;
use Lia\Addons\JWTAuth\Exceptions\TokenInvalidException;

class PayloadValidator extends AbstractValidator
{
    /**
     * @var array
     */
    protected $requiredClaims = ['iss', 'iat', 'exp', 'nbf', 'sub', 'jti'];

    /**
     * @var int
     */
    protected $refreshTTL = 20160;

    /**
     * Run the validations on the payload array.
     *
     * @param  array  $value
     * @return void
     */
    public function check($value)
    {
        $this->validateStructure($value);

        if (! $this->refreshFlow) {
            $this->validateTimestamps($value);
        } else {
            $this->validateRefresh($value);
        }
    }

    /**
     * Ensure the payload contains the required claims and
     * the claims have the relevant type.
     *
     * @param array  $payload
     * @throws \Lia\Addons\JWTAuth\Exceptions\TokenInvalidException
     * @return bool
     */
    protected function validateStructure(array $payload)
    {
        if (count(array_diff($this->requiredClaims, array_keys($payload))) !== 0) {
            throw new TokenInvalidException('JWT payload does not contain the required claims');
        }

        return true;
    }

    /**
     * Validate the payload timestamps.
     *
     * @param  array  $payload
     * @throws \Lia\Addons\JWTAuth\Exceptions\TokenExpiredException
     * @throws \Lia\Addons\JWTAuth\Exceptions\TokenInvalidException
     * @return bool
     */
    protected function validateTimestamps(array $payload)
    {
        if (isset($payload['nbf']) && Utils::timestamp($payload['nbf'])->isFuture()) {
            throw new TokenInvalidException('Not Before (nbf) timestamp cannot be in the future', 400);
        }

        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->isFuture()) {
            throw new TokenInvalidException('Issued At (iat) timestamp cannot be in the future', 400);
        }

        if (Utils::timestamp($payload['exp'])->isPast()) {
            throw new TokenExpiredException('Token has expired');
        }

        return true;
    }

    /**
     * Check the token in the refresh flow context.
     *
     * @param  $payload
     * @return bool
     */
    protected function validateRefresh(array $payload)
    {
        if (isset($payload['iat']) && Utils::timestamp($payload['iat'])->addMinutes($this->refreshTTL)->isPast()) {
            throw new TokenExpiredException('Token has expired and can no longer be refreshed', 400);
        }

        return true;
    }

    /**
     * Set the required claims.
     *
     * @param array  $claims
     */
    public function setRequiredClaims(array $claims)
    {
        $this->requiredClaims = $claims;

        return $this;
    }

    /**
     * Set the refresh ttl.
     *
     * @param int  $ttl
     */
    public function setRefreshTTL($ttl)
    {
        $this->refreshTTL = $ttl;

        return $this;
    }
}
