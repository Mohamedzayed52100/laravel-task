<?php

namespace App\Exceptions;


use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Illuminate\Database\QueryException;

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

        $this->reportable(function (CustomException $e) {
            Log::info($e->getMessage());
        });
        $this->reportable(function (ModelNotFoundException $e) {
            Log::info($e->getMessage());
        });
        $this->reportable(function (Throwable $e) {
            Log::info($e->getMessage());
        });


    }

    public function render($request, Throwable $e)
    {

        if ($e instanceof Exception) {
            $statusCode = 500;
            if ($e instanceof ModelNotFoundException) {
                $statusCode = 404;
                return response()->json([
                    'message' => $e->getMessage(),
                ], $statusCode);
            } elseif ($e instanceof ValidationException) {
                $statusCode = $e->status;
                return response()->json([
                    'message' => implode(", ", $e->validator->errors()->all()),
                ], $statusCode);
            }

            if ($e instanceof CustomException) {
                return response()->json([
                    'message' => $e->getMessage(),
                ], $e->getCode());
            }


            return response()->json([
                'message' => $e->getMessage(),
            ], $statusCode);
        }


        return parent::render($request, $e);
    }
}
