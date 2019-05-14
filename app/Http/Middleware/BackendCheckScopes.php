<?php

namespace App\Http\Middleware;

use App\Http\Backend\BackendPermissionException;
use App\Http\Backend\BackendRequestAuthException;
use Closure;

class BackendCheckScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param array $scopes
     * @return mixed
     * @throws BackendPermissionException
     * @throws BackendRequestAuthException
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        if (!$request->user() || !$request->user()->token()) {
            throw new BackendRequestAuthException('登录凭证已过期', -3);
        }

        foreach ($scopes as $scope) {
            if (!$request->user()->tokenCan($scope)) {
                throw new BackendPermissionException("需要权限： ".$scope);
            }
        }

        return $next($request);
    }
}
