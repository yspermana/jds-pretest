<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Helpers\ResponseHelper;
use Firebase\JWT\SignatureInvalidException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof HttpResponseException) {
            return ResponseHelper::setErrorResponse('Error HTTP Response');
        } elseif ($exception instanceof ModelNotFoundException) {
            return ResponseHelper::setErrorResponse('Error Not Found');
        } elseif ($exception instanceof AuthorizationException) {
            return ResponseHelper::setErrorResponse('Error 403');
        } elseif ($exception instanceof ValidationException && $exception->getResponse()) {
            return ResponseHelper::setErrorResponse('Error Validation');
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return ResponseHelper::setErrorResponse('Error Method Not Allowed');
        }  elseif ($exception instanceof SignatureInvalidException) {
            return ResponseHelper::setErrorResponse('Signature verification failed');
        }

        return parent::render($request, $exception);
    }
}
