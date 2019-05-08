<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
	
	use AuthenticatesUsers;
	
	protected $redirectTo = '/admin';
	
	private function getStyle()
	{
		return  @$_COOKIE['bootadminstyle'] ? : config('rock.adminstyle');//返回样式
	}
   //admin登录页面
	public function showLoginForm()
    {
    	// $bb = 22
    	// return $bb;
		if(\Auth::guest()){
			return view('admin.login',[
				'tpl'		=> '',
				'bootstyle' => $this->getStyle()
			]);
		}else{
			return redirect($this->redirectTo);
		}
    }
	
	//admin退出
	public function loginout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect(route('adminlogin'));
    }
}
