<?php
/**
*	应用.打卡异常
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqdkerr  extends Rockflow
{
	
	protected function flowinit()
	{
		$this->dateobj = c('date');
		$this->kaoqinobj = $this->getNei('kaoqin');
	}
	
	//流程全部完成，加入打卡记录
	protected function flowcheckfinsh($zt)
	{
		if($zt==1){
			$uarr['aid'] 	= $this->rs->aid;
			$uarr['dkdt'] 	= $this->rs->dt.' '.$this->rs->ytime;
			$uarr['type'] 	= 4;//异常
			$uarr['explain'] = $this->rs->errtype;
			$this->getFlowobj('kqdkjl')->saveData(0, $uarr);
			$this->kaoqinobj->kqanay($this->rs->aid, $this->rs->dt);//重新分析
		}
	}
	
	//保存之前判断
	public function flowsavebefore($rs, $mid=0)
	{
		$dt = substr($rs['dt'], 0, 7);
		$cshu  = (int)$this->getNei('option')->getval('kaoqindkerr');
		if($cshu>0){
			$to  = $this->getModel()->where('aid', $rs['aid'])->where('id','<>', $mid)->where('dt','like',''.$dt.'%')->count()+1;
			if($to>$cshu)return ''.$dt.'月份已申请超过'.$cshu.'次，不能在申请了';
		}
	}
}