<?php namespace App\Http\Middleware;

use UserAuth;
use App\Exceptions\UnauthorizedException;

class EntrustAbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param                          $roles
     * @param                          $permissions
     * @param bool                     $validateAll
     * @return mixed
     */
    public function handle($request, \Closure $next, $roles, $permissions, $validateAll = false)
    {

        if (!UserAuth::user()->ability(explode('|', $roles), explode('|', $permissions), ['validate_all' => $validateAll])) {
            throw new UnauthorizedException('You do not have permission to access.');
        }

        return $next($request);
    }
}