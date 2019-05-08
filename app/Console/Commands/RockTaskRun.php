<?php
/**
*	命令计划任务运行
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-06
*/

namespace App\Console\Commands;


class RockTaskRun extends Rockcommandsbase
{
    /**
     * The name and signature of the console command.
     * 计划任务运行开启
     * @var string
     */
    protected $signature = 'rock:taskrun {--act=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'xinhuoa platform planned task operation';

    /**
     * Execute the console command.
     * php artisan rock:taskrun
     * php artisan rock:taskrun --act=system:minute
     * @return mixed
     */
    public function handle()
    {
		$runact = $this->option('act');
		if($runact==''){
			$msg= c('Task:start')->runtask(); //总运行
		}else{
			if(is_numeric($runact)){
				$msg 	= c('Task:start')->run($runact);
			}else{
				$runact = str_replace(',',':',$runact);
				$acta 	= explode(':', $runact);
				$fies 	= $acta[0];
				$act  	= arrvalue($acta, 1, 'run');
				$msg	= c('Task:'.$fies.'')->$act();
			}
		}
		echo $msg;
    }
}
