<?php

namespace App\Exceptions;

use Aacotroneo\Saml2\Saml2Auth;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Throws an error response in case the user is not authenticated.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return JsonResponse|string|Response|null
     * @throws Exception
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
            // Or, return a response that causes client side js to redirect to '/routesPrefix/myIdp1/login'
        }

        $saml2Auth = new Saml2Auth(Saml2Auth::loadOneLoginAuthFromIpdConfig('eipdev'));
        return $saml2Auth->login('/');
    }
}
