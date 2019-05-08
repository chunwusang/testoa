<?php
/**
*	命令计划任务运行
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Console\Commands;


class RockReim extends Rockcommandsbase
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'rock:reim {param}';

    /**
     * The console command description.
     * rock队列初始化
     * @var string
     */
    protected $description = 'xinhuoa platform reimserver init/stop';

    /**
     * Execute the console command.
     * php artisan rock:reim init
     * @return mixed
     */
    public function handle()
    {
		$param 	= $this->argument('param');
		$msg 	= '';
		if($param=='init'){
			$msg = c('Queue:start')->init();
		}
		if($param=='stop'){
			$msg = c('Queue:start')->stop();
		}
		if($param=='start'){
			$msg = c('Queue:start')->start();
		}
		if(substr($param,0,3)=='run'){
			$id  = substr($param,4);
			$msg = c('Queue:start')->run($id);
		}
		
		//从REIM服务端来的php artisan rock:reim client-参数
		if(substr($param,0,6)=='client'){
			$msg = c('Queue:reim')->runclient(substr($param,7));
		}
		$this->line($msg);
    }
}
