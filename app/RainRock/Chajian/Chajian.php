<?php
/**
*	插件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian;



abstract class Chajian
{
	//平台用户信息，可不用设置
	public $userinfo;
	public $userid;
	
	//单位下用户信息
	public $useainfo;
	public $useaid 			= 0;
	public $companyid 		= 0;
	public $companyinfo;
	
	protected $basepath		= 'Base';
	
	protected function initChajian(){}
	
	public function __construct($usera=null)
	{
		$this->initUsera($usera);
		$this->initChajian();
	}
	
	/**
	*	初始化当前用户
	*/
	public function initUsera($usera)
	{
		$this->useainfo = $usera;
		if($usera){
			$this->useaid 	 = $usera->id;
			$this->companyid = $usera->cid;
			$this->userid 	 = $usera->uid;
			$this->companyinfo = $usera->company;
		}
	}
	
	/**
	*	引用自己内部插件
	*/
	protected function getNei($cls)
	{
		$clsa= explode(':', $cls);
		$cls = $clsa[0];
		$path= $this->basepath;
		if(isset($clsa[1])){
			$cls = $clsa[1];
			$path= $clsa[0];
		}
		$cls = 'App\RainRock\Chajian\\'.$path.'\Chajian'.$path.'_'.$cls.'';
		if(!class_exists($cls))return false;
		return new $cls($this->useainfo);
	}
	
	public function getFlow($num)
	{
		return \Rock::getFlow($num, $this->useainfo);
	}
	
	/**
		字段中包含
	*/
	public function dbinstr($fiekd, $str, $spl1=',', $spl2=',')
	{
		return "instr(concat('$spl1', $fiekd, '$spl2'), '".$spl1.$str.$spl2."')>0";
	}
	
	/**
	*	获取应用的Model
	*/
	public function getModel($nus)
	{
		$obj	= null;
		$cls 	= 'App\Model\Agent\Rockagent_'.$nus.'';
		if(class_exists($cls))$obj 	= new $cls();
		return $obj;
	}
}