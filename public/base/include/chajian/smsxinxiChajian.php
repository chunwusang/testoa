<?php
/**
*	企业信使短信服务api
*	官网：http://web.1xinxi.cn/default.aspx
*/
class smsxinxiChajian extends Chajian{
	
	private $pwdkey;
	private $userkey;
	private $sendurl 	= 'http://sms.1xinxi.cn/asmx/smsservice.aspx';
	
	protected function initChajian()
	{
		//短信模版设置
		$this->contarr	= array(
			'code' => '您短信验证码为：{code}，请勿将验证码提供给他人，五分钟内有效。'
		);
		
		$this->userkey 		= getconfig('smsxinxi_userkey');
		$this->pwdkey 		= getconfig('smsxinxi_pwdkey');
	}
	
	/**
	*	批量发送短信
	*/
	public function send($mobile, $qianm, $tplid, $cans=array())
	{
		$cont = arrvalue($this->contarr, $tplid);
		if(isempt($cont))return returnerror('短信内容不能为空');
		foreach($cans as $k=>$v)$cont = str_replace('{'.$k.'}', $v, $cont); //内容替换
		$argv = array( 
			'name'		=> $this->userkey,     //必填参数。用户账号
			'pwd'		=> $this->pwdkey,     //必填参数。（web平台：基本资料中的接口密码）
			'content'	=> $cont, 
			'mobile'	=> $mobile,   //必填参数。手机号码。多个以英文逗号隔开
			'stime'		=> '',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
			'sign'		=> $qianm,    //必填参数。用户签名。
			'type'		=> 'pt',  //必填参数。固定值 pt
			'extno'		=> ''    //可选参数，扩展码，用户定义扩展码，只能为数字
		);
		$str = '';
		$url = ''.$this->sendurl.'?';
		foreach($argv as $k=>$v){
			$str.='&'.$k.'='.urlencode($v).'';
		}
		$str   = substr($str, 1);
		$url  .= $str;
		@$rect = c('curl')->getcurl($url);
		$con   = substr($rect , 0, 1 );
		if($con=='0'){
			return returnsuccess('ok');
		}else{
			return returnerror('失败:'.$rect.'');
		}
	}
}