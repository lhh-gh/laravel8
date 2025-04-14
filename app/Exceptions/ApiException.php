<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 17:25
 */

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{

    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function render()
    {
        $rs = [
            'status'=>'failed',
            'code'=>$this->code,
            'time'=>date('Y-m-d H:i:s'),
            'msg'=>$this->message."----in ApiException",
            'data'=>(Object)[],
        ];

        return response()->json($rs);
    }
}
