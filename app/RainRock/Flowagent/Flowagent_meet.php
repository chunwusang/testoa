<?php
/**
*	应用.会议
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_meet  extends Rockflow
{
	protected function flowinit()
	{
		$this->changeStatus();
		$this->hyarra 	= array('正常','会议中','结束','取消');
		$this->hyarrb 	= array('green','blue','#ff6600','#888888');	
	}
	
	public function flowbillwhere($obj, $atype)
	{
		//今日会议
		if($atype=='today'){
			return $obj->where('startdt','<',date('Y-m-d 23:59:59'))
					->where('enddt','>',date('Y-m-d 00:00:00'));
		}
		
		//近7天会议
		if($atype=='week'){
			return $obj->where('startdt','<',date('Y-m-d 23:59:59', strtotime('+7 day')))
						->where('enddt','>',date('Y-m-d 00:00:00'));
		}
		
		return false;
	}
	
	public function meethysdata()
	{
		$data = $this->getModel('meethys')->where(['cid'=>$this->companyid,'status'=>1])->get();
		$barr = array();
		foreach($data as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'name' => $rs->hyname,
				'subname' => $rs->address,
			);
		}
		return $barr;
	}		
	
	
	/**
	*	会议状态切换
	*/
	public function flowreplacers($rs)
	{
		$zt 		 = $rs->state;
		$nzt 		 = $zt;
		$time 		 = time();
		
		$stime 		= strtotime($rs->startdt);
		$etime 		= strtotime($rs->enddt);
		if($zt < 2){
			if($etime<$time){
				$nzt = 2;
			}else if($stime>$time){
				$nzt = 0;
			}else{
				$nzt = 1;
			}
		}
		
		if($zt != $nzt){
			$this->updateData(['state'=>$nzt],$rs->id);
			$zt = $nzt;
		}
		
		if($zt==2 || $zt==3 || $rs->status==0)$rs->ishui = 1; //已读行字体颜色
		
		$rs->state 			= $this->getstatezt($zt, $rs);
		$rs->stateval   	= $zt;
		
		$rs->startdtsmall	= $this->getstartdtsmall($rs);

		return $rs;
	}
	
	public function getstartdtsmall($rs, $dt='')
	{
		if($dt=='')$dt = nowdt('dt');
		if(contain($rs->startdt, $dt)){
			$starts = nowdt('H:i', $rs->startdt);
		}else{
			$starts = nowdt('m-d H:i', $rs->startdt);
		}
		if(contain($rs->enddt, $dt)){
			$enddts = nowdt('H:i', $rs->enddt);
		}else{
			$enddts = nowdt('m-d H:i', $rs->enddt);
		}
		return ''.$starts.'→'.$enddts.'';
	}
	
	public function getstatezt($zt, $rs)
	{
		if($rs->isturn==0 || $rs->status==0)return $this->getnowstatus($rs);
		return '<font color="'.$this->hyarrb[$zt].'">'.$this->hyarra[$zt].'</font>';
	}
	
	//头部字段1列表，2详情
	protected function flowfieldsname($lx=0)
	{
		if($lx!=1)return;
		$barr['fields_id_after'] = [
			'base_name' 	=> '发起人'
		];
		return $barr;
	}
	
	//提醒设置默认值
	public function flowreminddata()
	{
		return array();
		/*
		$startdt 	= nowdt('', strtotime($this->rs->startdt)-10*60);
		$rateval	= nowdt('Y-m-d H:i:00', strtotime($this->rs->startdt)-5*60);
		return array(
			'startdt' 	=> $startdt,
			'receid'	=> $this->rs->joinid,
			'recename'	=> $this->rs->joinname,
			'todocont'	=> '会议“'.$this->rs->title.'”将在5分钟后的'.nowdt('H:i:s', $this->rs->startdt).'开始，请做好准备',
			'rate'		=> 'o',
			'rateval'	=> $rateval,
			'ratecont'	=> '仅一次 '.$rateval.''
		);*/
	}
	
	
	//提交时触发，加入到提醒表了
	protected function flowsubmit()
	{
		//c('remind', $this->useainfo)->add($this);
	}	
}