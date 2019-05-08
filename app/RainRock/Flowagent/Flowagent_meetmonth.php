<?php
/**
*	应用.会议预定情况
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_meetmonth  extends Rockflow
{
	public function get_datameetshow($request)
	{
		$month = $request->get('month');
		$gtype = $request->get('gtype');
		$barr  = array();
		$startdt = $month.'-01';
		$max 	 = c('date')->getmaxdt($month);
		$fobj	 = $this->getFlowobj('meet');
		$rows 	 = $fobj->getModeldata()->where('startdt','like',''.$month.'%')->get();
		$bcarr	 = array();
		$contobj = $this->getNei('contain');
		foreach($rows as $k=>$rs){
			$bo1 = true;
			if($gtype=='my' && !$contobj->iscontain($rs->joinid))$bo1=false;//我参会的
			
			if($bo1){
				$rs  = $fobj->flowreplacers($rs, 0);
				$bcarr[] = $rs;
			}
		}
		$dt 	 = $startdt;
		for($i=1;$i<=$max;$i++){
			$oi = $i;
			if($oi<10)$oi = '0'.$i.'';
			$dt = $month.'-'.$oi.'';
			$s = '';
			foreach($bcarr as $k=>$rs){
				$dt1 = substr($rs->startdt,0,10);
				$dt2 = substr($rs->enddt,0,10);
				if($dt1<=$dt && $dt<= $dt2){
					$zt= $rs->state;
				
					if($s!='')$s.='<br>';
					$s.=''.$fobj->getstartdtsmall($rs, $dt).'['.$rs->hyname.']'.$rs->title.'('.$zt.')';
				}
			}
			$barr[$dt] = $s;
		}
		return returnsuccess($barr);
	}		
}