<?php
/**
*	插件-api移动端首页
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Base\TokenModel;

class ChajianWeapi_index extends ChajianWeapi
{
	/**
	*	手机首页获取数据
	*/
	public function getData()
	{
		$agenharra	= $this->getNei('Base:agenh')->getAgenh(2); //2移动端
		
		$jm 		= c('rockjm');
		$this->userinfo->randshow	= $jm->getkeyshow();
		
		$charhist	= $this->getNei('reim')->getcharhist($this->allcompany); //获取会话历史列表
		
		$barr = [
			'userinfo' 	=> $jm->encryptarr($this->userinfo), //敏感数据加密
			'agenharr'	=> $agenharra[0],
			'useainfo'	=> $jm->encryptarr($this->useainfo),
			'charhist'	=> $charhist,
		];
		return returnsuccess($barr);
	}
	
	/**
	*	REIM首页获取数据
	*/
	public function reimdata($request)
	{
		$initci		= (int)$request->get('initci','0');
		$agenharra	= $this->getNei('Base:agenh')->getAgenh(1); //2移动端
		
		$jm 		= c('rockjm');
		$this->userinfo->randshow	= $jm->getkeyshow();
		
		$charhist	= $this->getNei('reim')->getcharhist($this->allcompany); //获取会话历史列表
		
		$barr = [
			'userinfo' 	=> $jm->encryptarr($this->userinfo), //敏感数据加密
			'agenharr'	=> $agenharra[0],
			'useainfo'	=> $jm->encryptarr($this->useainfo),
			'wwwewe'	=> $this->useainfo,
			'charhist'	=> $charhist,
		];
		
		//连接到服务端的信息
		$reiminfo		= false;
		if($initci==0){
			$reiminfo	= array();
			$reiminfo['wshost'] 	= $jm->encrypt(config('rockreim.reimclient'));
			$reiminfo['adminid'] 	= $this->userinfo->id;
			$reiminfo['adminname'] 	= $this->userinfo->name;
			$reiminfo['reimfrom'] 	= config('rockreim.reimfrom');
			$reiminfo['clientkey'] 	= md5($request->userAgent());
		}
		
		$barr['reiminfo']= $reiminfo;
		$barr['initci']	 = $initci;
		
		return returnsuccess($barr);
	}
	
	/**
	*	用户退出
	*/
	public function loginout()
    {
		$key	= 'usertoken';
		$token 	= session($key);
		session([$key=>'']); //清除session
		
		//清除cookie
		$cokey	= ''.config('cache.prefix').''.$key.'';
		if(function_exists('setcookie'))setcookie($cokey, '', 0, '/');
		
		$obj 	= new TokenModel();
		$obj->removeToken($token);
		return returnsuccess();
    }
}