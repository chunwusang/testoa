<?php
/**
*	应用.考勤统计
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-31
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqtotal  extends Rockflow
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
		$ztarr = $this->kaoqinobj->getkqztarr($this->adminid, nowdt());
		$barrs = [
			'state0' 	=> '正常',
			'state1' 	=> '迟到',
			'state2' 	=> '早退',
			'weidk' 	=> '未打卡',
			'timesb' 	=> '应上班(天)',
			'timeys' 	=> '已上班(天)',
		];
		foreach($ztarr as $k=>$v)$barrs[$v]=$k;
		foreach($barrs as $kv=>$lxs){
			$barr[$kv] = array(
				'name' => $lxs,
				'iszs'	=> 1
			); 
		}
		$qjarr	= $this->getNei('option')->getdata('kaoqinqjtype');
		foreach($qjarr as $k=>$rs){
			$barr['qjtype'.$k.''] = array(
				'name' => $rs['name'],
				'iszs'	=> 1
			); 
		}
		$barr['jiaban'] = array(
			'name' => '加班(时)',
			'iszs'	=> 1
		);
		$barr['waichu'] = array(
			'name' => '外出(次)',
			'iszs'	=> 1
		);
		$barr['dkerr'] = array(
			'name' => '打卡异常(次)',
			'iszs'	=> 1
		);
		return array(
			'fields_after' => $barr
		);
	}
	
	public function flowgetdata($rows)
	{
		//$rows[0]->state0 = 2;
		$month = \Request::input('month');
		if(isempt($month))$month= date('Y-m');
		$sbtime = 0;
		$bsobj	= c('base');
		$ztarr 	= $this->kaoqinobj->getkqztarr($this->adminid, nowdt());
		
		
		foreach($rows as $k=>$rs){
			$aid = $rs->aid;
			if($sbtime==0)$sbtime = $this->kaoqinobj->getworktime($aid);
			$anay= $this->kaoqinobj->anaytotal($aid, $month, $ztarr);
			foreach($anay as $k1=>$v1){
				$rs->$k1 = $v1;
			}
			$rs->timesb = $bsobj->number($rs->timesb/$sbtime, 1); 
			$rs->timeys = $bsobj->number($rs->timeys/$sbtime, 1); 
			
			//请假的
			$qjdata = $this->kaoqinobj->qjtotal($aid, $month);
			foreach($qjdata as $k1=>$v1){
				if(contain($k1,'qjtype')){
					$v2 = $bsobj->number($v1/$sbtime, 1); 
					$rs->$k1 = $v1.'('.$v2.'天)';
				}else{
					$rs->$k1 = $v1;
				}
			}
			if($rs->waichu==0)$rs->waichu='';
			if($rs->dkerr==0)$rs->dkerr='';
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

		$leftstr  = '<input placeholder="月份" id="startsou" data-soukey="month" readonly onclick="js.datechange(this,\'month\')" class="form-control input_date" style="width:100px"></td><td><select data-soukey="iskall" class="form-control" style="width:130px;margin-left:10px"><option value="">仅看需考勤</option><option value="1">全部人员</option></select>';
		
		$btnarr[] 	 = [
			'name' => '考勤重新分析',
			'click'=> 'reloadanay',
			'class'=> 'default',
		];
		return [
			'leftstr'	=> $leftstr,
			'btnarr'	=> $btnarr,
			'optcolums'	=> false
		];
	}
	
	/**
	*	考勤分析
	*/
	public function get_reloadanay($request)
	{
		$dt 	= $request->get('dt');
		if(isempt($dt))$dt = date('Y-m');
		return $this->kaoqinobj->sendanay($dt, $this);
	}
}