<?php
/**
*	应用.会议
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_daily  extends Rockflow
{
	
	public function flowinit()
	{
		$this->typearr = explode(',','日报,周报,月报,年报');
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		$rs->type 		= $this->typearr[$rs->type];
		return $rs;
	}
}