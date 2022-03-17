<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Arr;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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

    public function render($request, Throwable $e)
    {
        if ($request->wantsJson()){
            if ($e instanceof AuthenticationException) {
                return $this->error(null, 401, $e->getMessage());
            }

            if($e instanceof AuthorizationException){
                return $this->error(null, 403, $e->getMessage());
            }

            if ($e instanceof ModelNotFoundException) {
                return $this->error(null, 404, "Not Found");
            }

            if ($e instanceof ValidationException) {
                return $this->error(['errors' => array_values(Arr::dot($e->validator->errors()->toArray()))], 422, $e->getMessage());
            }

            if ($e instanceof HttpException) {
                $message = match ($e->getStatusCode()) {
                    503 => 'Service unavailable',
                    500 => 'Internal server error',
                    404 => 'Not found',
                    405 => 'Method not allowed',
                    401 => 'Unauthorized',
                    400 => 'Bad request',
                    default => 'Unknown error',
                };
                return $this->error(null, $e->getStatusCode(),$e->getMessage() == "" ? $message : $e->getMessage());
            }
            return $this->error(null, 520, $e->getMessage());
        }
        return parent::render($request, $e);
    }
}
