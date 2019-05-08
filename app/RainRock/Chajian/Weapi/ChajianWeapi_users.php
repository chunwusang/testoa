<?php
/**
*	平台用户api
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-17
*/

namespace App\RainRock\Chajian\Weapi;

use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Model\Base\UsersModel;

class ChajianWeapi_users extends ChajianWeapi
{
	
	use ValidatesRequests;
	
	public function postbase($request)
	{
		$this->validate($request, [
            'name' 			=> 'required',
        ]);
		
		$data  = UsersModel::find($this->userid);
		$data->name 	= $request->input('name');
		$data->nickname = $request->input('nickname');
		$data->save();
		return returnsuccess();
	}
	
	//修改密码
	public function postpass($request)
	{
		$this->validate($request, [
            'oldpass' 			=> 'required',
            'newpass' 			=> 'required|between:6,30',
        ]);
		$oldpass	= $request->input('oldpass');
		$newpass	= $request->input('newpass');
		
		$res = \DB::table('users')->where('id',$this->userid)->select('password')->first();
		if(!\Hash::check($oldpass, $res->password))return returnerrors('oldpass', trans('table/users.oldpass_err'));
		
		$data  = UsersModel::find($this->userid);
		$data->password 	= $newpass;
		$data->save();
		return returnsuccess();
	}
	
	//修改头像
	public function postface($request)
	{
		$face	= nulltoempty($request->input('face'));
		$data  = UsersModel::find($this->userid);
		$data->face 	= $face;
		$data->save();
		return returnsuccess();
	}
	
	
	//修改手机号
	public function postmobile($request)
	{
		$mobile		= $request->input('mobile');
		$mobilecode	= nulltoempty($request->input('mobilecode'));
		$device 	= $request->input('device');
		
		// 验证输入。
        $this->validate($request, [
            'mobile' 	=> 'required|numeric|unique:users',
            'mobileyzm' => 'required|mobileyzm:'.$mobile.',bind,'.$device.''
        ],[
			'mobile.unique' => trans('users/reg.mobilecz')
		]);
		
		$data  = UsersModel::find($this->userid);
		$data->mobile 		= $mobile;
		$data->mobilecode 	= $mobilecode;
		$data->save();
		
		//激活的用户也需要修改手机号
		
		return returnsuccess();
	}
	
	//保存样式
	public function poststyle($request)
	{
		$style = $request->input('stylename');
		$data  = UsersModel::find($this->userid);
		$data->bootstyle = nulltoempty($style);
		$data->save();
		
		return returnsuccess();
	}
}