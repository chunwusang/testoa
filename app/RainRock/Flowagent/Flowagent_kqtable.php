<?php
/**
*	应用.考勤表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_kqtable  extends Rockflow
{
	
	protected function flowinit()
	{
		$this->dateobj = c('date');
		$this->kaoqinobj = $this->getNei('kaoqin');
	}
	

	
	protected function flowlistoption()
	{
		$barr[] 	 = [
			'name' => '考勤重新分析',
			'click'=> 'reloadanay',
			'class'=> 'default',
		];
		return $barr;
	}
	
	public function get_myanaykq($request)
	{
		$aid 	= $request->get('aid');
		if(isempt($aid))$aid = $this->adminid;
		$month 	= $request->get('month');
		
		$barr 	= $this->kaoqinobj->getanay($aid, $month);
		$barrs	=  $toarr	= array();
		foreach($barr as $dt=>$dtrows){
			$str = '';
			foreach($dtrows as $k=>$rs){
				$iswork = $rs->iswork;
				$state	= $rs->state;
				
				if($iswork==1 && isempt($rs->states)){
					if(!isset($toarr[$state]))$toarr[$state]=0;
					$toarr[$state]++;
				}
				$s   = $this->kaoqinobj->getkqstate($rs);
				$str.= ''.$rs->ztname.'：'.$s.'';
				$str.= '<br>';
				if($iswork==0)$str='<font color="#aaaaaa">'.$str.'</font>';
			}
			$barrs[$dt] = $str;
		}
		$barrs['total']	= $toarr;
		
		return returnsuccess($barrs);
	}
	
	/**
	*	考勤分析
	*/
	public function get_reloadanay($request)
	{
		$aid 	= $request->get('aid');
		if(isempt($aid))$aid = $this->adminid;
		$month 	= $request->get('month');
		$this->kaoqinobj->kqanaymonth($aid, $month);
		return returnsuccess('分析成功');
	}
}