<?php
/**
*	应用.流程申请新增
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_flowadd  extends Rockflow
{
	
	public function flowlistview($lx)
	{
		$agenhbarr	= $this->getNei('agenh')->getAgenh(1, 1);
		$agenharr	= $agenhbarr[0];
		
		//需要有新增的权限
		$agenhbarr	= array();
		foreach($agenharr as $atype=>$agearr){
			foreach($agearr as $k=>$rs){
				if($rs->islu==0)continue;
				if($this->authoryobj->isadd($rs->id)){
					$agenhbarr[$atype][] = $rs;
				}
			}
		}
		return [
			'agenharr' => $agenhbarr,
		];
	}
}