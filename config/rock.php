<?php
/**
*	rock配置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

return [
	
	//开发团队名称
	'xinhu'		=> env('ROCK_XINHU', '信呼开发团队'), 
	
	//平台官网地址，用于在线升级等服务器
	'urly'		=> env('ROCK_URLY', 'http://www.rockoa.com'), 
	
	
	//平台官网地址官网key
	'xinhukey'	=> env('ROCK_XINHUKEY', ''), 
	
	
	//基础地址，如附件上传，可独立部署一个系统，用来实现附件，短信发送，App推送，邮件发送分离等
	'baseurl' 	=> env('ROCK_BASEURL', '/base'),
	
	//连接上面地址密钥
	'basekey'	=> env('ROCK_BASEKEY', ''),
	
	//系统随机密钥
	'randkey'	=> env('ROCK_RANDKEY', ''),
	
	//后台默认的样式
	'adminstyle'=> env('ROCK_ADMINSTYLE', '/bootstrap/css/app_sandstone.css'),
	
	//用户默认的样式
	'usersstyle'=> env('ROCK_USERSSTYLE', '/bootstrap/css/app_cerulean.css'),
	
	//平台类型,dev开发,demo演示
	'systype'	=> env('ROCK_SYSTYPE', ''),
	
	//是否异步发送消息
	'asynsend'	=> env('ROCK_ASYNSEND', false)
];
