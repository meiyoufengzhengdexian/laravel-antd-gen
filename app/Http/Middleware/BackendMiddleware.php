<?php

namespace App\Http\Middleware;

use App\Http\Backend\BackendPermissionException;
use App\Http\Backend\BackendRequestAuthException;
use Closure;
use Illuminate\Support\Facades\Auth;
use League\OAuth2\Server\ResourceServer;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

class BackendMiddleware
{
    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    protected $server;

    /**
     * The authentication factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \League\OAuth2\Server\ResourceServer $server
     * @return void
     */
    public function __construct(ResourceServer $server, AuthFactory $auth)
    {
        $this->server = $server;
        $this->auth = $auth;

    }


    /**
     * @param $request
     * @param Closure $next
     * @param array $scopes
     * @return mixed
     * @throws BackendPermissionException
     * @throws BackendRequestAuthException
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $this->authenticate(['api']);
        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            throw new BackendRequestAuthException('登录凭证已过期', -3);
        }

        $this->validateScopes($psr, $scopes);

        return $next($request);
    }

    /**
     * Validate the scopes on the incoming request.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $psr
     * @param  array $scopes
     * @return void
     * @throws BackendPermissionException
     */
    protected function validateScopes($psr, $scopes)
    {
        if (in_array('*', $tokenScopes = $psr->getAttribute('oauth_scopes'))) {
            return;
        }

        foreach ($scopes as $scope) {
            if (!in_array($scope, $tokenScopes)) {
                throw new BackendPermissionException($scope);
            }
        }
    }


    /**
     * 登录
     * @param array $guards
     * @throws BackendRequestAuthException
     */
    protected function authenticate(array $guards)
    {
        if (empty($guards)) {
            return $this->auth->authenticate();
        }

        foreach ($guards as $guard) {
            if ($this->auth->guard($guard)->check()) {
                return $this->auth->shouldUse($guard);
            }
        }

        throw new BackendRequestAuthException('登录凭证已过期.', -3);
    }
}
