<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
//调用方式 ，添加自定义命令，框架会自动调用
//php artisan maakess:test
Artisan::command('maakess:test', function () {
    $this->info("测试命令已调用");//输出信息
    $this->comment("测试命令已调用"); //输出带颜色的信息
})->purpose('测试命令');
Artisan::command('mail:send {user}', function (string $user) {
    $this->info("Sending email to: {$user}!");
});
