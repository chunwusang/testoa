<?php
/**
*	应用.物品领用
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_goodling  extends Rockflow
{
	
	protected function flowinit()
	{
		$this->goodsobj = $this->getNei('goods');
	}
	
	public function flowreplacers($rs)
	{
		$zbarr 			= $this->getsubdatalist(1, $rs->id);
		$rs->wupinlist 	= $this->goodsobj->getgoodninfo($zbarr);
		$nors 			= $this->goodsobj->showgoodmtype($rs);
		$rs->state 		= $nors->state;
		return $rs;
	}
	
	
}