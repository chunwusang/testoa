<?php
/**
*	单位管理用户保存和删除
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\UseraModel;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ChajianUnitapi_usera extends ChajianUnitapi
{
	use ValidatesRequests;
	/**
	*	保存用户
	*/
	public function postData($request)
	{
		$id 	= (int)$request->input('id');
		
		$this->validate($request, [
            'name' 		=> 'required',
			'user'		=> 'required',
			'deptname'	=> 'required',
			'mobile'	=> 'required|numeric',
			'email'		=> 'nullable|email',
        ]);
		

		$cid	= $this->companyid;
		$data 	= ($id > 0) ? UseraModel::find($id) : new UseraModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}else{
			
			//是不是超过了
			$flaskm = $this->companyinfo->flaskm;
			$flasks = $this->companyinfo->flasks;
			if($flasks>=$flaskm)return returnerror(trans('table/usera.extnot'));
		}
		
		$data->name 	= $request->name;
		$data->sort 	= (int)$request->sort;
		$data->deptid 	= (int)$request->deptid;
		$data->deptname = $request->deptname;
		$data->position = $request->position;
		$data->superid 	= nulltoempty($request->superid);
		$data->superman = nulltoempty($request->superman);
		$data->user 	= nulltoempty($request->user);	
		$data->gender 	= (int)$request->gender;
		if(!isempt($request->email))$data->email 	= $request->email;
		
		if(isempt($data->pingyin))$data->pingyin = c('pingyin')->get($data->name);
		
		$isedit	= true;
		if($id>0 && $data->uid>0)$isedit = true;//已经激活了
		
		
		//可以保存用户名,新增时||未激活||未设置用户名
		if(!isempt($request->user)){		
			//用户名是否存在
			$result = UseraModel::select()->where('id','<>', $id)->where('cid', $cid)->where('user', $request->user);
			if($result->count()>0)
			return returnerrors('user', trans('table/usera.user_err'));
		}
		
		//可以保存手机,新增时||未激活||未设置手机号
		if($id==0 || ($id>0 && $data->uid==0) || ($id>0 && isempt($data->mobile))){
			$data->mobile 	= $request->mobile;
			$data->mobilecode 	= nulltoempty($request->mobilecode);

			//手机号是否存在
			$result = UseraModel::select()->where('id','<>', $id)->where('cid', $cid)->where('mobile', $request->mobile)->where('mobilecode', $data->mobilecode);
			if($result->count()>0)
			return returnerrors('mobile', trans('table/usera.mobile_err'));
		}
	
		if(!isempt($request->type))$data->type = $request->type;//用类型
	
		//新增时
		if($id==0){
			$data->cid = $cid;
			$data->uid = 0;
			$data->status = 0;
			$data->createdt = nowdt();
		}
		$data->optdt = nowdt();
		
		$this->getNei('usera')->reloaddata($cid);
		$data->save();
		
		return returnsuccess();
	}
	
	/**
	*	删除
	*/
	public function postdelcheck($request)
	{
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('iderror');
		
		if($id == $this->companyinfo->uid)return returnerror(trans('table/usera.delnot'));
		$data = UseraModel::find($id);
		$data->delete();
	
		return returnsuccess();
	}
	
	
	/**
	*	更新数据
	*/
	public function reload()
	{
		$this->getNei('usera')->reloaddata($this->companyid);
		return returnsuccess();
	}
	
	/**
	*	选择人员获取数据
	*/
	public function getdata($request)
	{
		$cid 		= $this->companyid;
		$userjson	= array();
		$groupjson	= array();
		$range		= $request->get('changerange');
		$type		= $request->get('changetype');
		if(contain($type,'user')){
			$userjson	= $this->getNei('usera')->getUseraData($cid, $range);
			$deptjson 	= $this->getNei('dept')->getDeptData($cid, $userjson);
			$groupjson	= $this->getNei('usera')->getGroupData($cid);
		}else{
			$deptjson 	= $this->getNei('dept')->getDeptData($cid);
		}
		
		$barr= [
			'groupjson' => json_encode($groupjson),
			'userjson' 	=> json_encode($userjson),
			'deptjson' 	=> json_encode($deptjson),
		];
		return returnsuccess($barr);
	}
}