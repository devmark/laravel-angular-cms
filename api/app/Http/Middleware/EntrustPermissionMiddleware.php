<?php namespace App\Http\Middleware;

use UserAuth;
use App\Exceptions\UnauthorizedException;

class EntrustPermissionMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure                  $next
     * @param                           $permissions
     *
     * @return mixed
     */
    public function handle($request, \Closure $next, $permissions)
    {

        if (!UserAuth::check() || !UserAuth::user()->can(explode('|', $permissions))) {
            throw new UnauthorizedException('You do not have permission to access.');
        }

        return $next($request);
    }
}