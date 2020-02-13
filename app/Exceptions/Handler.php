<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {

        if ($request->wantsJson()) {
            if ($exception instanceof AuthenticationException) {
                return response()->json('Authentication Required');
            }
            if ($exception instanceof TokenInvalidException) {
                return response()->json(['error' => 'Token is Invalid', 'type' => 'token_invalid'], 403);
            } else if ($exception instanceof TokenExpiredException) {
                return response()->json(['error' => 'Token is Expired', 'type' => 'token_expired'], 403);
            } else if ($exception instanceof JWTException) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            } else if ($exception instanceof ModelNotFoundException) {
                $message = class_basename($exception->getModel()) . " Not Found";
                return response()->json(['error' => $message], 404);
            } else if ($exception instanceof NotFoundHttpException) {
                return response()->json(['error' => 'URL Not Found'], 404);
            } else if ($exception instanceof MethodNotAllowedHttpException) {
                return response()->json(['error' => 'Method not allowed for the requested endpoint'], 405);
            }
            // else if($exception instanceof QueryException){
            //     // dd($exception->getMessage());
            //     return response()->json(['error' => 'Query Exception'], 500);
            // }
            else if ($exception instanceof ValidationException) {
                return $this->convertValidationExceptionToResponse($exception, $request);
            }
            // dd($exception);
            return response()->json(['error' => $exception->getMessage()], 500);
        }

        if ($exception instanceof JWTException) {
            return redirect('/');
        }

        return parent::render($request, $exception);
    }
}