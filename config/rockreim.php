<?php
/**
*	rock队列配置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-09
*/

return [
	
	'title'		=> env('ROCKREIM_TITLE', 'REIM'),
	
	//服务进程名称
	'name' 		=> 'rockreimcloud',
	
	//队列主机IP
	'ip'		=> env('ROCKQUEUE_IP','127.0.0.1'),
	
	//队列主机端口号
	'port'		=> env('ROCKQUEUE_PORT',7839),
	
	//node的路径
	'nodepath'	=> env('ROCKREIM_NODEPATH','F:/soft/nodejs/node.exe'),
	
	
	//REIM的IP
	'reimip'	=> env('ROCKREIM_IP', '0.0.0.0'),
	
	//REIM端口号
	'reimport'	=> env('ROCKREIM_PORT',6551),
	
	//REIM客户端连接地址就是格式：ws://127.0.0.1:6551，端口号跟上面的一样
	'reimclient'=> env('ROCKREIM_CLIENT', 'ws://127.0.0.1:6552'),
	
	//服务端receid
	'reimfrom'  => env('ROCKREIM_RECID', 'reimcloud'),
	
	//REIM服务端允许连接的客户端如：cloud.rockoa.com,127.0.0.1,多个,分开，*不限制
	'reimorigin' => env('ROCKREIM_ORIGIN', '*')
];
