<?php
/**
*	应用.仓库管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_godepot  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	protected function flowinit()
	{
		$this->changeStatus();	

	}
	
	//删除判断
	protected function flowdelbillbefore()
	{
		$to 	 = \DB::table('goodx')->where('depotid', $this->mid)->count();
		if($to>0)return '此仓库有物品出入库过，不能在删除';
	}
}