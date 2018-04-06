<?php

namespace Lia\Addons\JWTAuth\Middleware;

use Lia\Addons\JWTAuth\Exceptions\JWTException;
use Lia\Addons\JWTAuth\Exceptions\TokenExpiredException;

class GetUserFromToken extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('lia.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('lia.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('lia.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->respond('lia.jwt.user_not_found', 'user_not_found', 404);
        }

        \Auth::guard('admin')->login($user);

        $this->events->fire('lia.jwt.valid', $user);

        return $next($request);
    }
}
