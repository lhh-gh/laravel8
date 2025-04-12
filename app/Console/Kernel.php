<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //每分钟执行一次 ，失效超过15分钟未支付订单
        $schedule->command("maakes:cancel-order")->withoutOverlapping()->everyMinute();
        // 每天00:00  执行一次 重置短信发送次数
        $schedule->command("maakes:reset-sms-count")->daily();
    }

    /**
     * 注册闭包命令
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
