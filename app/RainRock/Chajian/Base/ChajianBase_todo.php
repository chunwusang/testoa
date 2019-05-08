<?php
/**
*	插件-提醒
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*	使用方法 $obj = c('todo');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\TodoModel;

class ChajianBase_todo extends ChajianBase
{
	/**
	*	普通添加提醒
	*/
	public function add($aids, $typename, $title, $mess='', $tododt='', $agenhnum='', $mtable='', $mid=0)
	{
		if(isempt($aids))return;
		$aidsa	= explode(',', $aids);
		if($tododt=='')$tododt = nowdt();
		$uarra 	= array();
		foreach($aidsa as $aid){
			if(in_array($aid, $uarra))continue;
			$obj = new TodoModel();
			$obj->aid 		= $aid;
			$obj->cid 		= $this->companyid;
			$obj->typename 	= $typename;
			$obj->title 	= $title;
			$obj->mess 		= $mess;
			$obj->agenhnum  = $agenhnum;
			$obj->mtable 	= $mtable;
			$obj->mid 		= $mid;
			$obj->status 	= 0; //未读的
			$obj->optdt 	= nowdt();
			$obj->optname 	= $this->useainfo->name;
			$obj->tododt 	= $tododt;
			$obj->save();
			$uarra[] = $aid;
		}
		return $aidsa;
	}
	
	/**
	*	根据流程添加提醒
	*/
	public function adds($aids,  $title, $mess='', $flow=null)
	{
		$agenhnum	= '';
		$mtable		= '';
		$mid		= 0;
		$typename	= '';
		if($flow!=null){
			$agenhnum 	= $flow->agenhnum;
			$mtable 	= $flow->mtable;
			$mid 		= $flow->mid;
			$typename	= $flow->agenhname;
		}
		return $this->add($aids, $typename, $title, $mess,'', $agenhnum, $mtable, $mid);
	}
}