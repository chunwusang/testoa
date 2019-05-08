<?php
/**
*	后台管理-部门
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;


use App\Model\Base\DeptModel;
use Illuminate\Http\Request;


class DeptController extends AdminController
{
	
	/**
	*	列表
	*/
    public function index(Request $request)
    {
		$this->getLimit();
		
		$obj 	= DeptModel::select();

		$cid 	= $request->get('cid');
		if(!isempt($cid))$obj->where('cid' ,$cid);
		//模糊查询
		$key 	= $request->get('keyword');
		if(!isempt($key)){//如果不是空就查询
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
			});
		}

		$total 	= $obj->count();
		$data 	= $obj->orderBy('id','desc')->simplePaginate($this->limit)->getCollection();
		
        return $this->getShowView('admin/dept', [
			'pagetitle' => trans('table/dept.pagetitle'),
			'data' 		=> $data,
			'pager'		=> $this->getPager('admindept', $total),
			'tpl'		=> 'companydept',
			'lang'		=> 'table/dept',
			'kzq'		=> 'Admin/Dept'
		]);
    }
}
