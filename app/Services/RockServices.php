<?php
/**
*	基本服务
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Services;


class RockServices
{
	
	private $config;
	private $nowuser;
	
	public function __construct($config=null)
	{
		$this->config = $config;
    }
	
	/**
	*	设置用户
	*/
	public function setApiUser($user)
	{
		$this->nowuser = $user;
	}
	public function getApiUser()
	{
		return $this->nowuser;
	}
	
	/**
	*	设置token
	*/
	public function setToken($token)
	{
		$this->temptoken = $token;
	}
	public function getToken()
	{
		return $this->temptoken;
	}
	
	/**
	*	验证系统
	*/
	public function initCheck()
	{
		if(contain(strtolower(PHP_OS),'win')){
			//if(!class_exists('COM'))
			//	return 'Error(601):Windows System Not Found [COM] Class';
			
		}else{
			
		}
		return false;
	}
	
	
	/**
	*	发送请求
	*/
	public function curlsend($url, $type, $data=array(),$params=array())
	{
		$curl = new \Curl\Curl();
		$curl->setUserAgent(arrvalue($params, 'useragent', 'xinhucloud v1.0.0'));
		$ishttps	= 0;
		$url		= str_replace('&#47;','/', $url);
		$url		= str_replace(' ','', $url);
		if(strtolower(substr($url,0, 5))=='https')$ishttps = 1;
		if($ishttps==1){
			$curl->setOpt(CURLOPT_RETURNTRANSFER, TRUE);
			$curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
		}
		$timeout 	= (int)arrvalue($params,'timeout', 0);
		if($timeout>0)$curl->setOpt(CURLOPT_TIMEOUT, $timeout); 
		$bobj 	= c('base');
		$header	= arrvalue($params,'header', array());
		$header['CLIENT-IP'] 	= $bobj->getclientip();
		if($header)foreach($header as $k=>$v)$curl->setHeader($k,  $v);
		
		$curl->setOpt(CURLOPT_USERAGENT, $bobj->getuseragent());
		
		if($type=='get')$curl->get($url);
		if($type=='post')$curl->post($url, $data);
		if($type=='put')$curl->put($url, $data);
		if($type=='delete')$curl->delete($url, $data);
	
		if ($curl->error) {
			$barr 	 = returnerror('error('.$curl->error_code.'):'.$curl->http_error_message, $curl->http_status_code);
		}else{
			$bmsg 	 = $curl->response;
			$barr	 = returnsuccess($bmsg);
		}

		$curl->close();
		return $barr;
	}
	
	/**
	*	get请求
	*/
	public function curlget($url,$params=array())
	{
		return $this->curlsend($url, 'get', array(), $params);
	}
	
	/**
	*	post请求
	*/
	public function curlpost($url, $data=array(),$params=array())
	{
		return $this->curlsend($url, 'post', $data, $params);
	}
	
	
	/**
	*	添加URL路径
	*/
	public function replaceurl($val)
	{
		if(isempt($val))return '';
		if(contain($val,'{baseurl}')){
			$val = str_replace('{baseurl}', config('rock.baseurl').'/', $val);
		}else{
			if(substr($val,0,4)!='http')$val=''.config('app.url').''.$val.'';
		}
		return $val;
	}
	
	/**
	*	往队列加数据
	*/
	public function qpush($mstr, $act,$params='',$atype='', $title='',$runtime=0)
	{
		return c('Queue:start')->push($mstr, $act,$params,$atype, $title,$runtime);
	}
	
	/**
	*	获取流程
	*	$num 应用编号 agenh.num
	*/
	public function getFlow($num, $usea=null)
	{
		$numa= explode('-', $num);
		$nums= $numa[0];
		$pnum= arrvalue($numa, 1);
		$cls = '\App\RainRock\Flowagent\Flowagent_'.$nums.'';
		if(!class_exists($cls))$cls = '\App\RainRock\Flow\Rockflow';
		return new $cls($nums, $usea, $pnum);
	}
	
	/**
	*	匹配
	*/
	public function matcharr($str, $lx=0)
	{
		$match	= '/\{(.*?)\}/';
		if($lx==1)$match	= '/\[(.*?)\]/';
		if($lx==2)$match	= '/\`(.*?)\`/';
		if($lx==3)$match	= '/\#(.*?)\#/';
		preg_match_all($match, $str, $list);
		$barr = array();
		foreach($list[1] as $k=>$nrs){
			$barr[] = $nrs;
		}
		return $barr;
	}
}