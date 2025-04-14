<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * 禁止记录日志
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
        BusinessException::class,
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
        // 处理 BusinessException
        if ($e instanceof BusinessException) {
            return response()->json([
                'code' => $e->getCode(),     // 业务错误码
                'message' => $e->getMessage(),
                'data' => $e->getData(),
            ], $e->getStatusCode());         // HTTP 状态码
        }

        return parent::render($request, $e);
    }
}
