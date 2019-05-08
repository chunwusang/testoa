<?php
/**
*	应用.客户跟进
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_custgen  extends Rockflow
{
	

	protected function flowinit()
	{
		$this->changeStatus();	
		$this->hyarra 	= array('计划','完成','取消');
		$this->hyarrb 	= array('blue','green','#888888');
	}
	
	public function flowreplacers($rs)
	{
		if($rs->state==2 || $rs->status==5)$rs->ishui = 1;
		$rs->state = $this->getnowstate($rs, $this->hyarra, $this->hyarrb);
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		//今日
		if($atype=='mytoday'){
			return $obj->where('plandt', 'like', ''.nowdt('dt').'%');
		}
		return false;
	}
	
	protected function flowoptmenu($optrs)
	{
		//更新客户最后跟进时间
		if($optrs->num=='yiwanc'){
			\DB::table('customer')->where('id', $this->rs->custid)->update(array(
				'lastdt' 	=> nowdt(),
				'status'	=> 3
			));
		}
	}
}