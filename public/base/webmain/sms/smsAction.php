<?php
/**
*	短信服务
*	主页：http://www.rockoa.com/
*	软件：信呼平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

class smsClassAction extends Action
{
	
	public function initAction()
	{
		$this->providerarr = array('smsali','smsxinxi','smsxinhu');
	}
	
	/**
	*	发送
	*/
	public function sendAction()
	{
		$provider	= $this->post('provider');
		$qianming	= $this->post('qianming'); //签名
		$tplnum		= $this->post('tplnum');
		$mobile		= $this->post('mobile');
		if(!in_array($provider, $this->providerarr))return returnerror('无效provider');
		if(isempt($mobile))return returnerror('手机号不能为空');
		
		$barr 		=  c($provider)->send($mobile, $qianming, $tplnum);
		if(!$barr['success'])return $barr;
		
		return returnsuccess();
	}
	
	/**
	*	获取验证码
	*/
	public function getcodeAction()
	{
		$provider	= $this->post('provider');
		$qianming	= $this->post('qianming'); //签名
		$device		= $this->post('device');
		$tplnum		= $this->post('tplnum');
		$mobile		= $this->post('mobile');
		if(!in_array($provider, $this->providerarr))return returnerror('无效provider');
		
		if(!c('check')->iscnmobile($mobile))return returnerror('无效手机号码');
		if(isempt($device))return returnerror('device不能为空');
		
		$dbs 			= m('basesms');
		$lars 			= $dbs->getone("`mobile`='$mobile' or `device`='$device'",'optdt', 'id desc');
		if($lars){
			$otme 	= strtotime($lars['optdt']);
			$jgtims = 60;//每次获取间隔秒数
			$jgtime	= time()-$otme;
			if($otme>0 && $jgtime<$jgtims)return returnerror('获取太频繁,请'.($jgtims-$jgtime).'秒后在试');
		}
		
		$code 			= rand(100000,999999);//随机验证码
		$params['code']	= $code;
		$barr 			= c($provider)->send($mobile, $qianming, $tplnum, $params);
		//$barr 			= returnsuccess();
		if(!$barr['success'])return $barr;
		
		//保存到数据库
		$sarr['code'] 	= $code;
		$sarr['device'] = $device;
		$sarr['mobile'] = $mobile;
		$sarr['ip'] 	= $this->rock->ip;
		$sarr['web'] 	= $this->rock->web;
		$sarr['optdt'] 	= $this->rock->now;
		$dbs->insert($sarr);
		
		return returnsuccess();
	}
		
	/**
	*	验证
	*/		
	public function checkcodeAction()
	{
		$device		= $this->post('device');
		$code		= $this->post('code');
		$mobile		= $this->post('mobile');
		
		if(isempt($mobile))return returnerror('手机号不能为空');
		if(isempt($code))return returnerror('验证码不能为空');
		if(isempt($device))return returnerror('device不能为空');
		if(strlen(''.$code.'')!=6)return returnerror('验证码必须是6位数字');
		
		$youxiaq= 5*60;
		$dbs 	= m('basesms');
		$optdt 	= date('Y-m-d H:i:s', time()-$youxiaq);
		$ors 	= $dbs->getone("`mobile`='$mobile' and `device`='$device' and `optdt`>'$optdt' and `status`<5",'`optdt`,`code`,`id`','`id` desc');
		
		if(!$ors)return returnerror('请先获取验证码');
	
		if($code != $ors['code'])return returnerror('验证码错误');
		$dbs->update('`status`=`status`+1', $ors['id']);  //验证次数
		
		return returnsuccess('ok');
	}		
}