<?php
/**
*	插件-计划任务执行的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Task;

use App\Model\Base\TaskModel;

class ChajianTask_start extends ChajianTask
{
	
	/**
	*	运行计划任务
	*	$taskid 计划任务id
	*	使用：c('Task:start')->run(任务id)
	*/
	public function run($taskid)
	{
		$trs 	= TaskModel::where('id', $taskid)->first();
		if(!$trs)return 'task('.$taskid.') not found';
		if($trs->status==0)return 'task('.$taskid.') disconnected stop';
		$urla 	= explode(',', $trs->url);
		$mode   = $urla[0];
		$act 	= arrvalue($urla, 1, 'run');
		$bstr 	= c('Task:'.$mode.'')->$act($trs);
		$state 	= 2;
		if($bstr=='success')$state = 1;
		$trs->state 	= $state;
		$trs->lastdt 	= nowdt();
		$trs->lastcont 	= $bstr;
		$trs->save();
		return $bstr;
	}
	
	/**
	*	初始化，win生成.bat文件
	*/
	public function init()
	{
		$base1	= str_replace('\\','/', base_path());
		if(contain(PHP_OS,'WIN')){
			$path 	= ''.$base1.'/storage/app/rocktaskrun.bat';
			$str 	= "@echo off\nphp ".$base1."/artisan rock:taskrun";
			@file_put_contents($path, $str);
		}else{
			$path 	= '';
		}
		
		return ''.PHP_OS.' taskinit ok '.$path.'';
	}
	
	/**
	*	开始运行任务，每5分钟访问这个方法
	*	php artisan rock:taskrun
	*	运行方法：c('Task:start')->runtask();
	*/
	public function runtask($time=0)
	{
		if($time==0)$time = time()+10;		
		$barr 	= $this->getrunlist();
		$oi 	= $cg = $sb = 0;
		$ntime 	= strtotime(date('Y-m-d H:i:00', $time));
		foreach($barr as $k=>$rs){
			if($rs['runtime']==$ntime){
				$oi++;
				$cont = $this->run($rs['id']);
				if($cont=='success'){
					$cg++;
				}else{
					$sb++;
				}
			}
		}
		$msg 	= 'taskrun('.$oi.'),success('.$cg.'),fail('.$sb.')';
		addlogs($msg,'taskrun');
		return $msg;
	}
	//读取计划任务运行列表
	private function getrunlist()
	{
		$dt		= nowdt('dt');
		$rows 	= TaskModel::where('status', 1)->get()->toArray();
		$ntime  = time();
		$runa	= array();
		$sdts	= strtotime($dt);
		$edts	= $ntime+600;
		$ntime	= $ntime-20;//稍微减一下防止出现跳过的
		$brows	= array();
		$w 		= (int)date('w', $sdts);if($w==0)$w=7;//星期7
		foreach($rows as $k=>$rs){
			$ate = explode(',', $rs['type']);
			$ati = explode(',', $rs['time']);
			if(count($ate)!=count($ati))continue;
			$len = count($ate);
			for($i=0;$i<$len;$i++){
				$rs['type'] = $ate[$i];
				$rs['time'] = $ati[$i];
				$brows[] = $rs;
			}
		}
		
		foreach($brows as $k=>$rs){
			$type 	= $rs['type'];
			$atime  = $rs['time'];
			
			$jg		= (int)str_replace(array('d','i','h','m','w'),array('','','','',''), $type);
			if($jg==0)$jg=1;
			$type 	= str_replace($jg,'', $type);
			$jgs 	= $jg; if($jg<10)$jgs = '0'.$jg.'';
			$time 	= '';
			//每天
			if($type=='d'){
				$time = $dt.' '.$rs['time'];
				$rs['runtimes'] 	= $time;
				$rs['runtime'] = strtotime($time);
				$runa[] = $rs;
			}
			//分钟
			if($type=='i'){
				$ges = $jg*60;
				for($i=$sdts;$i<=$edts;$i=$i+$ges){
					$rs['runtimes'] = date('Y-m-d H:i:s', $i);
					$rs['runtime']  = $i;
					$runa[] = $rs;
				}
			}
			//小时
			if($type=='h'){
				for($i=0;$i<=23;$i=$i+$jg){
					$time 			= date('Y-m-d H:'.$atime.'', $sdts+$i*3600);
					$rs['runtimes'] = $time;
					$rs['runtime'] 	= strtotime($time);
					$runa[] = $rs;
				}
			}
			//每月
			if($type=='m'){
				$time 			= date('Y-m-'.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
			//周
			if($type=='w' && $jg==$w){
				$time 			= date('Y-m-d '.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
			//每年
			if($type=='y'){
				$time 			= date('Y-'.$atime.'');
				$rs['runtimes'] = $time;
				$rs['runtime'] 	= strtotime($time);
				$runa[] = $rs;
			}
		}
		$brun	= array();
		foreach($runa as $k=>$rs){
			$_runti = $rs['runtime'];
			if($_runti >= $ntime && $_runti<=$edts)$brun[]=$rs;
		}
		return $brun;
	}
}