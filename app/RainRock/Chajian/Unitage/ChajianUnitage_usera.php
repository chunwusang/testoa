<?php
/**
*	管理首页-用户
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\UseraModel;


class ChajianUnitage_usera extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$obj 	= UseraModel::select();
		$did 	= (int)$request->get('did',0);
		$obj->where('cid' ,$this->companyid);
		if($did>0)$obj->where('deptid', $did);
		
		$key 	= trim($request->get('keyword'));
		$status 	= $request->get('status');
		if(!isempt($key)){
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
				$query->oRwhere('mobile','like',"%$key%");
				$query->oRwhere('deptname','like',"%$key%");
				$query->oRwhere('superman','like',"%$key%");
			});
		}
		if(!isempt($status)){
			$obj->where('status', $status);
		}

		$total 	= $obj->count();
		$data 	= $obj->orderBy('sort','desc')->simplePaginate($this->limit)->getCollection();
		
		return [
			'deptdata' 	=> c('dept')->getDeptArr($this->companyid),
			'did'		=> $did,
			'key'		=> $key,
			'status'		=> $status,
			'data'		=> $data,
			'total'		=> $total,
			'pager'		=> [
				'did' 	=> $did,
				'key' 	=> $key,
				'status' 	=> $status,
			],
			'mtable' 	=> c('rockjm')->encrypt('usera')
		];
	}
	
	/**
	*	编辑获取
	*/
	public function editForm($request)
	{
		$id 	= (int)$request->get('id','0');
		
		$data 	= UseraModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->name = '';
			$data->status= 1;
			$data->sort= 0;
			$data->position= '';
			$data->email= '';
			$data->mobile= '';
			$data->mobilecode= '';
			$data->user = '';
			$data->deptid = '';
			$data->gender = 1;
			$data->deptname = '';
			$data->type = 0;
			$data->superman = '';
			$data->superid = '';
			$data->uid 	= 0; //平台用户ID
		}
		
		$data->isedituser 	= ($data->uid==0 || isempt($data->user)) ? 1 : 0;
		$data->iseditmobile = ($data->uid==0 || isempt($data->mobile)) ? 1 : 0;
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
	
		return [
			'pagetitles' 	=> trans('table/usera.'.$ebts.''),
			'data'			=> $data,
			'deptdata' 		=> c('dept')->getDeptArr($this->companyid),
		];
	}
}