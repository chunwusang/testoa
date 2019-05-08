<?php
/**
*	应用.会议室
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_meethys  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	protected function flowinit()
	{
		$this->changeStatus();
	}
		
	public function flowreplacers($rs)
	{
		if($rs->status==0)$rs->ishui=1;
		return $rs;
	}		
}