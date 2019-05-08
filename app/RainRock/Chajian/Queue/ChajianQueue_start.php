<?php
/**
*	插件-队列运行
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Queue;

use App\Model\Base\RockqueueModel;
use App\Model\Base\UseraModel;

class ChajianQueue_start extends ChajianQueue
{
	/**
	*	推送到队列上
	*	$atype 类型，微信
	*	$mstr主方法 $act 方法 $runtime 0 马上运行
	*	c('Queue:start')->push('sms','sendcode');
	*/
	public function push($mstr, $act,$params='',$atype='',$title='',$runtime=0)
	{
		if($atype=='')$atype = '默认';
		if(is_array($params))$params = json_encode($params);
		$obj 		= new RockqueueModel();
		$url 		= ''.$mstr.':'.$act.'';
		$obj->cid 	= $this->companyid;
		$obj->aid 	= $this->useaid;
		$obj->atype = $atype;
		$obj->title = $title;
		$obj->url 	= $url;
		$obj->rundt = nowdt('now', $runtime);
		$obj->params= $params;
		$obj->optdt = nowdt();
		$obj->save();
		$id = $obj->id;
		//socket 推送的node 的服务上
		$type	= 'cmd';
		$base1	= str_replace('\\','/', base_path());
		$url 	= 'php '.$base1.'/artisan rock:reim run-'.$id.'';
		$rarr[] = array(
			'type'		=> $type,
			'runtime'	=> $runtime,
			'url'		=> $url,
			'id'		=> $id
		);
		$conf 	= config('rockreim');
		$barr 	= c('socket')->udpsend($rarr, $conf['ip'], $conf['port']);
		if($barr['success'])$barr['data'] = $obj;
		return $barr;
	}
	
	public function test()
	{
		return 'success';
	}
	
	/**
	*	运行队列
	*	php artisan rock:reim run-1
	*	$queid = 队列id
	*/
	public function run($queid)
	{
		$queobj = RockqueueModel::find($queid);
		if(!$queobj)return 'not found('.$queid.')';
		$url 	= $queobj->url;
		$status	= 2;
		$runcont= '';
		$params	= $queobj['params'];
		if(!isempt($params))$params = json_decode($params, true);
		if(contain($url,':')){
			$acta 	= explode(':', $url);
			$act  	= arrvalue($acta, 1, 'run');
			$usera	= null;
			if($queobj->aid>0)$usera = UseraModel::find($queobj->aid);
			$obj	= c('Queue:'.$acta[0].'', $usera);
			if(method_exists($obj, $act)){
				$rarr= $obj->$act($params);
				if(is_string($rarr) || !$rarr){
					$runcont = $rarr;
					if($runcont=='success')$status = 1;
				}else if($rarr['success']){
					$status = 1;
					$runcont= $rarr['data'];
				}else{
					$runcont= $rarr['msg'];
				}
			}
		}else{
			$barr = \Rock::curlget($url);
			if($barr['success']){
				$status = 1;
				$runcont= $barr['data'];
			}else{
				$runcont= $barr['msg'];
			}
		}
		
		if(!$runcont)$runcont='';
		if(is_array($runcont))$runcont = json_encode($runcont);
		$queobj->runcont = $runcont;
		$queobj->status = $status;
		$queobj->lastdt = nowdt();
		$queobj->save();
		return $runcont;
	}
	
	
	/**
	*	队列任务，开启一个UDP端口来接收数据的处理的，异步
	*/
	public function runstart()
	{
		echo 'rockreim runing...'.chr(10).'';
		cli_set_process_title(config('rockreim.name')); //设置进程名称
		while(true){
			
		}
	}
	
	/**
	*	初始化，创建配置文件
	*	php artisan rock:reim init
	*/
	public function init()
	{
		$base1	= str_replace('\\','/', base_path());
		$conf 	= config('rockreim');
		$name 	= $conf['name'];
$str = "/**
*	REIM服务端和队列配置文件信息
*	开发者：雨中磐石(rainrock)
*	网址：http://www.rockoa.com/
*	时间：2018-07-01
*/


var DEUBG 	= ".(config('app.debug')?'true':'false').";
var Config 	= {
	
	getDebug:function(){
		return DEUBG;
	},
	
	getSyspath:function(){
		return 'php ".$base1."/artisan rock:reim client-';
	},
	
	getConfig:function(){
		return {
			'ip'   : '".$conf['ip']."',
			'port' : ".$conf['port'].",
			'reimip'   : '".$conf['reimip']."',
			'reimport' : ".$conf['reimport'].",
			'reimorigin' : '".$conf['reimorigin']."'
		}
	}
};

module.exports = Config;";
		
		$path 	= ''.$base1.'/reimserver/config.js';
		file_put_contents($path, $str);
		$ostr1	= '';
		if(contain(PHP_OS,'WIN')){
			$nssm 		= str_replace('/','\\', ''.$base1.'/reimserver/nssm.exe');
			$nodepath 	= str_replace('/','\\', $conf['nodepath']);
			$fpath 		= str_replace('/','\\', ''.$base1.'\reimserver\rockreim.js');

//创建安装的			
$cmstr = '@echo off
'.$nssm.' stop '.$name.'
sc delete '.$name.'
'.$nssm.' install '.$name.' '.$nodepath.' '.$fpath.'
'.$nssm.' set '.$name.' DisplayName "xinhuoa platform reimserver"
'.$nssm.' set '.$name.' Description "xinhuoa platform reimserver,from www.rockoa.com,rainrock"
echo '.$name.' install success
cmd';
			file_put_contents(''.$base1.'/reimserver/reiminstall.bat', $cmstr);

//重启			
$cmstr = '@echo off
'.$nssm.' restart '.$name.'
cmd';
			file_put_contents(''.$base1.'/reimserver/reimrestart.bat', $cmstr);
//删除
$cmstr = '@echo off
'.$nssm.' stop '.$name.'
'.$nssm.' remove '.$name.'
cmd';
			file_put_contents(''.$base1.'/reimserver/reimremove.bat', $cmstr);			
			
		}else{
			
$strss ='nohup '.$conf['nodepath'].' '.$base1.'/reimserver/rockreim.js > '.$base1.'/storage/logs/${NOWDT}'.$name.'_service.log 2>&1 &'."\n";			

$yun = '#!/bin/sh
cd '.$base1.'
NAME=rockreim
PROCESS=`ps -ef|grep "$NAME"|grep -v grep|grep -v PPID|awk \'{ print $2}\'`
for i in $PROCESS
do
  echo "Kill the $NAME process [ $i ]"
  kill -9 $i
done
echo "stop $NAME"';

$yun1 = $yun.'
NOWDT=`date +"%Y-%m-%d.%H.%M.%S"`
'.$strss.'echo "start $NAME success"';

			$pahts = ''.$base1.'/reimserver/reimstart.sh';
			file_put_contents($pahts, str_replace('^M','', $yun1));
			if(!file_exists($pahts))return 'init '.$this->showstr(false).'';
			if(function_exists('exec'))exec('chmod 755 '.$pahts.'');
			
			$pahts = ''.$base1.'/reimserver/reimstop.sh';
			file_put_contents($pahts, str_replace('^M','', $yun));
			if(function_exists('exec'))exec('chmod 755 '.$pahts.'');
			
		}
		
		return ''.PHP_OS.' reiminit '.$this->showstr(true).'';
	}
	
	private function showstr($val)
	{
		if(contain(PHP_OS,'WIN')){
			if($val){
			   return '[OK]';
			}
			else{
			   return '[fail]';
			}
		}else{
			if($val){
			   return "\033[32;40m [OK] \033[0m";
			}
			else{
			   return "\033[31;40m [fail] \033[0m";
			}
		}
	}
	
	/**
	*	开启
	*/
	public function start()
	{
		$base1	= str_replace('\\','/', base_path());
		return 'start reimserver '.$this->showstr(false).''.chr(10).'Please enter:'.chr(10).'reimserver/reimstart.sh';
	}
	
	public function stop()
	{
		$base1	= str_replace('\\','/', base_path());
		//exec("ps aux | grep rockreim | grep -v grep | awk '{print $2}' |xargs kill -SIGKILL"); //SIGINT
		return 'stop reimserver '.$this->showstr(false).''.chr(10).'Please enter:'.chr(10).'reimserver/reimstop.sh';
	}
}