<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
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
        parent::report($e);
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
        $msg = $e->getMessage();
        $tokenMessages = "";
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $code = \Illuminate\Http\Response::HTTP_OK;
        } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
            $code = \Illuminate\Http\Response::HTTP_OK;
        } elseif ($e instanceof ValidationException) {
            $code = Response::HTTP_OK;
        } elseif ($e instanceof TokenExpiredException) {
            $code = $e->getStatusCode();
            $tokenMessages = "token expired";
            JWTAuth::refresh(JWTAuth::getToken());
        } elseif ($e instanceof TokenInvalidException) {
            $code = $e->getStatusCode();
            $tokenMessages = "token invalid";
        }else{
           $code = \Illuminate\Http\Response::HTTP_BAD_REQUEST;
        }
        if ($tokenMessages != '') {
            $msg = $tokenMessages;
        } else {
            $tmp = json_decode($msg, true);

            $rsKey = [];
            $rsVal = [];
            if (is_array($tmp)) {
                foreach ($tmp AS $key => $el) {
                    if (!in_array($key, $rsKey)) {
                        $rsKey[] = $key;
                    }
                }
            }
            $rs = [];
            if ($rsKey) {
                foreach ($rsKey AS $el) {
                    $rs[] = "{$el}: " . implode(', ', $tmp[$el]);
                }
            }
            if ($rs) {
                $msg = implode('; ', $rs);
            }
        }
        return response()->json([
            'success' => false,
            'code' => $code,
            'message' => str_replace(array('"', "\n"), array("'", ""), $msg ? $msg : ["API request does not found!"]),
            'data' => $request->all(),
        ]);
        //return parent::render($request, $e);
    }
}