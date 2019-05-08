<?php
/**
*	Token数据模型
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;


class TokenModel extends Model
{
	protected $table = 'token';
	
	/**
	*	创建登录的token
	*/
	public function createToken($uid, $cfrom, $useragent='', $xi=0)
	{
		$bobj		= c('base');
		$this->uid 			= $uid;
		$this->cfrom 		= $cfrom;
		$this->online 		= 1;
		$this->token 		= strtolower(str_random(10));
		$this->useragent 	= $useragent;
		$this->ip 			= $bobj->getclientip();
		$this->web 			= $bobj->getbrowser();
		$bo = $this->save();
		if(!$bo && $xi<=1)$this->token = $this->createToken($uid, $cfrom, $useragent, $xi+1);
		return $this->token;
	}
    
	/**
	*	根据token获取用户信息
	*/
	private $uinfo;
	public function getTokenInfo($token)
	{
		$uinfo 	= $this->where('token', $token)->first();
		$this->uinfo = $uinfo;
		if($uinfo){
			return UsersModel::find($uinfo->uid);
		}else{
			return false;
		}
	}
	
	public function getuserAgent()
	{
		if(!$this->uinfo)return '';
		return $this->uinfo->useragent;
	}
	
	/**
	*	删除token
	*/
	public function removeToken($token)
	{
		$this->where('token', $token)->delete();
	}
}
