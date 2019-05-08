<?php
/**
*	api单位管理-组
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\GroupModel;
use App\Model\Base\SjoinModel;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ChajianUnitapi_group extends ChajianUnitapi
{
	use ValidatesRequests;
	/**
	*	保存
	*/
	public function postData($request)
	{
		$id 	= (int)$request->input('id');
		$cid	= $this->companyid;
		$this->validate($request, [
            'name' 		=> 'required',
        ]);
		
		$data 	= ($id > 0) ? GroupModel::find($id) : new GroupModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}
		
		$data->name 	= $request->name;
		$data->sort 	= (int)$request->sort;
		
		//新增时
		if($id==0){
			$data->cid = $cid;
		}
		
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
		
		$data 	= GroupModel::find($id);
		$data->delete();
	
		return returnsuccess();
	}
	
	public function postdeluser($request)
	{
		$sid	= (int)$request->input('sid');
		SjoinModel::where('cid', $this->companyid)
			->where('type',$request->input('type'))
			->where('mid', (int)$request->input('mid'))
			->where('sid', $sid)
			->delete();
		$this->getNei('usera')->reloaddata($this->companyid, $sid);	
		return returnsuccess();	
	}
	
	public function postsaveuser($request)
	{
		$mid	= (int)$request->input('mid'); //组ID
		$sid	= $request->input('sid'); //人员ID
		$sida 	= explode(',', $sid);
		
		SjoinModel::where('cid', $this->companyid)
			->where('type','gu')
			->where('mid', $mid)
			->whereIn('sid', $sida)
			->delete(); //删除原来保存的
		
		foreach($sida as $sid){
			$obj = new SjoinModel();
			$obj->cid = $this->companyid;
			$obj->type = 'gu';
			$obj->mid = $mid;
			$obj->sid = $sid;
			$obj->save();
		}
		$this->getNei('usera')->reloaddata($this->companyid, $sida);
		return returnsuccess();	
	}
	
}