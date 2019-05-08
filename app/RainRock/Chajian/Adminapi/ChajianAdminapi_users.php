<?php
/**
*	平台用户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Adminapi;

use App\Model\Base\UsersModel;
use App\Model\Base\CompanyModel;
use Illuminate\Http\Request;

class ChajianAdminapi_users extends ChajianAdminapi
{
	
	/**
	*	数据保存
	*/
	public function postData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		//if($id==0)return returnerror('不支持新增');
		$name 	= $request->name;
		$email 	= trim(nulltoempty($request->email));
		$mobile 	= trim(nulltoempty($request->mobile));
		$mobilecode = trim(nulltoempty($request->mobilecode));
		$userid 	= trim(nulltoempty($request->userid));
		if(isempt($name))return returnerrors('name','姓名不能为空');
		if(isempt($mobile))return returnerrors('mobile','手机号不能为空');
		
		$check 	= c('check');
		
		if(!isempt($email)){
			if(!$check->isemail($email))return returnerrors('email','邮箱格式错误');
			$to = UsersModel::where('email',$email)->where('id','<>', $id)->count();
			if($to>0)return returnerrors('email','邮箱'.$email.'已经存在了');
		}
		
		if(!isempt($userid)){
			if(strlen($userid)<2)
				return returnerrors('userid','用户名至少2个字符');
			if($check->isincn($userid))
				return returnerrors('userid','用户名不能包含中文');
			if($check->isnumber($userid))
				return returnerrors('userid','用户名不能是数字');
			$to = UsersModel::where('userid',$userid)->where('id','<>', $id)->count();
			if($to>0)return returnerrors('userid','用户名'.$userid.'已经存在了');
		}
		
		$to = UsersModel::where(['mobile'=>$mobile,'mobilecode'=>$mobilecode])->where('id','<>', $id)->count();
		if($to>0)return returnerrors('mobile','手机号'.$mobile.'已经存在了');

		$data 	= ($id > 0) ? UsersModel::find($id) : new UsersModel();
		$pass 			= nulltoempty($request->password);
		if($id==0 && isempt($pass))$pass = '123456';//新增默认密码
		
		$data->name 	= $request->name;
		$data->face 	= nulltoempty($request->face);
		$data->email 	= $email;
		$data->userid 	= $userid;
		$data->nickname = nulltoempty($request->nickname);
		$data->flaskm 	= (int)$request->flaskm;
		$data->status 	= (int)$request->status;
		if(!isempt($pass))$data->password = $pass;
		if($id==0){
			$data->mobilecode 	= $mobilecode;
			$data->mobile 		= $mobile;
		}

		$data->save();
		
		return returnsuccess();
	}
	
	/**
	*	删除用户
	*/
	public function postdelcheck(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		if($id==0)return returnerror('不支持新增');
		$to 	= CompanyModel::where('uid',$id)->count();
		if($to>0)return returnerror('该用户有创建过单位，请先转移单位创建人后在删除。');
		
		UsersModel::find($id)->delete();
		
		return returnsuccess();
	}
	
	/**
	*	导入平台用户
	*/
	public function postdaoru()
	{
	}
}