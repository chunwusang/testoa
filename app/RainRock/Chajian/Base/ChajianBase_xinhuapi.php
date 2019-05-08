<?php
/**
*	插件-xinhuapi的请求
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use Rock;

class ChajianBase_xinhuapi extends ChajianBase
{
	private function geturl($act)
	{
		$bboj= c('base');
		$url = config('rock.urly').'/api.php?a='.$act.'';
		$can['cfrom'] 	= 'cloud';
		$can['xinhukey']= config('rock.xinhukey');
		$can['version'] = config('version');
		$can['host'] 	= $bboj->gethost(1);
		$can['ip'] 		= $bboj->getclientip();
		$can['web'] 	= $bboj->getbrowser();
		$can['time'] 	= time();
		$can['randkey'] = config('rock.randkey');
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	private function recordchu($barr)
	{
		if(!$barr['success'])return $barr;
		$result	= $barr['data'];
		if(isempt($result))return returnerror('接口没有返回内容');
		if(substr($result,0,1)!='{')return returnerror($result);
		$barr	= json_decode($result, true);
		return $barr;
	}
	
	public function get($act, $can=array())
	{
		$url  = $this->geturl($act);
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
	
		$barr = Rock::curlget($url);
		return $this->recordchu($barr);
	}
}