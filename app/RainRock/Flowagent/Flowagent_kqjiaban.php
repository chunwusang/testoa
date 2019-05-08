<?php
/**
*	应用.加班单
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqjiaban  extends Rockflow
{
	public function flowreplacers($rs)
	{
		$jtype = '调休';
		if($rs->jtype==1){
			$jtype='加班费';
		}else{
			$rs->jiafee = '';
		}
		$rs->jtype = $jtype;
		return $rs;
	}
	
	//流程全部完成，如果是换调休加入到假期里面
	protected function flowcheckfinsh($zt)
	{
		if($zt==1 && $this->rs->jtype==0){
			$youxiao	 = (int)$this->getNei('option')->getval('kaoqintiaoxyou','0');
			$uarr['aid'] = $this->rs->aid;
			$uarr['applyname'] = $this->rs->applyname;
			$uarr['isturn'] = 1;
			$uarr['status'] = 1;
			$uarr['jiatype'] = '调休';
			$uarr['startdt'] = $this->rs->startdt;
			$uarr['totals']  = $this->rs->totals;
			$uarr['totday']  = $this->rs->totals/8;
			if($youxiao>0){
				$uarr['enddt'] = c('date')->adddate($uarr['startdt'],'m', $youxiao,'Y-m-d 23:59:59');
			}
			$uarr['explain']  = '来自加班单'.$this->rs->base_sericnum.'';
			$this->getFlowobj('kqjiaqi')->saveData(0, $uarr);
		}
	}
	
	//默认隐藏加班费框
	protected function flowinputfields($farr)
	{
		$istp = true;
		if($this->mid>0 && $this->rs->jtye==1)$istp = false;
		$toux = array('jiafee');
		foreach($farr as $k=>$rs){
			if($istp && in_array($rs->fields, $toux)){
				$farr[$k]->mstyle='display:none';
			}
		}
		return $farr;
	}
	
	public function post_gettotals($request)
	{
		$dt1 = $request->input('dt1');
		$dt2 = $request->input('dt2');
		
		$barr['totals'] = c('date')->datediff('H', $dt1, $dt2);
		return returnsuccess($barr);
	}
}