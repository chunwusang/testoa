<?php
/**
*	平台用户管理
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Model\Base\UsersModel;
use Illuminate\Http\Request;
use DB;


class UsersController extends AdminController
{
	
	/**
	*	列表
	*/
    public function index(Request $request)
    {
		$this->getLimit();
		
		$obj 	= UsersModel::latest();
		$key 	= $request->get('keyword');
		if(!isempt($key)){
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
				$query->oRwhere('mobile','like',"%$key%");
				$query->oRwhere('userid',$key);
			});
		}

		$total 	= $obj->count();
		$data 	= $obj->simplePaginate($this->limit)->getCollection();
	
		
        return $this->getShowView('admin/users', [
			'pagetitle' => trans('table/users.pagetitle'),
			'data' 		=> $data,
			'pager'		=> $this->getPager('adminusers', $total),
			'mtable' 	=> c('rockjm')->encrypt('users'),
			'lang'		=> 'table/users',
			'kzq'		=> 'Admin/Users'
		]);
    }
	
	/**
	*	用户编辑与新增
	*/
	public function getForm($id, Request $request)
	{
		$data 	= DB::table('users')->find($id);
		if(!$data){//判断是否有原本的数据,没有则是新增,有就是编辑
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->flaskm 	= 0;
			$data->email 	= '';
			$data->name 	= '';
			$data->nickname = '';
			$data->userid 	= '';
			$data->iseditmobile = 1;
			$data->status 	= 1;
			$data->face 	= '';
			$data->facesrc	= '';
			$data->mobile	= '';
			$data->mobilecode	= '';
		}else{
			$data->facesrc	= $data->face;
			$data->iseditmobile = 0;
		}
		if(isempt($data->facesrc))$data->facesrc = '/images/noface.png';
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		return $this->getShowView('admin/usersedit', [
			'pagetitle' 	=> trans('table/users.'.$ebts.''),
			'data'			=> $data
		]);
	}
	
	
}
