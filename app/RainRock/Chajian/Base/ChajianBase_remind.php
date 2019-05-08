<?php
/**
*	插件-数组转化
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('array');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\RemindModel;
use App\Model\Base\AgenhModel;
use App\Model\Base\UseraModel;
use App\Model\Base\AgenttodoModel;
use App\Model\Base\AgentModel;
use Rock;
use DB;

class ChajianBase_remind extends ChajianBase
{
	/**
	*	获取主Id
	*/
	public function getmid($mtable, $mid)
	{
		$sid = 0; 
		$frs = $this->getmrs($mtable, $mid);
		if($frs)$sid = $frs->id;
		return $sid;	
	}
	
	/**
	*	获取主记录
	*/
	public function getmrs($mtable, $mid)
	{
		$frs = RemindModel::where('aid', $this->useaid)
							->where('mtable', $mtable)
							->where('mid', $mid)
							->first();
		return $frs;					
	}
	
	/**
	*	提醒包含我
	*/
	public function gettxmall($mtable, $mid, $usra=null, $fid='')
	{
		if($usra==null)$usra = $this->useainfo;
		$frs = RemindModel::where([
					'cid' 	=> $this->companyid,
					'mtable' => $mtable,
					'mid' 	=> $mid,
				]);
		$rows 	= $frs->get();
		$barr 	= array();
		$conobj = $this->getNei('contain');
		$staa	= array();
		foreach($rows as $k=>$rs){
			$receid = $rs->receid;
			if(isempt($receid))continue;
			if(!$conobj->iscontain($receid, $usra))continue;
			$barr[] = $rs;
			if($fid!='')$staa[] = $rs->$fid;
		}
		if($fid!='')return join('<br>', $staa);
		return $barr;					
	}
	
	/**
	*	自动加入到提醒表中
	*/
	public function add($flow)
	{
		Rock::getFlow('remind', $this->useainfo)->addremind($flow);
	}
	
	
	
	/**
	*	这个是在计划任务运行的c('remind')->todorun();
	*/
	public function todorun()
	{
		$this->getremindtodo();
	}
	
	//获取进入需要提醒内容
	private function getreminddt($dt='',$modenum='')
	{
		if($dt=='')$dt = nowdt('dt');
		$dt		= substr($dt, 0, 10);
		$now 	= nowdt();
		$rows 	= RemindModel::where('status', 1)
						->where('startdt','<=', $now)
						->where(function($query){
							$query = $query->whereNull('enddt');
							$query = $query->orWhere('enddt','>=', nowdt());
						})->get();
		$rows	= $rows->toArray();				
		$w 		= date('w', strtotime($dt));
		if($w==0)$w = 7;
		$nw 	= 'w'.$w.'';
		$nrows  = array();
		$timestr= '';
		foreach($rows as $k=>$rs){
			$ratea = explode(',', $rs['rate']);
			$rateb = explode(',', $rs['rateval']);
			$len   = count($ratea);
			for($i=0; $i<$len; $i++){
				$timea = $this->getssdt($dt, $nw, $ratea[$i], arrvalue($rateb, $i), $rs['uid'], $rs['startdt']);
				if($timea)foreach($timea as $time){
					if(!contain($timestr, '['.$time.']')){
						$rs['runtime']  = $time;
						$rs['runtimes'] = date('Y-m-d H:i:s',$time);
						$rs['rates'] 	= $ratea[$i]; //频率类型
						$nrows[] = $rs;
						$timestr.='['.$time.']';
					}
				}
			}
		}
		return $nrows;
	}
	
	//判断时间是否可使用
	private function getssdt($dt, $nw, $rate, $valstr, $uid, $startdts)
	{
		$timea  = array();
		$vala	= explode('|', $valstr);
		$val 	= $vala[0];
		$val2 	= arrvalue($vala, 1);
		
		//仅一次
		if($rate=='o' && contain($val, $dt)){
			$timea[] = strtotime($val);
		}
		
		//星期和天
		if($nw==$rate || $rate=='d'){
			$time = ''.$dt.' '.$val.'';
			$timea[] = strtotime($time);
		}
		//每小时
		if($rate=='h'){
			$ksis	= substr($startdts, 11);
			if(isempt($val2))$val2 = '23:59:59';
			$stime  = strtotime(''.$dt.' '.$ksis.'');
			$etime  = strtotime(''.$dt.' '.$val2.'');
			for($i=0;$i<=23;$i++){
				$time = strtotime(''.$dt.' '.$i.':'.$val.'');
				if($stime<=$time && $etime>=$time)$timea[] = $time;
			}
		}
		//每月
		if($rate=='m'){
			$time = ''.substr($dt,0, 8).''.$val.'';
			if(contain($time, $dt))$timea[] = strtotime($time);
		}
		//每年
		if($rate=='y'){
			$time = ''.substr($dt,0, 5).''.$val.'';
			if(contain($time, $dt))$timea[] = strtotime($time);
		}
		
		//工作日,休息日
		if($rate=='g' || $rate=='x'){
			$time = ''.$dt.' '.$val.'';
			$timea[] = strtotime($time);
		}
		
		return $timea;
	}
	
	//时间段读取
	private function getremindtodo($startdt='', $enddt='')
	{
		if($startdt=='')$startdt = nowdt();
		$stime= strtotime($startdt)-3;
		
		if($enddt=='')$enddt	= date('Y-m-d H:i:s', $stime + 302); //默认是5分钟内提醒
		$dt	  = substr($startdt, 0, 10);
		$rows = $this->getreminddt($startdt); //返回的
		
		
		$etime		= strtotime($enddt);
		$sarr 		= $agenharr = $useaarr	= array();
		$agenhids	= $aids		= array();
	
	
		foreach($rows as $k=>$rs){
			$rate 	= $rs['rates']; //频率类型
			$bo 	= true;
			if($rs['runtime']>=$stime && $rs['runtime']<=$etime){
				//工作日休息日判断
				/*
				if($rate=='g' || $rate=='x'){
					$isw = $kqd->isworkdt($rs['uid'], $dt);
					if($isw==1 && $rate=='x')$bo = false;
					if($isw==0 && $rate=='g')$bo = false;
				}*/
				if($bo){
					if(!in_array($rs['agenhid'], $agenhids))$agenhids[] = $rs['agenhid'];
					if(!in_array($rs['aid'], $agenhids))$aids[] = $rs['aid'];
					$sarr[] = $rs;
				}
			}
		}
		
		if(!$agenhids)return false; //没有应用
		
		//存储应用信息
		$modrs 		= AgenhModel::whereIn('id', $agenhids)->where('status',1)->get();
		foreach($modrs as $k=>$rs)$agenharr[$rs['id']] = $rs;

		//存储用户信息
		$modrs 		= UseraModel::whereIn('id', $aids)->where('status',1)->get();
		foreach($modrs as $k=>$rs)$useaarr[$rs['id']] = $rs;

		
		$flowtodoid = ''; //单据通知设置ID
		$subscribid = array(); //订阅的
		
		foreach($sarr as $k=>$rs){
			$mrs 	= arrvalue($agenharr, $rs['agenhid']);
			if(!$mrs)continue;
			
			//读取用户
			$usea 	= arrvalue($useaarr, $rs['aid']);
			if(!$usea)continue;

			
			/*
			if($rs['modenum']=='flowtodo'){
				$flowtodoid.=','.$mid.'';
				continue;
			}*/
		
			$todocont 	= $rs['todocont']; //提醒内容
			$receid 	= $rs['aid']; //默认提醒给我
			$recename 	= '';
			if(!isempt($rs['receid'])){
				$receid  	= $rs['receid'];
				$recename   = $rs['recename'];
			}
	
			$flow 		= Rock::getFlow($mrs->num, $usea);
			$flow->initdata($rs['mid']);
			
			//订阅的
			/*
			if($rs['modenum']=='subscribe'){
				$subscribid[] = array(
					'id'	=> $mid,
					'uid'	=> $rs['uid'],
					'receid'=> $receid,
					'recename'=> $recename,
				);
				continue;
			}*/
			
			//推送添加提醒
			$flow->todo($receid, $rs['todotit'], $rs['todocont']);
			
			RemindModel::where('id', $rs['id'])->update([
				'lastdt' => nowdt()
			]);
		}
		
		/*
		//单据通知提醒需要另外提醒
		if($flowtodoid !='')$this->flowtodosettx(substr($flowtodoid, 1));
		
		//订阅的处理(建议用异步的)
		if($subscribid){
			if(getconfig('asynsend')){
				print_r($subscribid);
				$reim	= m('reim');
				foreach($subscribid as $subo){
					$reim->asynurl('asynrun','subscribe', array(
						'recename' 	=> $this->rock->jm->base64encode($subo['recename']),
						'receid' 	=> $subo['receid'],
						'id' 		=> $subo['id'],
						'uid'		=> $subo['uid']
					));
				}
			}else{
				//没有异步直接调用
				$subflow = m('flow')->initflow('subscribeinfo');
				foreach($subscribid as $subo){
					$subflow->subscribe($subo['id'],$subo['uid'],$subo['receid'],$subo['recename']);
				}
			}
		}
		*/
		
		return $sarr;
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	*	单据通知设置触发类型计划任务的
	*/
	public function tasktodo()
	{
		$rows = AgenttodoModel::where('status', 1)->where('botask', 1)->get();
		$devobj = $this->getNei('devdata');
		foreach($rows as $k=>$rs){
			$wherestr 	= $rs->wherestr; 
			if($wherestr=='')continue;//没条件不运行
			
			if(!$this->tasktodobool($rs))continue;
			
			$agentinfo 	= AgentModel::find($rs->agentid);
			if(!$agentinfo)continue;
			$wherestr 	= $devobj->replacesql($wherestr, false);
			$mtable 	= $agentinfo->table;
			$jobj 		= DB::table($mtable)->where(['isturn'=>1,'status'=>1]);
			if($agentinfo->mwhere!='')$jobj		= $jobj->whereRaw($agentinfo->mwhere);
			$jobj		= $jobj->whereRaw($wherestr);
			
			$jrows 		= $jobj->orderBy('cid')->get();
			if($jrows)$this->tasktodorunRows($jrows,$agentinfo->num, $rs); //有记录
		}
	}
	private function tasktodorunRows($rows, $num, $todors)
	{
		$cuarr = array();
		foreach($rows as $k=>$rs){
			$cid 	= $rs->cid;
			if(!isset($cuarr[$cid])){
				$aid 		= (int)objvalue($rs,'aid', objvalue($rs,'optid','0'));
				$usera		= UseraModel::find($rs->aid);
				$cuarr[$cid]= $usera;
			}else{
				$usera 	= $cuarr[$cid];
			}
			if(!$usera)continue; //单位用户不存在了
			
			$mid 	= $rs->id;
			$flow	= Rock::getFlow($num, $usera);
			$flow->initdata($mid);
			$flow->agenttodosend($todors); //发提醒
		}
	}
	//判断是否可运行
	private function tasktodobool($rs)
	{
		$tasktype = $rs->tasktype;
		$tasktime = $rs->tasktime;
		if(isempt($tasktype) || isempt($tasktime))return true;
		$ytle1  = 'Y-m-d ';
		$ytle  	= 'H:i:s';
		if($tasktype=='h'){
			$ytle1  = 'Y-m-d H:';
			$ytle  = 'i:s';
		}
		if($tasktype=='m'){
			$ytle1  = 'Y-m-';
			$ytle  = 'd h:i:s';
		}
		$shji  = date($ytle1).date($ytle, strtotime($tasktime)); //运行的时间
		$times = strtotime($shji);
		$stime = time();
		$etime = $stime+5*60;
		if($times>=$stime && $times<=$etime)return true;
		return false;
	}
}