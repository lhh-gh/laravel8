<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 17:23
 */

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    protected $statusCode;
    protected $data;

    /**
     * 构造函数
     * @param  int  $businessCode  业务错误码
     * @param  string  $message  错误信息
     * @param  int  $statusCode  HTTP 状态码，默认 400
     * @param  mixed  $data  附加数据，默认 null
     */
    public function __construct(
        $businessCode,
        $message,
        $statusCode = 400,
        $data = null
    ) {
        parent::__construct($message, $businessCode);
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getData()
    {
        return $this->data;
    }
}
