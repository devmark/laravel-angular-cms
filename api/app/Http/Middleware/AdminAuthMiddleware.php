<?php namespace App\Http\Middleware;

use Closure;
use Response;
use Event;
use Auth;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use App\Models\Admin;
use AdminAuth;

class AdminAuthMiddleware
{
    /**
     * @var \Tymon\JWTAuth\Facades\JWT
     */
    private $jwt;

    /**
     * Create a new BaseMiddleware instance
     *
     * @param \Laravel\Lumen\Http\ResponseFactory $response
     * @param \Illuminate\Events\Dispatcher       $events
     * @param \Tymon\JWTAuth\Facades\JWT          $jwt
     */
    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        if (!$token = $this->jwt->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }
        try {
            $payload = $this->jwt->decode($token);

            if ($payload['aud'] == 'admin') {
                $user = Admin::find($payload['sub']);
                AdminAuth::loginUsingId($user->id);
            }
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }
        if (is_null($user)) {
            return $this->respond('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        Event::fire('tymon.jwt.valid', $user);

        return $next($request);
    }

    /**
     * Fire event and return the response
     *
     * @param  string  $event
     * @param  string  $error
     * @param  integer $status
     * @param  array   $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = Event::fire($event, $payload, true);

        return $response ?: Response::json([
            'status'      => false,
            'status_code' => $status,
            'message'     => $error,
        ], $status);
    }
}