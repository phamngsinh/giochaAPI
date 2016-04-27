<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use League\Flysystem\NotSupportedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Log;
class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
       // HttpException::class,
       // ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        return parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        Log::info($e->getMessage());
        /* if ($e instanceof ModelNotFoundException) {
             $e = new NotFoundHttpException($e->getMessage(), $e);
         }*/
        $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = trans('messages.server_error');
        if ($e instanceof Exception) {
            $code = Response::HTTP_VERSION_NOT_SUPPORTED;
            $message = trans('messages.server_error');
        } elseif ($e instanceof ModelNotFoundException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = trans('messages.common_data_not_found');
        } elseif ($e instanceof NotFoundHttpException) {
            $code = Response::HTTP_NOT_FOUND;
            $message = trans('messages.common_data_not_found');
        } elseif ($e instanceof \PDOException) {
            $code = Response::HTTP_SERVICE_UNAVAILABLE;

            $message = trans('messages.database_error');
        }
        $rs = [
            'success' => false,
            'code' => $code,
            'message' => $message,
            'data' => $request->all(),
        ];

        return response()->json($rs);
        // return parent::render($request, $e);
    }
}
