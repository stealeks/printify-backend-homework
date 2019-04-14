<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * @var string[]
     */
    protected $dontFlash = [
        'password',
        'token',
    ];

    /**
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return Response
     */
    public function render($request, Exception $exception) : Response
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $exception);
        }

        $message = $exception->getMessage();
        $code = 500;
        if ($exception instanceof HttpException) {
            $code = $exception->getStatusCode();
            if (empty($message)) {
                $message = Response::$statusTexts[$code] ?? 'Unknown error';
            }
        }

        $response = [
            'message' => $message,
        ];

        if (config('app.debug')) {
            $response['class'] = get_class($exception);
            // todo
            //$response['trace'] = $exception->getTrace();
        }

        if ($exception instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
        }

        if ($exception instanceof AuthenticationException) {
            $code = Response::HTTP_UNAUTHORIZED;
        }

        if ($exception instanceof ValidationException) {
            $code = Response::HTTP_BAD_REQUEST;

            $response['errors'] = $exception->errors();
        }

        return response()->json($response, $code);
    }
}
