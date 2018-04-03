<?php

namespace Lia\Addons\JWTAuth;

use Lia\Addons\JWTAuth\Exceptions\JWTException;
use Lia\Addons\JWTAuth\Providers\JWT\JWTInterface;
use Lia\Addons\JWTAuth\Exceptions\TokenBlacklistedException;

class JWTManager
{
    /**
     * @var \Lia\Addons\JWTAuth\Providers\JWT\JWTInterface
     */
    protected $jwt;

    /**
     * @var \Lia\Addons\JWTAuth\Blacklist
     */
    protected $blacklist;

    /**
     * @var \Lia\Addons\JWTAuth\PayloadFactory
     */
    protected $payloadFactory;

    /**
     * @var bool
     */
    protected $blacklistEnabled = true;

    /**
     * @var bool
     */
    protected $refreshFlow = false;

    /**
     *  @param \Lia\Addons\JWTAuth\Providers\JWT\JWTInterface  $jwt
     *  @param \Lia\Addons\JWTAuth\Blacklist  $blacklist
     *  @param \Lia\Addons\JWTAuth\PayloadFactory  $payloadFactory
     */
    public function __construct(JWTInterface $jwt, Blacklist $blacklist, PayloadFactory $payloadFactory)
    {
        $this->jwt = $jwt;
        $this->blacklist = $blacklist;
        $this->payloadFactory = $payloadFactory;
    }

    /**
     * Encode a Payload and return the Token.
     *
     * @param  \Lia\Addons\JWTAuth\Payload  $payload
     * @return \Lia\Addons\JWTAuth\Token
     */
    public function encode(Payload $payload)
    {
        $token = $this->jwt->encode($payload->get());

        return new Token($token);
    }

    /**
     * Decode a Token and return the Payload.
     *
     * @param  \Lia\Addons\JWTAuth\Token $token
     * @return Payload
     * @throws TokenBlacklistedException
     */
    public function decode(Token $token)
    {
        $payloadArray = $this->jwt->decode($token->get());

        $payload = $this->payloadFactory->setRefreshFlow($this->refreshFlow)->make($payloadArray);

        if ($this->blacklistEnabled && $this->blacklist->has($payload)) {
            throw new TokenBlacklistedException('The token has been blacklisted');
        }

        return $payload;
    }

    /**
     * Refresh a Token and return a new Token.
     *
     * @param  \Lia\Addons\JWTAuth\Token  $token
     * @return \Lia\Addons\JWTAuth\Token
     */
    public function refresh(Token $token)
    {
        $payload = $this->setRefreshFlow()->decode($token);

        if ($this->blacklistEnabled) {
            // invalidate old token
            $this->blacklist->add($payload);
        }

        // return the new token
        return $this->encode(
            $this->payloadFactory->make([
                'sub' => $payload['sub'],
                'iat' => $payload['iat'],
            ])
        );
    }

    /**
     * Invalidate a Token by adding it to the blacklist.
     *
     * @param  Token  $token
     * @return bool
     */
    public function invalidate(Token $token)
    {
        if (! $this->blacklistEnabled) {
            throw new JWTException('You must have the blacklist enabled to invalidate a token.');
        }

        return $this->blacklist->add($this->decode($token));
    }

    /**
     * Get the PayloadFactory instance.
     *
     * @return \Lia\Addons\JWTAuth\PayloadFactory
     */
    public function getPayloadFactory()
    {
        return $this->payloadFactory;
    }

    /**
     * Get the JWTProvider instance.
     *
     * @return \Lia\Addons\JWTAuth\Providers\JWT\JWTInterface
     */
    public function getJWTProvider()
    {
        return $this->jwt;
    }

    /**
     * Get the Blacklist instance.
     *
     * @return \Lia\Addons\JWTAuth\Blacklist
     */
    public function getBlacklist()
    {
        return $this->blacklist;
    }

    /**
     * Set whether the blacklist is enabled.
     *
     * @param bool  $enabled
     */
    public function setBlacklistEnabled($enabled)
    {
        $this->blacklistEnabled = $enabled;

        return $this;
    }

    /**
     * Set the refresh flow.
     *
     * @param bool $refreshFlow
     * @return $this
     */
    public function setRefreshFlow($refreshFlow = true)
    {
        $this->refreshFlow = $refreshFlow;

        return $this;
    }
}
