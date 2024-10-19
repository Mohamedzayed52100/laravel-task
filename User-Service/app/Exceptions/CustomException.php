<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class CustomException extends Exception
{

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * Render the exception into an HTTP response.
     *
     */
    public function render($request)
    {
        $statusCode = $this->getValidHttpStatusCode($this->getCode());
        return response()->json([
            "message" => $this->getMessage(),
            "code" => $statusCode
        ], $this->$statusCode);
    }

    public function getValidHttpStatusCode(int $code): int
    {
        if ($code < Response::HTTP_CONTINUE || $code >= Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $code;
    }
}
