<?php

namespace App\Providers;

use App\Services\VideoTranscodeService;
use Illuminate\Support\ServiceProvider;

class TranscodeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //如果要换其他的服务方，直接把匿名函数中的类改一下就好了
        $this->app->singleton(VideoTranscodeService::class, function ($app) {
            return new \App\Services\Aliyun\VideoTranscodeService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
