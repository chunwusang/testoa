<?php
/**
*	应用.打卡记录
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-27
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqdkjl  extends Rockflow
{
	protected $flowisturnbool	= false;
	public $defaultorder		= 'dkdt,desc';
	
	public function flowinit()
	{
		$this->dateobj = c('date');
		$this->typearr = explode(',','在线打卡,考勤机,手机定位,手动添加,异常添加,数据导入,接口导入,企业微信打卡,钉钉打卡,中控考勤机');//0-9
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		if(!isempt($rs->dkdt))$rs->week = $this->dateobj->cnweek($rs->dkdt);
		$rs->type = arrvalue($this->typearr, $rs->type);
		return $rs;
	}
	
	public function flowdaorutestdata()
	{
		$barr[] = array(
			'base_name' 	=> $this->adminname,
			'dkdt' 		=> nowdt(),
		);
		return $barr;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$startdt 	 	= \Request::get('startdt');
		$enddt 	 	= \Request::get('enddt');
		if(!isempt($startdt))$obj->where('dkdt','>=', ''.$startdt.' 00:00:00');
		if(!isempt($enddt))$obj->where('dkdt','<=', ''.$enddt.' 23:59:59');
	
		return $obj;
	}
	
	//导入之前
	public function flowdaorubefore($rows)
	{
		$inarr = array();
		$cobj  = $this->getNei('usera');
		foreach($rows as $k=>$rs){
			$name = $rs['base_name']; //根据名称获取id
			$nafir= $cobj->getnametors($name);
			if(!$nafir)return '['.$name.']用户不存在';
			$rs['aid'] 	= $nafir->id;
			$rs['type'] = 5;
			$inarr[] 	= $rs;
		}
		return $inarr;
	}
	
	public function flowlistoption()
	{

		$leftstr  = '<input placeholder="日期从" data-soukey="startdt" readonly onclick="js.datechange(this,\'date\')" class="form-control input_date" style="width:110px">';
		
		$leftstr .= '</td><td style="padding:5px">至</td><td>';
		
		$leftstr .= '<input readonly data-soukey="enddt" onclick="js.datechange(this,\'date\')" class="form-control input_date" style="width:110px">';
		
		return [
			'leftstr'	=> $leftstr,
		];
	}
}