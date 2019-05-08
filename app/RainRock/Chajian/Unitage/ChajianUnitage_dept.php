<?php
/**
*	管理首页
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\DeptModel;


class ChajianUnitage_dept extends ChajianUnitage
{
	
	public function getForm()
	{
		return [
			'data' 		=> c('dept')->getDeptArr($this->companyid),
			'mtable' 	=> c('rockjm')->encrypt('dept')
		];
	}
	
	/**
	*	部门编辑新增
	*/
	public function editForm($request)
	{
		$id 	= (int)$request->get('id','0');
		
		$data = DeptModel::where(['cid'=>$this->companyid,'id'=>$id])->first();
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->name = '';
			$data->status= 1;
			$data->num	= '';
			$data->sort= 0;
			$data->headman= '';
			$data->headid= '';
			$data->pid 	= (int)$request->get('pid','0');
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		return [
			'pagetitles' 	=> trans('table/dept.'.$ebts.''),
			'data'			=> $data,
		];
	}
}