<?php
/**
*	应用.考勤时间规则
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-08-22
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqxxsj  extends Rockflow
{
	protected $flowisturnbool	= false;
	
	public $defaultorder		= 'dt,asc';
	
	public function flowinit()
	{
		$this->dateobj = c('date');
	}
	
	public function flowlistoption()
	{
		$barr[] = array(
			'name' => '加休息日',
			'click'=> 'weouxiugd1',
			'class'=> 'default'
		);
		$barr[] = array(
			'name' => '周六日设置休息日',
			'click'=> 'weouxiugd2',
			'class'=> 'default'
		);
		/*
		$barr[] = array(
			'name' => '导入'.date('Y').'年法定节假日',
			'click'=> 'weouxiugd3',
			'class'=> 'default'
		);*/
		
		$leftstr  = '<select id="fenquselse" onchange="changekqxxsj(true)" class="form-control" style="width:150px">';
		$leftstr .= '<option value="">-全部休息规则-</option>';
		$fqarr	  = $this->getModel()->where(['cid'=>$this->companyid,'pid'=>0])->get();
		foreach($fqarr as $k=>$frs){
			$leftstr .= '<option value="'.$frs->id.'" >'.$frs->name.'</option>';
		}
		$leftstr .= '</select>';
		
		$leftstr .= '</td><td style="padding-left:10px">';
		
		$leftstr .= '<input readonly placeholder="月份" id="soumonth" onclick="js.datechange(this,\'month\')" class="form-control input_date" style="width:90px">';
		
		return [
			'leftstr'	=> $leftstr,
			'btnarr'	=> $barr
		];
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$pid 	 	= (int)\Request::get('pid','0');
		$month 	 	= \Request::get('month');
		if($pid>0)$obj->where(function($query)use($pid){
			$query->where('id', $pid);
			$query->oRwhere('pid', $pid);
		});
		if(!isempt($month))$obj->where('dt','like', ''.$month.'%');
		
		return $obj;
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		if(!isempt($rs->dt))$rs->week = $this->dateobj->cnweek($rs->dt);
		return $rs;
	}
	
	protected function flowdelbill()
	{
		$this->getModel()->where('pid', $this->mid)->delete();
	}
	
	//任何人都可以编辑
	public function iseditqx()
	{
		return 1;
	}
	//任何人都可以删除
	public function isdelqx()
	{
		return 1;
	}
	
	public function post_weouxiugd2($request)
	{
		$pid = (int)$request->input('pid');
		$month = $request->input('month');
		if($pid==0 || isempt($month))return returnerror('err');

		$max 	= $this->dateobj->getmaxdt($month);
		for($i=1; $i<=$max; $i++){
			$oi = $i;if($oi<10)$oi='0'.$i.'';
			$dt = ''.$month.'-'.$oi.'';
			$we = $this->dateobj->cnweek($dt);
			if($we=='六' || $we=='日'){
				$uarrs = array(
					'cid' => $this->companyid,
					'pid' => $pid,
					'dt' => $dt,
				);
				$obf = $this->getModel()->where($uarrs)->first();
				if(!$obf)$this->getModel()->insert($uarrs);
			}
		}
		return returnsuccess();
	}
}