<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetSmsCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maakees:reset-sms-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置每日发送短信次数';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(SendCode $sendCode)
    {
        $res=$sendCode->resetSmsCount();
        Log::info('重置短信发送次数',['res'=>$res]);
        return 0;
    }
}
