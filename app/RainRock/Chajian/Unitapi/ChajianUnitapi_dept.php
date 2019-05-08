<?php
/**
*	单位管理部门保存和删除
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\DeptModel;
use App\Model\Base\UseraModel;

class ChajianUnitapi_dept extends ChajianUnitapi
{
	/**
	*	保存单位
	*/
	public function postData($request)
	{
		$id 	= (int)$request->input('id');
		$pid 	= (int)$request->pid;
		$cid 	= $this->companyid;
		
		if($id==0 && $pid<=0)return returnerrors('pid',trans('table/dept.pid_err'));
		if($pid<0)return returnerrors('pid',trans('table/dept.pid_err'));
		
		if($pid==$id)return returnerrors('pid',trans('table/dept.pid_err1'));
		
		$to 	= DeptModel::select()->where('cid', $cid)->where('id', $pid)->count();
		if($to==0)return returnerrors('pid',trans('table/dept.pid_ers'));
		
		$data 	= $this->getNei('dept')->save($cid, $id, [
			'name' 		=> $request->name,
			'pid' 		=> $pid,
			'sort' 		=> (int)$request->sort,
			'status' 	=> (int)$request->status,
			'headman' 	=> nulltoempty($request->headman),
			'num' 		=> nulltoempty($request->num),
			'headid' 	=> nulltoempty($request->headid),
		]);
		
		return returnsuccess();
	}
	
	/**
	*	部门删除
	*/
	public function postdelcheck($request)
	{
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('iderror');
		
		$to 	= DeptModel::where(['cid'=>$this->companyid,'pid'=>$id])->count();
		if($to>0)return returnerror(trans('table/dept.delinfo1'));
		
		//$to 	= UseraModel::select()->where('deptid', $id)->count();
		//if($to>0)return returnerror(trans('table/dept.delinfo'));
		
		DeptModel::where(['cid'=>$this->companyid,'id'=>$id])->delete();
		
		return returnsuccess();
	}
	
}