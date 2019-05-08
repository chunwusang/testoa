<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use App\Model\Base\UseraModel;
use Illuminate\Http\Request;


class UseraController extends AdminController
{
	
	/**
	*	列表
	*/
    public function index(Request $request)
    {
		$this->getLimit();
		
		$obj 	= UseraModel::with('company'); //->latest()就是根据
		
		
		$key 	= $request->get('keyword');
		if(!isempt($key)){
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
				$query->oRwhere('mobile','like',"%$key%");
				$query->oRwhere('position','like',"%$key%");
			});
		}
		
		
		$cid 	= $request->get('cid');
		if(!isempt($cid))$obj->where('cid' ,$cid);
		
		$uid 	= $request->get('uid');
		if(!isempt($uid))$obj->where('uid' ,$uid);
	

		$total 	= $obj->count();
		$data 	= $obj->orderBy('id','desc')->paginate($this->limit);
		
        return $this->getShowView('admin/usera', [
			'pagetitle' => trans('table/usera.pagetitle'),
			'data' 		=> $data,
			'pager'		=> $this->getPager('adminusera', $total, [
				'cid' => $cid,
				'uid' => $uid,
			]),
			'tpl'		=> 'companyusera',
			'lang'		=> 'table/usera',
			'kzq'		=> 'Admin/Usera'
		]);
    }
}
