<?php namespace App\Http\Controllers\Api\Backend;

use Auth;
use Input, Response, Validator;

use UserAuth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use App\Exceptions\EmailOrPasswordIncorrectException;
use App\Exceptions\ResourceException;

class AuthController extends ApiController
{

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard     $auth
     * @param  \Illuminate\Contracts\Auth\Registrar $registrar
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function postLogin()
    {
        // Get input data
        $credentials = Input::only('email', 'password');

        $validator = Validator::make($credentials, [
            'email'    => 'Required|Between:3,64|Email',
            'password' => 'Required|Min:8|Max:30|AlphaNum',
        ]);

        if ($validator->fails()) {
            throw new ResourceException($validator->errors()->first());
        }

        // Try to login
        if (UserAuth::once($credentials)) {
            $payload = app('tymon.jwt.payload.factory')->sub(UserAuth::user()->id)->aud('user')->make();
            $token = JWTAuth::encode($payload);

            return response()->return(['token' => $token->get()]);
        }

        throw new EmailOrPasswordIncorrectException;
    }

    public function postLogout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->return();

        } catch (TokenExpiredException $e) {
            return Response::json([
                'status'      => false,
                'status_code' => $e->getStatusCode(),
                'message'     => 'token_expired',
            ], $e->getStatusCode());
        } catch (JWTException $e) {
            return Response::json([
                'status'      => false,
                'status_code' => $e->getStatusCode(),
                'message'     => 'token_invalid',
            ], $e->getStatusCode());
        }
    }

    /**
     *
     *
     * @return Response
     */
    public function postRefreshToken()
    {
        try {
            $token = JWTAuth::parseToken()->refresh();

            return response()->return(['token' => $token]);

        } catch (TokenExpiredException $e) {
            $token = JWTAuth::parseToken()->refresh();

            return response()->return(['token' => $token]);
        } catch (JWTException $e) {
            return Response::json([
                'status'      => false,
                'status_code' => $e->getStatusCode(),
                'message'     => 'token_invalid',
            ], $e->getStatusCode());
        }


    }

}
