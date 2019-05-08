<?php
/**
*	应用.剩余假期统计
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-31
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqjiato  extends Rockflow
{
	
	protected function flowinit()
	{
		$this->dateobj = c('date');
		$this->kaoqinobj = $this->getNei('kaoqin');
		$this->userinfostate= $this->getNei('option')->getdata('userinfostate');
	}
	
	public function flowreplacers($rs)
	{
		if($rs->state==5)$rs->ishui = 1;
		foreach($this->userinfostate as $k1=>$rs1){
			if($rs1['value']==$rs->state){
				$rs->state = $rs1['name'];
				break;
			}
		}
		return $rs;
	}
	
	protected function flowfieldsname()
	{
		$jaarr 	= $this->getNei('option')->getdata('kaoqinjiatype');
		$barr	= array();
		foreach($jaarr as $k=>$rs){
			if($rs['name']!='其他..')$barr['jqtype'.$k.''] = array(
				'name' => '剩余'.$rs['name'],
				'iszs'	=> 1
			); 
		}
		
		return array(
			'fields_after' => $barr
		);
	}
	
	//统计
	public function flowgetdata($rows)
	{
		$dt 	= \Request::input('month');
		if(isempt($dt))$dt = nowdt('dt');
		$jaarr = $this->getNei('option')->getdata('kaoqinjiatype');
		$sbtime = 0;
		$bsobj	= c('base');
		foreach($rows as $k=>$rs){
			$aid = $rs->aid;
			if($sbtime==0)$sbtime = $this->kaoqinobj->getworktime($aid);
			foreach($jaarr as $k1=>$rs1){
				if($rs1['name']=='其他..')continue;
				$sj = $this->kaoqinobj->getqjsytime($aid, $rs1['name'], $dt.' 00:00:00');
				$ke = 'jqtype'.$k1.'';
				if($sj>0){
					$tian 	 = $v2 = $bsobj->number($sj/$sbtime, 1); 
					$rs->$ke = $sj.'('.$tian.'天)';
				}
			}
		}
		return ['rows'=>$rows];
	}
	
	public function flowbillwhere($obj, $atype)
	{
		if($atype=='view'){
			$iskall = \Request::get('iskall');
			$month 	= \Request::input('month');
			if(isempt($month))$month= date('Y-m');
			$obj->whereRaw($this->kaoqinobj->getuwhere($month));
			if(isempt($iskall))$obj->where('iskq', 1); //只看需要考勤的
		}
	}
	
	public function flowlistoption()
	{

		$leftstr  = '<input placeholder="截止日期" id="startsou" data-soukey="month" readonly onclick="js.datechange(this,\'date\')" class="form-control input_date" style="width:120px"></td><td><select data-soukey="iskall" class="form-control" style="width:130px;margin-left:10px"><option value="">仅看需考勤</option><option value="1">全部人员</option></select>';

		return [
			'leftstr'	=> $leftstr,
			
			'optcolums'	=> false
		];
	}
	
	
}