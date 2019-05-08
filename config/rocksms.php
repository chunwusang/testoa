<?php
/**
*	短信服务的配置文件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

return [

    'provider' 	=> env('ROCK_SMSPROVIDER', 'smsxinhu'), //驱动:smsali,smsxinxi,smsxinhu
	
	
	
	//用阿里云短信的
	'smsali'	=> [
		'sign'		=> '信呼', //短信签名
		'codetpl'	=> 'SMS_132095174', //短信验证码的模版，模版内容必须包含变量code
	],
	
	//企业信使
	'smsxinxi'	=> [
		'sign'		=> '信呼',
		'codetpl'	=> 'code',
	],
	
	//云片网(待开发)
	'smsyunpian'=> [
		'sign'		=> '信呼OA',
		'codetpl'	=> 'code',
	],
	
	//信呼官网短信服务
	'smsxinhu'	=> [
		'sign'		=> 'xinhuyun',	 //默认签名编号，签名是：信呼OA云平台
		'codetpl'	=> 'defyzm', //短信验证码模版编号
	]
];
