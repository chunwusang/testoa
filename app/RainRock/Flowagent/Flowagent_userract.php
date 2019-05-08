<?php
/**
*	应用.合同
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use App\Model\Base\UseraModel;

class Flowagent_userract  extends Rockflow
{
	
	protected $flowisturnbool	= false;
	
	protected function flowinit()
	{
		$this->changeStatus();	
		
		$this->statearr 	= explode(',','<font color=blue>待执行</font>,<font color=green>生效中</font>,<font color=#888888>已终止</font>,<font color=red>已过期</font>');
	}
	
	public function flowreplacers($rs)
	{
		$zt 	= $rs->state;
		$nzt 	= $rs->state;
		$dt 	= nowdt('dt');
		if($rs->startdt>$dt){
			$nzt = 0;
		}else{
			if(isempt($rs->tqenddt)){
				if($rs->enddt>=$dt){
					$nzt = 1; //生效
				}else{
					$nzt = 3; //过期
				}
			}else{
				if($rs->tqenddt>=$dt){
					$nzt = 1; //生效
				}else{
					$nzt = 2; //终止
				}
			}
		}
		
		if($rs->status!=1 || $nzt>=2)$rs->ishui = 1;
		
		if($zt!=$nzt){
			$this->getModel()->where('id', $rs->id)->update([
				'state' => $nzt
			]);
		}
		
		$rs->state = $this->statearr[$nzt];
		return $rs;
	}
}