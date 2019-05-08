<?php
/**
*	基础验证码
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-17
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Base\UseraModel;
use App\Model\Base\UsersModel;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;

class ChajianWeapi_base extends ChajianWeapi
{
	/**
	*	获取验证码地址，/api/we/base_getcode
	*/
	public function getcode($request)
	{
		$mobile 	= $this->userinfo->mobile;
		$device 	= $request->get('device');
		$mobilecode = nulltoempty($request->get('mobilecode'));
		$gtype  	= $request->get('gtype');
		$captcha  	= $request->get('captcha');
		if(!c('check')->iscnmobile($mobile))return returnerror('手机号码格式有误');
		if(isempt($device) || isempt($gtype))return returnerror('device/gtype不能为空');
		
		if($gtype=='jiesan'){
			$uinfo 	= UsersModel::where('id', $this->companyinfo->uid)->first();
			$mobile = $uinfo->mobile;
		}
		
		
		//从新绑定手机号
		if($gtype=='bind'){
			$to 	= UsersModel::where('mobile', $mobile)->count();
			if($to>0)return returnerror(trans('users/reg.mobilecz'));
		}
		
		//加入单位
		if($gtype=='join'){
			$aid	= (int)$request->get('aid');
			$to 	= UseraModel::where('id', $aid)->where('mobile', $mobile)->where('mobilecode', $mobilecode)->count();
			if($to==0)return returnerror(trans('users/reg.regnot'));
		}
		
		return c('rockapi')->sendcode($mobile, $gtype, $device);
	}
	
	/**
	*	退出/api/we/base_loginout
	*/
	public function loginout()
	{
		$key	= 'usertoken';
		$token 	= session($key);
		session([$key=>'']);
		$obj 	= new TokenModel();
		$obj->removeToken($token);
		Auth::guard('users')->logout();
		return returnsuccess();
	}
}