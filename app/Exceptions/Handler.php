<?php

namespace App\Exceptions;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (HttpException $exception) {
            return response()->json([
                'result' => 'failure',
                'type' => 'http',
                'message' => $exception->getMessage()
            ], $exception->getStatusCode());
        });

        $this->renderable(function (ValidationException $exception) {
            return response()->json([
                'result' => 'failure',
                'type' => 'validation',
                'message' => $exception->errorBag
            ], 422);
        });

        $this->renderable(function (DecryptException $exception) {
            return response()->json([
                'result' => 'failure',
                'type' => 'decrypt',
                'message' => '복호화 오류'
            ], 500);
        });

        $this->renderable(function (\Exception $exception) {
            return response()->json([
                'result' => 'failure',
                'type' => 'exception',
                'message' => $exception->getMessage()
            ], 500);
        });
    }
}
