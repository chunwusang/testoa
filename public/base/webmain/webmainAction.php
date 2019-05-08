<?php 

require(ROOT_PATH.'/include/Action.php');

class Action extends mainAction
{
	public $splittime	= 0;//你服务器的时间差，计算他们的时间+
	public $accessbool	= true;//是否需要验证apikey
	
	public function initProject()
	{
		$this->display	= false;
		if($this->accessbool)$this->checkaccess();
	}
	
	public function checkaccess()
	{
		$apikey 		= $this->get('apikey');
		$myikey			= getconfig('apikey');
		if($myikey && md5($myikey)!=$apikey)showreturn('', '无效的apikey', 201);
	}
	
	public function createtoken()
	{
		return md5($this->rock->HTTPweb.'_'.getheader('Referer').'_'.getconfig('apikey').'');
	}
	
	
}