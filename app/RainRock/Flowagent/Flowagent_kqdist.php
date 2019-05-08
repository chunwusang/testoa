<?php
/**
*	应用.考勤相关分配
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqdist  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	protected function flowinit()
	{
		$this->changeStatus();	
		$this->typearr = explode(',','考勤规则,休息时间,打卡定位');
	}
	
	public function getfuizhe()
	{
		$type 	= (int)\Request::get('def_type','0'); //默认
		if($this->mid>0)$type 	= $this->rs->type;
		$arows	= array();
		if($type==0){
			$arows 	= $this->getModel('kqsjgz')->where('pid',0)->orderBy('sort')->get();
		}
		if($type==1){
			$arows 	= $this->getModel('kqxxsj')->where('pid',0)->get();
		}
		$barr 	= array();
		foreach($arows as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'name' => $rs->name,
			);
		}
		return $barr;
	}
	
	public function flowreplacers($rs)
	{
		if($rs->type==0){
			$onrs 	= $this->getModel('kqsjgz')->find($rs->mid);
			if($onrs)$rs->mid .= '.'.$onrs->name;
		}
		if($rs->type==1){
			$onrs 	= $this->getModel('kqxxsj')->find($rs->mid);
			if($onrs)$rs->mid .= '.'.$onrs->name;
		}
		$rs->type = $this->typearr[$rs->type];
		if($rs->status==0 || $rs->enddt<nowdt('dt'))$rs->ishui=1;
		return $rs;
	}
	
}