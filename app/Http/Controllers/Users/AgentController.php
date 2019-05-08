<?php
/**
*	个人中应用管理-相当于第三方应用
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;



class AgentController extends UsersController
{
  

    public function index()
    {
		return view('users/agent');
    }
}
