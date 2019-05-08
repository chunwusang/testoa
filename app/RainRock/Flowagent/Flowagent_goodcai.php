<?php
/**
*	应用.采购单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_goodcai  extends Rockflow
{
	protected function flowinit()
	{
		$this->goodsobj = $this->getNei('goods');
	}
	
	public function flowsavebefore($arr)
	{
		$data['type'] = 1;
		return [
			'data' => $data
		];
	}
	
	public function flowreplacers($rs)
	{
		$zbarr 			= $this->getsubdatalist(1, $rs->id);
		$rs->wupinlist 	= $this->goodsobj->getgoodninfo($zbarr);
		$nors 			= $this->goodsobj->showgoodmtype($rs);
		$rs->state 		= $nors->state;
		return $rs;
	}
	
	//删除详情的
	protected function flowdelbill()
	{
		\DB::table('goodx')->where('goodmid', $this->mid)->delete();
		
	}
	
	//已审核完成
	protected function flowcheckfinsh($zt)
	{
		if($zt==1)$this->goodsobj->reloadcgmoney();
	}
}