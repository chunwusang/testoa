<?php
/**
*	基本方法 /base/$act
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use Captcha;

class BaseController extends Controller
{
	
	public function index($act, Request $request)
	{
		$act = 'base_'.$act.'';
		if(method_exists($this, $act))return $this->$act($request);
	}
	
	/**
	*	图片验证码
	*/
	private function base_captcha()
	{
		return Captcha::create();
	}
	
	/**
	*	获取短信验证码，注册和找回密码
	*/
	private function base_getcode(Request $request)
	{
		$mobile 	= $request->get('mobile');
		$device 	= $request->get('device');
		// var_dump($device);die;
		$mobilecode = nulltoempty($request->get('mobilecode'));
		$gtype  	= $request->get('gtype');
		$captcha  	= $request->get('captcha');
		if(!c('check')->iscnmobile($mobile))return returnerror('手机号码格式有误');
		if(isempt($device) || isempt($gtype))return returnerror('device/gtype不能为空');
		$gtypea 	= array('reg', 'find');
		if(!in_array($gtype, $gtypea))return returnerror('错误的gtype');
		
		//计算验证
		if(isempt($captcha))return returnerror('计算验证不能为空');
		if(!Captcha::check($captcha))return returnerror('计算验证错误', 206);
		
		//注册
		if($gtype=='reg'){
			$to 	= UsersModel::where('mobile', $mobile)->count();
			if($to>0)return returnerror(trans('users/reg.mobilecz'));
		}
		
		//找回密码
		if($gtype=='find'){
			$to 	= UsersModel::where('mobile', $mobile)->where('mobilecode', $mobilecode)->count();
			if($to==0)return returnerror(trans('users/reg.regnot'));
		}
		
		return c('rockapi')->sendcode($mobile, $gtype, $device);
	}
	
	private function base_randkey()
	{
		return c('rockjm')->getRandkey();
	}
	
	/**
	*	引入上传的文件/base/upfilejs
	*/
	private function base_upfilejs()
	{
		$url = c('rockapi')->geturl('upfilejs').'&gtype=url';
		return file_get_contents($url);
	}
	
	//字符串md5访问：/base/md5?str=123456
	private function base_md5(Request $request)
	{
		$str = $request->get('str','');
		return md5($str);
	}
	
	//验证REIM
	private function base_reim(Request $request)
	{
		$callback = $request->get('callback');
		$str 	  = json_encode(array(
			'title' => config('rockreim.title'),
			'url'	=> config('app.url')
		));
		return ''.$callback.'('.$str.')';
	}
}
