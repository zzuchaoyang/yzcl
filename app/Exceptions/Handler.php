<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        GeneralException::class,
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     *
     * @return array|\Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // 通用的异常处理
//        if ($exception instanceof GeneralException) {
//            $message  = $exception->getMessage() ?: '未知错误';
//            $code     = $exception->getCode() ?: 400;
//            $redirect = method_exists($exception, 'getRedirect') && $exception->getRedirect() ? $exception->getRedirect() : route('error.500');
//
//            return $request->ajax() || $request->wantsJson() ? response()->json(
//                [
//                    'message' => $message,
//                    'code'    => 30500
//                ],
//                $code
//            ) : response(view('errors.400', compact('code', 'message', 'redirect')), $code);
//        }

        return parent::render($request, $exception);
    }
}
