<?php
/**
*	插件-空
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_base extends ChajianBase
{
	/**
	*	用户浏览器
	*/
	public function getuseragent($lx=0)
	{
		$val = arrvalue($_SERVER,'HTTP_USER_AGENT');
		if($val=='')$val = 'Mozilla/4.0 (XINHUOA CLOUDPLAT V'.config('version').')';
		return $val;
	}
	
	public function gethost($lx=0)
	{
		$val = arrvalue($_SERVER,'HTTP_HOST');
		if($lx==1)$val = $this->getNei('rockjm')->base64encode($val);
		return $val;
	}
	
	/**
	*	获取浏览器类型
	*/
	public function getbrowser()
	{
		$web 	= $this->getuseragent();
		$val	= '';
		$parr	= array(
			array('MSIE 5'),array('MSIE 6'),array('XIAOMI','xiaomi'),array('HUAWEI','huawei'),array('XINHUAPP','xinhu'),array('DingTalk','ding'),array('MSIE 7'),array('MSIE 8'),array('MSIE 9'),array('MSIE 10'),array('MSIE 11'),array('rv:11','MSIE 11'),array('MSIE 12'),array('MicroMessenger','wxbro'),
			array('MSIE 13'),array('Firefox'),array('OPR/','Opera'),array('Chrome'),array('Safari'),array('Android'),array('iPhone')
		);
		foreach($parr as $wp){
			if(contain($web, $wp[0])){
				$val	= $wp[0];
				if(isset($wp[1]))$val	= $wp[1];
				break;
			}
		}
		$web = strtolower($web);
		if(contain($web,'micromessenger'))$val='wxbro';//微信浏览器
		if(contain($web,'dingtalk'))$val='ding';//钉钉浏览器
		if($val=='wxbro' && contain($web, 'wxwork'))$val = 'wxwork'; //企业微信的
		return $val;
	}
	
	/**
	*	获取IP
	*/
	public function getclientip()
	{
		$ip = 'unknow';
		if(isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else if(isset($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
	
	/**
	*	创建文件夹
	*/
	public function createdir($path, $oi=1)
	{
		$zpath	= explode('/', $path);
		$len    = count($zpath);
		$mkdir	= '';
		$luj  	= str_replace('\\','/', base_path());
		for($i=0; $i<$len-$oi; $i++){
			if(!isempt($zpath[$i])){
				$mkdir.='/'.$zpath[$i].'';
				$wzdir = $luj.''.$mkdir;
				if(!is_dir($wzdir)){
					@$bo = mkdir($wzdir);
					if(!$bo)return false;
				}
			}
		}
		return true;
	}
	
	public function ismobile()
	{
		$web 	= strtolower($this->getuseragent());
		$bo 	= 0;
		$strar	= explode(',','micromessenger,android,mobile,iphone,xinhuapp');
		foreach($strar as $str){
			if(contain($web, $str))return 1;
		}
		return $bo;
	}
	
	/**
	*	返回文件大小
	*/
	public function formatsize($size)
	{
		$arr = array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
		if($size == 0)return '0';
		$e = floor(log($size)/log(1024));
		return number_format(($size/pow(1024,floor($e))),2,'.','').' '.$arr[$e];
	}
	
	/*
	*	获取当前访问全部url
	*/
	public function nowurl()
	{
		if(!isset($_SERVER['HTTP_HOST']))return '';
		$qz  = 'http';
		if($_SERVER['SERVER_PORT']==443)$qz='https';
		$url = ''.$qz.'://'.$_SERVER['HTTP_HOST'];
		if(isset($_SERVER['REQUEST_URI']))$url.= $_SERVER['REQUEST_URI'];
		return $url;
	}
	
	/**
	*	小数点
	*/
	public function number($num,$w=2)
	{
		if(!$num)$num='0';
		return number_format($num,$w,'.','');
	}
}