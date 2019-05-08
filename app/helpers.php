<?php
/**
*	定义常用的方法
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-01-01
*/

if (! function_exists('isempt')) {
    /**
     *	判断变量是否为空
	 *	@return boolean
     */
    function isempt($str)
    {
        $bool=false;
		if( ($str==''||$str==NULL||empty($str)) && (!is_numeric($str)) )$bool=true;//is_numeric如果是传进来的是数字和字符串则返回true,反之为false;
		return $bool;
    }
}

if (! function_exists('contain')) {
	/**
	*	判断变量是否包含在另一变量里面
	*	@return boolean
	*/
	function contain($str,$a, $jg='')
	{
		$bool=false;
		if(!isempt($a) && !isempt($str)){
			$ad=strpos($jg.$str.$jg,$jg.$a.$jg);
			if($ad>0||!is_bool($ad))$bool=true;
		}
		return $bool;
	}
}

if (! function_exists('arrvalue')) {
	/**
	*	获取数组上对应值
	*	@return object
	*/
	function arrvalue($arr,$k,$dev='')
	{
		$val  = $dev;
		if(isset($arr[$k]))$val= $arr[$k];
		if(isempt($val) && !isempt($dev))$val = $dev;
		return $val;
	}
}

if (! function_exists('objvalue')) {
	/**
	*	获取对象上对应值
	*	@return object
	*/
	function objvalue($arr,$k,$dev='')
	{
		$val  = $dev;
		if(isset($arr->$k))$val= $arr->$k;
		if(isempt($val) && !isempt($dev))$val = $dev;
		return $val;
	}
}

if (! function_exists('addlogs')) {
	/**
	*	保存日志
	*/
	function addlogs($data,$key='')
	{
		if(is_array($data))$data = json_encode($data, JSON_UNESCAPED_UNICODE);
		$file = storage_path('logs').'/'.date('Y-m-d').''.$key.'.log';
		
		$url	= '';//记录请求URL
		if(isset($_SERVER['HTTP_HOST']))$url = 'http://'.$_SERVER['HTTP_HOST'].'';
		if(isset($_SERVER['REQUEST_URI']))$url.= $_SERVER['REQUEST_URI'];
		
		$output = date('Y-m-d H:i:s'). " ";
		if($url!='')$output .='['.$url.'] ';
		$output .= $data."\n\n" ;
		
		@file_put_contents($file, $output, FILE_APPEND | LOCK_EX);
	}
}

if (! function_exists('returnerror')) {
	/**
	*	返回错误信息
	*/
	function returnerror($msg='', $code=422, $carr=array())
	{
		$carr['msg']  		= $msg;
		$carr['success'] 	= false;
		$carr['code'] 		= $code;
		if(!isset($carr['data']))$carr['data'] 	= '';
		return $carr;
	}
}

if (! function_exists('returnerrors')) {
	/**
	*	返回字段错误信息
	*/
	function returnerrors($fid, $msg='', $code=422, $carr=array())
	{
		$errs = [$fid=>[''.$msg.'']];
		return returnerror($errs, $code, $carr);
	}
}

if (! function_exists('returnsuccess')) {
	/**
	*	返回正确信息
	*/
	function returnsuccess($data=array(), $msg='')
	{
		$carr['msg']  		= $msg;
		$carr['success'] 	= true;
		$carr['data'] 		= $data;
		$carr['code']		= 200;
		return $carr;
	}
}

if (! function_exists('nulltoempty')) {
	/**
	*	如果是null转为''
	*/
	function nulltoempty($str,$dev='')
	{
		if($str==null)$str=$dev;
		return $str;
	}
}

if (! function_exists('emptytodev')) {
	/**
	*	如果是为空转默认值
	*/
	function emptytodev($str,$dev='')
	{
		if(isempt($str))$str=$dev;
		return $str;
	}
}

if (! function_exists('nowdt')) {
	/**
	*	简单日期获取
	*/
	function nowdt($lx='now', $time=0)
	{
		if(is_string($time))$time = strtotime($time);
		if($time==0)$time = time();
		if($lx=='date' || $lx=='dt')$lx = 'Y-m-d';
		if($lx=='now' || $lx=='')$lx = 'Y-m-d H:i:s';
		if($lx=='time')$lx = 'H:i:s';
		if($lx=='month')$lx = 'Y-m';
		return date($lx, $time);
	}
}

/**
*	获取一个路径
*/
function rockpath($fold='')
{
	$path = app_path().'/RainRock';
	if($fold!='')$path.='/'.$fold.'';
	if(!file_exists($path) && !contain($path,'.')){
		mkdir($path);
	}
	return $path;
}

/**
*	获取IP
*/
function getclientip()
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



if (! function_exists('c')) {
	/**
	*	引入插件
	*/
	function c($num, $usra=null)
	{
		$clx = '\App\RainRock\Chajian';
		if(!contain($num,':'))$num = 'Base:'.$num.'';
		$numa= explode(':', $num);
		$cls = ''.$clx.'\\'.$numa[0].'\Chajian'.$numa[0].'_'.$numa[1].'';
		if(!class_exists($cls))return false;
		$obj = new $cls($usra);
		return $obj;
	}
}

/**
*	识别当前URL
*/
function appurl()
{
	if(!isset($_SERVER['HTTP_HOST']))return '';
	$url = 'http://'.$_SERVER['HTTP_HOST'];
	return $url;
}