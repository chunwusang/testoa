<?php
/**
*	api单位管理-权限
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\AuthoryModel;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ChajianUnitapi_authory extends ChajianUnitapi
{
	
	use ValidatesRequests;
	
	/**
	*	保存
	*/
	public function postData($request)
	{
		$cid	= $this->companyid;
		$id 	= (int)$request->input('id');
		$atype 	= (int)$request->input('atype');
		$csarr	= [
            'objectname' 	=> 'required',
			'atype'			=> 'required',
        ];
		$this->validate($request, $csarr);
		
		
		$data 	= ($id > 0) ? AuthoryModel::find($id) : new AuthoryModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}
		
		$data->cid 		= $cid;
		$data->status 	= (int)$request->status;
		$data->objectid 	= nulltoempty($request->objectid);
		$data->objectname 	= nulltoempty($request->objectname);
		$data->receid 	= nulltoempty($request->receid);
		$data->recename = nulltoempty($request->recename);
		$data->wherestr = nulltoempty($request->wherestr);
		$data->explain 	= nulltoempty($request->explain);
		$data->agenhid 	= nulltoempty($request->agenhid,'0');
		$data->atype 	= $request->atype;
		
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
		
		AuthoryModel::where([
			'cid' => $this->companyid,
			'id' => $id
		])->delete();
		
		return returnsuccess();
	}
	
	/**
	*	批量删除
	*/
	public function postpldel($request)
	{
		$sid = $request->input('sid');
		if(isempt($sid))return returnerror('iderror');
		AuthoryModel::where('cid', $this->companyid)
				->whereIn('id', explode(',', $sid))
				->delete();
		return returnsuccess();
	}
}