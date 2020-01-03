<?php

namespace App\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param  \Exception $exception
     *
     * @throws
     *
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ClientException) {
            $response = json_decode($exception->getResponse()->getBody()->getContents(), true);

            $code = $response['error']['code'];

            if (array_key_exists('error_subcode', $response['error'])) {
                $code .= '-' . $response['error']['error_subcode'];
            }

            return response()->json([
                'code'    => $code,
                'message' => $response['error']['message'],
            ], 400);
        } elseif ($exception instanceof RequestException) {
            return response()->json([
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
            ], 400);
        }

        return parent::render($request, $exception);
    }
}
