<?php
/**
*	插件-Socket
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('log')->add('');
*/

namespace App\RainRock\Chajian\Base;


class ChajianBase_socket extends ChajianBase
{
	
	/**
	*	UDP发送文本
	*/
	public function udpsend($str, $ip=0, $port=0, $waitbo=false)
	{
		if(!function_exists('stream_socket_client'))return returnerror('没有开启Socket组件');
		$handle = stream_socket_client("udp://{$ip}:{$port}", $errno, $errstr);
		if( !$handle ){
			return returnerror("ERROR: {$errno} - {$errstr}");
		}
		if(is_array($str))$str = json_encode($str);
		$result	= '';
		fwrite($handle, $str);
		if($waitbo)$result = fread($handle, 1024);
		fclose($handle);
		return returnsuccess($result);
	}
}