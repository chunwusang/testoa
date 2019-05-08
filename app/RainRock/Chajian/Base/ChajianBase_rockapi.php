<?php
/**
*	插件-api的请求
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use Rock;

class ChajianBase_rockapi extends ChajianBase
{
	public function geturl($m, $a='')
	{
		$baseurl= config('rock.baseurl');
		if(isempt($baseurl) || substr($baseurl,0,4)!='http'){
			$baseurl = config('app.urllocal').'/base';
		}
		$url 	= $baseurl.'/index.php?m='.$m.'';
		if($a!='')$url.='&a='.$a.'';
		$apikey = config('rock.basekey');
		if($apikey)$url.='&apikey='.md5($apikey).'';
		$url.='&xinhukey='.config('rock.xinhukey').'';
		return $url;
	}
	
	private function recordchu($barr)
	{
		if(!$barr['success'])return $barr;
		$result	= $barr['data'];
		if(isempt($result))return returnerror('接口没有返回内容');
		if(!contain($result, 'success'))return returnerror($result);
		$barr	= json_decode($result, true);
		return $barr;
	}
	
	/**
	*	$mobiles手机号多个,分开
	*	$tplnum 模版
	*	$device 随机
	*	$provider 发短信平台
	*/
	public function sendsms($mobiles, $tplnum, $params=array(), $provider='',  $device='', $act='send')
	{
		$conf 		= config('rocksms');
		if($provider=='')$provider 	= $conf['provider'];
		$barr = Rock::curlpost($this->geturl('sms',$act), array(
			'mobile' 	=> $mobiles,
			'device' 	=> $device,
			'tplnum' 	=> $tplnum,
			'provider' 	=> $provider,
			'qianming' 	=> $conf[$provider]['sign'],
		),[
			'timeout' => 10
		]);
		return $this->recordchu($barr);
	}
	
	/**
	*	发送验证码
	*/
	public function sendcode($mobile, $gtype, $device, $provider='')
	{
		if(isempt($device) || isempt($gtype))return returnerror('device/gtype不能为空');
		if(!$this->getNei('check')->iscnmobile($mobile))return returnerror('手机号码格式有误');
		$conf 		= config('rocksms');
		if($provider=='')$provider 	= $conf['provider']; //驱动
		return $this->sendsms($mobile, $conf[$provider]['codetpl'], array(), $provider, $gtype.$device, 'getcode');
	}
	
	/**
	*	验证手机验证码
	*/
	public function checkcode($mobile, $code, $gtype, $device)
	{
		if(isempt($device) || isempt($gtype))return returnerror('device/gtype不能为空');
		if(strlen(''.$code.'')!=6)return returnerror('验证码格式错误');
		if(!$this->getNei('check')->iscnmobile($mobile))return returnerror('手机号码格式有误');
		
		$barr = Rock::curlpost($this->geturl('sms','checkcode'), array(
			'mobile' 	=> $mobile,
			'device' 	=> $gtype.$device,
			'code' 		=> $code
		));
		return $this->recordchu($barr);
	}
	
	/**
	*	获取文件下载预览链接
	*	$glx=0预览1下载
	*/
	public function getdownurl($num, $glx=0)
	{
		$barr = Rock::curlpost($this->geturl('updown','geturl'), array(
			'num' 	=> $num,
			'glx' 	=> $glx,
		),[
			'timeout' => 10
		]);
		$barr = $this->recordchu($barr);
		if($barr['code']==404)c('file')->updatedel($num);
		return $barr;
	}
	
	public function curlpost($m, $a, $data=array())
	{
		$curl 	= $this->geturl($m, $a);
		$barr = \Rock::curlpost($curl, $data);
		return $this->recordchu($barr);
	}
}