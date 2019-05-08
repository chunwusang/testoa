<?php
/**
*	应用.日程
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_schedule  extends Rockflow
{
	
	protected function flowinit()
	{
		$this->changeStatus();
		$this->month = nowdt('Y-m');
		$this->dtobj = c('date');
		$this->remindobj = $this->getNei('remind');
	}	
	
	protected function flowfieldsname()
	{
		return [
			//Id后面
			'fields_id_after' => [
				'base_name' 	=> '填写人'
			]
		];
	}
	
	protected function flowlistoption()
	{
		$barr = array();
		if($this->pnum=='month'){
			$barr[] 	 = [
				'name' => '新增日程',
				'click'=> 'addschedule',
				'icons'=> 'plus',
				'class'=> 'primary'
			];
			$barr[] 	 = [
				'name' => '日程管理',
				'click'=> 'guanloi',
				'icons'=> 'cog',
				'class'=> 'info'
			];
		}else{
			$barr[] 	 = [
				'name' => ''.$this->agenhname.'月视图',
				'click'=> 'yeushitu',
				'class'=> 'info',
				'icons'=> 'calendar'
			];
		}
		return $barr;
	}
	
	public function flowreplacers($rs)
	{
		//提醒频率
		$rs->pinglv = $this->remindobj->gettxmall($this->mtable, $rs->id, $this->useainfo,'ratecont');
		if(!isempt($rs->enddt) && $rs->enddt<nowdt())$rs->ishui = 1;
		
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		//今日日程
		if($atype=='today'){
			return $obj->where('startdt','<',date('Y-m-d 23:59:59'))
					->where(function($query){
						$query->whereNull('enddt');
						$query->oRwhere('enddt','>',date('Y-m-d 00:00:00'));
					});
					
		}
		
		//近7天日程
		if($atype=='week'){
			$qte = date('Y-m-d', strtotime('+7 day'));
			return $obj->where('startdt','<', ''.$qte.' 23:59:59')
					->where(function($query){
						$query->whereNull('enddt');
						$query->oRwhere('enddt','>',date('Y-m-d 00:00:00'));
					});
		}
		
		if($atype=='month'){
			$startdt = ''.$this->month.'-01';
			$enddt   	 = $this->dtobj->getenddt($this->month);
			return $obj->where('startdt','<', ''.$enddt.' 23:59:59')
					->where(function($query)use($startdt){
						$query->whereNull('enddt');
						$query->oRwhere('enddt','>', ''.$startdt.' 00:00:00');
					});
		}
		
		return false;
	}
	
	/**
	*	获取月视图数据
	*/
	public function get_montylist($request)
	{
		$month = $request->get('month');
		$max   = $this->dtobj->getmaxdt($month);
		$this->month = $month;
		
		$obj 	= $this->getWhereobj('month');
		$data   = $obj->get();
		$nd 	= nowdt('dt');
		$barr 	= array();
		for($d=1;$d<=$max; $d++){
			$d1 = $d;
			if($d1<10)$d1='0'.$d.'';
			$dt = ''.$month.'-'.$d1.'';
			$str= '';
			$xu = 0;
			foreach($data as $k=>$rs){
				$startdt = substr($rs->startdt, 0, 10);
				if($startdt>$dt)continue;
				if(!isempt($rs->enddt) && substr($rs->enddt, 0, 10)<$dt)continue;
				if($str!='')$str.='<br>';
				$xu++;
				$str.=''.$xu.'.['.substr($rs->startdt,11,5).']:'.$rs->title.'';
			}
			if($str!=''){
				if($dt<$nd)$str='<font color="#aaaaaa">'.$str.'</font>';
				if($dt>$nd)$str='<font color="#555555">'.$str.'</font>';
				if($dt==$nd)$str='<b>'.$str.'</b>';
			}
			$barr[$d] = $str;
		}
		return returnsuccess($barr);
	}
	
	//提醒设置默认值
	public function flowreminddata()
	{
		return array(
			'startdt' 	=> $this->rs->startdt,
			'enddt' 	=> $this->rs->enddt,
			'receid'	=> $this->rs->receid,
			'recename'	=> $this->rs->recename,
		);
	}
}