<?php
/**
*	单位管理部门保存和删除
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use App\Model\Base\CompanyModel;
use App\Model\Base\UseraModel;
use App\Model\Base\UsersModel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

class ChajianUnitapi_company extends ChajianUnitapi
{
	use ValidatesRequests;
	
	public function postbase($request)
	{
		$this->validate($request, [
            'name' 			=> 'required|unique:company,name,'.$this->companyid.'',
            'shortname' 	=> 'required'
        ],[
			'name.unique' 	=> trans('table/company.name_unique')
		]);
		
		$data  = CompanyModel::find($this->companyid);
		$data->name 		= $request->input('name');
		$data->shortname 	= $request->input('shortname');
		$data->tel 			= nulltoempty($request->input('tel'));
		$data->contacts 	= nulltoempty($request->input('contacts'));
		$data->save();
		return returnsuccess();
	}
	
	//修改logo
	public function postface($request)
	{
		$face	= nulltoempty($request->input('face'));
		$data   = CompanyModel::find($this->companyid);
		$data->logo 	= $face;
		$data->save();
		return returnsuccess();
	}
	
	//更换创建人
	public function postcreate($request)
	{
		$this->validate($request, [
            'superman' 	=> 'required',
            'superid' 	=> 'required|numeric'
        ]);
		
		$aid 	= (int)$request->input('superid','0'); //人员必须是激活的状态
		$ars 	= UseraModel::where('id', $aid)->where('cid', $this->companyid)->where('status', 1)->first();
		if(!$ars)return returnerrors('superman', trans('table/company.editcreatename_err1'));
		$uid 	= $ars->uid;
		
		$urs 	= UsersModel::where('id', $uid)->where('status', 1)->first();
		if(!$urs)return returnerrors(trans('table/company.editcreatename_err2'));
		
		$data   = CompanyModel::find($this->companyid);
		$data->uid 	= $uid;
		$data->save();
		
		return returnsuccess();
	}
	
	public function pipei()
	{
		$msg = c('flow', $this->useainfo)->pipeiall($this->companyid);
		return returnsuccess($msg);
	}
	
	/**
	*	解散
	*/
	public function postjiesan($request)
	{
		$device		= $request->input('device');
		$code		= $request->input('mobileyzm');
		
		$uinfo 	= UsersModel::find($this->companyinfo->uid);
		$mobile = $uinfo->mobile;
		
		//验证验证码
		$barr 		= c('rockapi')->checkcode($mobile, $code,'jiesan', $device);
		if(!$barr['success'])return returnerrors('mobileyzm',$barr['msg']);
		
		$cid 		= $this->companyid;
		
		//删除数据
		$nobeifne 	= explode(',','admin,agent,agentfields,agentmenu,agenttodo,basefileda,basesms,chargems,company,log,migrations,task,queue,token,users');
		$db 		= c('mysql');
		$qz 		= DB::getTablePrefix();
		$alltabls 	= $db->getAllTable();
		foreach($alltabls as $tabs){
			$tabs	= str_replace($qz,'', $tabs);
			if(in_array($tabs, $nobeifne))continue;	
			DB::table($tabs)->where('cid', $cid)->delete();
		}
		CompanyModel::find($cid)->delete();
		
		$this->getNei('log')->add('解散单位','单位['.$this->companyinfo->name.']');

		return returnsuccess(['msg'=>'成功解散单位['.$this->companyinfo->name.']']);
	}
}