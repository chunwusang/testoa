<?php
/**
*	应用接口
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Agentapi;

use App\RainRock\Chajian\Chajian;
use Rock;

class ChajianAgentapi extends Chajian
{
	public $flow;
	public $agenhinfo;
	public $agentinfo;
	
	public function initFlow($num)
	{
		$this->flow	= Rock::getFlow($num, $this->useainfo);
		$this->agenhinfo	= $this->flow->agenhinfo;
		$this->agentinfo	= $this->flow->agentinfo;
	}
	
}