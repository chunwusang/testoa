<?php
/**
*	应用.考勤分析
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;


class Flowagent_kqanay  extends Rockflow
{
	protected $flowisturnbool	= false;
	public $defaultorder		= 'dt,desc';
	
	public function flowinit()
	{
		$this->dateobj = c('date');
		$this->kaoqinobj = $this->getNei('kaoqin');
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		if(!isempt($rs->dt))$rs->week = $this->dateobj->cnweek($rs->dt);
		$rs->state = $this->kaoqinobj->getkqstate($rs);
		if($rs->iswork==0){
			$rs->ishui = 1;
			$rs->iswork = '否';
		}else{
			$rs->iswork = '是';
		}
		
		return $rs;
	}
	
	
	
	public function flowbillwhere($obj, $atype)
	{
		$startdt 	 	= \Request::get('startdt');
		$enddt 	 	= \Request::get('enddt');
		if(!isempt($startdt))$obj->where('dt','>=', $startdt);
		if(!isempt($enddt))$obj->where('dt','<=', $enddt);
	
		return $obj;
	}
	
	public function flowlistview($lx)
	{
		return array(
			//'keywordmsg' => ''
		);
	}

	
	public function flowlistoption()
	{

		$leftstr  = '<input placeholder="日期从" data-soukey="startdt" readonly onclick="js.datechange(this,\'date\')" id="startsou" class="form-control input_date" style="width:110px">';
		
		$leftstr .= '</td><td style="padding:5px">至</td><td>';
		
		$leftstr .= '<input readonly data-soukey="enddt" onclick="js.datechange(this,\'date\')" class="form-control input_date" style="width:110px">';
		
	
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
		return $this->kaoqinobj->sendanay($dt, $this);
	}
}