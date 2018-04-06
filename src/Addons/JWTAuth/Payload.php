<?php

namespace Lia\Addons\JWTAuth;

use Lia\Addons\JWTAuth\Claims\Claim;
use Lia\Addons\JWTAuth\Exceptions\PayloadException;
use Lia\Addons\JWTAuth\Validators\PayloadValidator;

class Payload implements \ArrayAccess
{
    /**
     * The array of claims.
     *
     * @var \Lia\Addons\JWTAuth\Claims\Claim[]
     */
    private $claims = [];

    /**
     * Build the Payload.
     *
     * @param array  $claims
     * @param \Lia\Addons\JWTAuth\Validators\PayloadValidator  $validator
     * @param bool   $refreshFlow
     */
    public function __construct(array $claims, PayloadValidator $validator, $refreshFlow = false)
    {
        $this->claims = $claims;

        $validator->setRefreshFlow($refreshFlow)->check($this->toArray());
    }

    /**
     * Get the array of claim instances.
     *
     * @return \Lia\Addons\JWTAuth\Claims\Claim[]
     */
    public function getClaims()
    {
        return $this->claims;
    }

    /**
     * Get the array of claims.
     *
     * @return array
     */
    public function toArray()
    {
        $results = [];
        foreach ($this->claims as $claim) {
            $results[$claim->getName()] = $claim->getValue();
        }

        return $results;
    }

    /**
     * Get the payload.
     *
     * @param  string  $claim
     * @return mixed
     */
    public function get($claim = null)
    {
        if (! is_null($claim)) {
            if (is_array($claim)) {
                return array_map([$this, 'get'], $claim);
            }

            return array_get($this->toArray(), $claim, false);
        }

        return $this->toArray();
    }

    /**
     * Determine whether the payload has the claim.
     *
     * @param  \Lia\Addons\JWTAuth\Claims\Claim  $claim
     * @return bool
     */
    public function has(Claim $claim)
    {
        return in_array($claim, $this->claims);
    }

    /**
     * Get the payload as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->toArray());
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return array_get($this->toArray(), $key, []);
    }

    /**
     * Don't allow changing the payload as it should be immutable.
     *
     * @param  mixed $key
     * @param  mixed $value
     * @throws Exceptions\PayloadException
     * @return void
     */
    public function offsetSet($key, $value)
    {
        throw new PayloadException('The payload is immutable');
    }

    /**
     * Don't allow changing the payload as it should be immutable.
     *
     * @param  string $key
     * @throws Exceptions\PayloadException
     * @return void
     */
    public function offsetUnset($key)
    {
        throw new PayloadException('The payload is immutable');
    }

    /**
     * Magically get a claim value.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this, $method) && starts_with($method, 'get')) {
            $class = sprintf('Lia\\Addons\\JWTAuth\\Claims\\%s', substr($method, 3));

            foreach ($this->claims as $claim) {
                if (get_class($claim) === $class) {
                    return $claim->getValue();
                }
            }
        }

        throw new \BadMethodCallException(sprintf('The claim [%s] does not exist on the payload.', $method));
    }
}