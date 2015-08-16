<?php namespace App\Http\Middleware;

use GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware as BaseVerifier;

use Closure;
use Response;

class ThrottleMiddleware extends BaseVerifier
{


    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @param int                      $limit
     * @param int                      $time
     *
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $limit = 20, $time = 5)
    {
        if (!$this->throttle->attempt($request, $limit, $time)) {
            return $this->respond('TooManyRequestsHttpException', 'rate_limit_exceed', 429, []);
        }

        return $this->addRateLimitToResponse($request, $next($request), $limit);
    }

    /**
     * Add the RateLimit to the response cookies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Http\Response $response
     * @return \Illuminate\Http\Response
     */
    protected function addRateLimitToResponse($request, $response, $limit)
    {
        $throttle = $this->throttle->get($request);

        $response->header('x-rate-limit-limit', $limit);
        $response->header('x-rate-limit-remaining', $limit - $throttle->count());

        return $response;
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
        return Response::json([
            'status'      => false,
            'status_code' => $status,
            'message'     => $error,
        ], $status);
    }

}
