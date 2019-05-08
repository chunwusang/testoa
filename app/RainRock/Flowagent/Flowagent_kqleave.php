<?php
/**
*	应用.请假条
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqleave  extends Rockflow
{
	public function flowinit()
	{
		$this->dateobj = c('date');
		$this->kaoqinobj = $this->getNei('kaoqin');
	}
	
	//子表判断
	public function flowsavebeforesubdata($zb, $sdata, $arr)
	{
		if(count($sdata)<1)return '请假时间至少要有一条';
		$qjcont = '';
		$sqtype = $this->getNei('option')->getval('kaoqinsqtype');
		if(isempt($sqtype))$sqtype='事假,年假';
		$aid 	= $arr['aid'];
		foreach($sdata as $k=>$rs){
			if($k>0)$qjcont.=';';
			$qjcont.=''.$rs['qjtype'].''.$rs['stime'].'→'.$rs['etime'].'('.$rs['totals'].'时)';
			$stime = $rs['stime'];
			foreach($sdata as $k1=>$rs1){
				if($k!=$k1){
					if($stime>$rs1['stime'] && $stime<$rs1['etime'])
						return '请假时间上：行'.($k+1).'的时间跟行'.($k1+1).'重叠了';
				}
			}
			
			$sid 	= (int)$rs['id'];
			$totals = floatval($rs['totals']);
			//判断是否有剩余假期可申请
			if(!contain(','.$sqtype.',',','.$rs['qjtype'].',')){
				$nj = $this->kaoqinobj->getqjsytime($aid, $rs['qjtype'], $rs['stime'], $sid);
				if($totals>$nj)return '请假时间上：行'.($k+1).'的剩余'.$rs['qjtype'].'不够，还差'.($totals-$nj).'时';
			}
		}
		return array(
			'data' => array(
				'qjcont' => $qjcont
			)
		);
	}
	
	public function post_total($request)
	{
		$aid 	= (int)$request->input('aid');
		$start 	= $request->input('start');
		$end 	= $request->input('end');
		
		$sj 	= $this->kaoqinobj->getsbtime($aid,$start, $end);
		$sbtime = $this->kaoqinobj->getworktime($aid, $start); //一天上班小时
		$sj 	= $this->qjshieuts($sj);
		$tshu 	= c('base')->number($sj/$sbtime);
		return returnsuccess(array($sj, $tshu, $sbtime));
	}
	
	//请假最小单位0.5小时
	private function qjshieuts($jst)
	{
		$sts = explode('.', $jst.'');
		if(isset($sts[1])){
			$vss = floatval('0.'.$sts[1]);
			if($vss>0 && $vss<=0.5)$vss = 0.5;
			if($vss>0.5)$vss = 1;
			$jst = floatval($sts[0])+$vss;
		}else{
		}
		return c('base')->number($jst, 1);
	}
	
	/**
	*	录入剩余假期
	*/
	public function inputAuto_temp_sheng()
	{
		return $this->inputAutosteew($this->adminid, $this->adminname);
	}
	public function inputAutosteew($aid, $aname)
	{
		$larr= $this->getNei('option')->getdata('kaoqinjiatype');
		$str = '';
		foreach($larr as $k=>$rs){
			$lx = $rs['name'];
			if($lx=='其他..')continue;
			$nj = $this->kaoqinobj->getqjsytime($aid, $lx);
			if($nj)$str.=''.$lx.'('.$nj.'时);';
		}
		if($str=='')$str='无可用假期';
		return '<div id="shengdiv">'.$aname.''.$str.'</div>';
	}
	
	public function post_changsheng($request)
	{
		$aid = $request->input('aid');
		$aname = $request->input('aname');
		
		$str = $this->inputAutosteew($aid, $aname);
		return returnsuccess($str);
	}
}