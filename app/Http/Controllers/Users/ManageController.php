<?php
/**
*	用户后台
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Users;

use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use Auth;

class ManageController extends UsersController
{
  

    /**
     * 	用户管理主页
     */
    public function index()
    {
		$auth = Auth::user();
		//读取待加入的单位
		$daicompany 	= UseraModel::where('mobile', $auth->mobile)
							->where('mobilecode', $auth->mobilecode)
							->where('uid', 0)
							->get();
							
		$joincompany	= $auth->joincompany()->get();
		$joincoma 		= array();
		foreach($joincompany as $k=>$rs)$joincoma[] = $rs;
		foreach($daicompany as $k=>$rs)$joincoma[] = $rs;
		
        return view('users/manage',[
			'createcompany' => $auth->createcompany()->get(),
			'joincompany' 	=> $joincoma,
		]);
    }
	
	/**
	*	激活,$id 单位用户id
	*/
	public function activeForm($id)
	{
		$auth 	= Auth::user();
		$ars	= UseraModel::find($id);
		return view('users/active',[
			'ars'		=> $ars,
			'pagetitle'	=> trans('users/manage.activetitle')
		]);
	}
}
