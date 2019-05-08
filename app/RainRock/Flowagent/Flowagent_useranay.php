<?php
/**
*	应用.人员分析
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_useranay  extends Rockflow
{
	public function get_anay($request)
	{
		
		$type 	= $request->get('type','deptname');
		$dt 	= $request->get('dt');
		$where	= 'state<>5';
		if($dt !=''){
			$where = "((state<>5 and workdate<='$dt') or (state=5 and workdate<='$dt' and quitdt>'$dt'))";
		}
		$this->userinfostate= $this->getNei('option')->getdata('userinfostate');
		
		$rows	= $this->getModel()->where('cid', $this->companyid)->where('aid','>',0)->whereRaw($where)->get();
		$rows	= $rows->toArray();
		
		$nianls	= array(
			array(0,'16-20岁',16,20),
			array(0,'21-25岁',21,25),
			array(0,'26-30岁',26,30),
			array(0,'31-40岁',31,40),
			array(0,'41岁以上',41,9999),
			array(0,'其他',-555,15),
		);
		
		$yearls	= array(
			array(0,'1年以下',0,1),
			array(0,'1-3年',1,3),
			array(0,'3-5年',3,5),
			array(0,'5-10年',5,10),
			array(0,'10年以上',10,9999)
		);
		
		//$atatea = explode(',', '试用期,正式,实习生,兼职,临时工,离职');
		
		$gendera= explode(',', '未知,男,女');
		$total 	= 0;
		foreach($rows as $k=>$rs){
			$year = '';
			if(!isempt($rs['workdate'])) $year = substr($rs['workdate'],0,4);
			$rows[$k]['year'] = $year;
			
			$lian	= $this->jsnianl($rs['birthday']);
			foreach($nianls as $n=>$nsa){
				if( $lian >= $nsa[2]  && $lian <= $nsa[3]){
					$rows[$k]['nian'] = $nsa[1];
					break;
				}
			}
			
			$state = (int)$rs['state'];
			//$rows[$k]['state'] = $atatea[$state];
			$rows[$k]['gender'] = $gendera[$rs['gender']];
			
			foreach($this->userinfostate as $k1=>$rs1){
				if($rs1['value']==$rs['state']){
					$rows[$k]['state'] = $rs1['name'];
					break;
				}
			}
			
			//入职连
			$nan = $this->worknx($rs['workdate']);
			foreach($yearls as $n=>$nsa){
				if( $nan >= $nsa[2]  && $nan < $nsa[3]){
					$rows[$k]['nianxian'] = $nsa[1];
					break;
				}
			}
			$total++;
		}
		
		$arr 	= array();
		
		foreach($rows as $k=>$rs){
			$val = $rs[$type];
			if(isempt($val))$val = '其他';
			if(!isset($arr[$val]))$arr[$val]=0;
			$arr[$val]++;
		}	
		$bobj 	= c('base');
		$a		= array();
		$xuhao 	= 0;
		foreach($arr as $k=>$v){
			$a[] = array(
				'xuhao'	=> ++$xuhao,
				'name'	=> $k,
				'value'	=> $v,
				'bili'	=> ($bobj->number($v/$total*100)).'%'
			);
		}

		return returnsuccess($a);
	}
	
	private function jsnianl($dt)
	{
		$nY	= date('Y')+1;
		$lx	= 0;
		if(!isempt($dt) && !contain($dt, '0000')){
			$ss		= explode('-', $dt);
			$saa	= (int)$ss[0];
			$lx		= $nY - $saa;
		}
		return $lx	;
	}
	
	//计算工作年限的
	private function worknx($dt)
	{
		$w = 0;
		if(!isempt($dt) && !contain($dt, '0000')){
			$startt		= strtotime($dt);
			$date 		= date('Y-m-d');
			$endtime	= strtotime($date);
			$w			= (int)(($endtime - $startt) / (24*3600) / 365);
		}
		return $w;
	}
}