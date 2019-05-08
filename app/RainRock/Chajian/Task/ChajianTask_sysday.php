<?php
/**
*	每天运行
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	cli运行：php artisan rock:taskrun --act=sysday
*/

namespace App\RainRock\Chajian\Task;

use DB;

class ChajianTask_sysday extends ChajianTask
{
		
	/**
	*	待办通知
	*/	
	public function run()
	{
		$this->daibanrun();
		
		return 'success';
	}
	
	private function daibanrun()
	{
		
	}
}