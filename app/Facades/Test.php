<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-14
 * Time: 22:16
 */

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Test extends Facade #继承Facade
{
    public static function getFacadeAccessor()#重写里面的getFacadeAccessor方法
    {
        return 'test';  #自定义返回后面需要调用
    }
}
