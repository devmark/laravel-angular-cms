<?php namespace App\Http\Middleware;

use UserAuth;
use App\Exceptions\UnauthorizedException;


class EntrustRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @param                           $roles
     * @return mixed
     */
    public function handle($request, \Closure $next, $roles)
    {
        if (!UserAuth::user()->hasRole(explode('|', $roles))) {
            throw new UnauthorizedException('You do not have permission to access.');
        }

        return $next($request);
    }
}