<?php
/**
*	系统后台应用管理
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;



class AnstallController extends AdminController
{
  
	/**
	*	获取可安装的应用
	*/
    public function getaList()
    {
		 return $this->getShowView('admin/anstall', [
			'pagetitle' => trans('table/anstall.pagetitle'),
		]);
    }

}
