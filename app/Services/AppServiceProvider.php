<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: SillyCat
 * Date: 2025-04-13
 * Time: 22:16
 */

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    //注册服务基础绑定
    public function register()
    {
        $this->app->bind('Payment', function ($app) {
            return new PaymentService();
        });
    }
}

