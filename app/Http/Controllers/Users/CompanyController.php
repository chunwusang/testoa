<?php
/**
*	单位管理
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;



class CompanyController extends UsersController
{
  

    public function showCreateForm()
    {
        return view('users/companyadd',[
			'pagetitle' => trans('table/company.addtext')
		]);
    }
}
