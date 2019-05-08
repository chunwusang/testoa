<?php
/**
*	命令计划任务运行
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Console\Commands;


class RockTaskInit extends Rockcommandsbase
{
    /**
     * The name and signature of the console command.
     * 计划任务运行开启
     * @var string
     */
    protected $signature = 'rock:taskinit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'xinhuoa platform planned task init';

    /**
     * Execute the console command.
     * php artisan rock:taskinit
     * @return mixed
     */
    public function handle()
    {
		$msg = c('Task:start')->init();
		$this->line($msg);
    }
}
