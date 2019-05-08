<?php
/**
*	用户登录等控制器
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;

class LoginController extends Controller
{
	
	private function getStyle()
	{
		return  @$_COOKIE['bootstyle']?:config('rock.usersstyle');//错误抑制符  三目运算符   如果$_cookie['bootstyle']为真就输出$_COOKIE['bootstyle']
	}
   
	/**
	*	显示登录页面
	*/
	public function showLoginForm()
    {
    	// var_dump(123);die;
		if(Auth::guard('users')->check())return redirect(route('usersindex'));//判断是否存在users 如果存在则跳转值usersindex这个页面
		
        return view('users.login',[//输出登录页面  分配变量
			'bootstyle' => $this->getStyle()//调用getstyle方法
		]);
    }

	
	/**
	*	用户注册
	*/
	public function showRegForm()
    {
		if(Auth::guard('users')->check())return redirect(route('usersindex'));
		if(!config('app.openreg'))$this->returntishi(trans('users/reg.regopen'));//如果未开放注册则返回提示,但是默认app.openreg都是开放的 
		// die($this->returntishi(trans('users/reg.regopen')));
//        return [
//            'bootstyle' => $this->getStyle()
//        ];
        return view('users.reg',[
			'bootstyle' => $this->getStyle()
		]);
    }
	
	/**
	*	用户退出
	*/
	public function loginout(Request $request)
    {
		$key	= 'usertoken';
		// die($key);
		$token 	= session($key);
		session([$key=>'']);
		
		//清除cookie
		$cokey	= ''.config('cache.prefix').''.$key.'';//拼接
		if(function_exists('setcookie'))setcookie($cokey, '', 0, '/');//返回bool值   true或者false
		// die(setcookie($cokey, '', 0, '/'));
		
		$obj 	= new TokenModel();//实例化
		$obj->removeToken($token);//删除token
		Auth::guard('users')->logout();//去调用logout方法
        return redirect(route('userslogin'));//重定义到登录页面
    }

    public function house()
    {
//        return 222;
        return view('users/test');
    }
	
	/**
	*	找回密码
	*/
	public function showFindForm()
	{
		if(Auth::guard('users')->check())return redirect(route('usersindex'));
        return view('users.find',[
			'bootstyle' => $this->getStyle()
		]);
	}
}
