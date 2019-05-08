<?php
/**
*	api接口
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Api;

use App\Model\Base\BaseModel;
use App\Model\Base\UsersModel;
use App\Model\Base\CompanyModel;




class ApiauthController extends ApiController
{
	
	public $userid 		= 0;
	public $useaid 		= 0;
	public $userinfo;
	public $useainfo;
	
	
	public $companyarr	= false; //对应单位下用户usera.id数组
	public $companyinfo	= false; //对应用户所在的单位
	public $companyid	= 0; //单位Id
	
	/**
	*	验证api中间件
	*/
	public function __construct()
    {
		parent::__construct();
		$this->middleware('apiauth');
    }
	
	/**
	*	获取用户ID
	*/
	public function getUserId()
	{
		if($this->userid>0)return $this->userid;
		$uarr= \Rock::getApiUser();
		$uid = $uarr['user.id'];
		if(isempt($uid))$uid = 0;
		$this->userid 	= $uid;
		$this->userinfo = $uarr['user.info'];
		return $uid;
	}
	
	/**
	*	判断是否可以操作该单位权限
	*/
	public function getCompanyInfo()
	{
		$this->getUserId();
		if(!$this->userid)return;
		$this->companyarr = UsersModel::find($this->userid)->joincompany()->get(); //这个获取下我加入单位的人员
	}
	
	/**
	*	判断是否可以操作该单位权限
	*/
	public function getCompanyId($request, $cnum='')
	{
		$cid 	= 0;
		if($cnum=='')$cid	= (int)$request->input('cid','0');
		if($cid==0){
			if($cnum=='')$cnum = $request->input('cnum');
			if(!isempt($cnum)){
				$cnumfof = CompanyModel::where('num', $cnum)->first();
				if($cnumfof)$cid = $cnumfof->id;
			}
		}
		if($cid==0)return $cid;
		if(!$this->companyarr)$this->getCompanyInfo();
		if(!$this->companyarr)return 0;
		$bo  = false;
		foreach($this->companyarr as $k=>$rs){
			if($rs->company->id==$cid){
				$this->companyinfo = $rs->company;
				$bo = true;
				break;
			}
		}
		if(!$bo)return 0;
		$this->useainfo		= BaseModel::getUsera($cid, $this->userid); //单位用户ID
		if($this->useainfo)$this->useaid = $this->useainfo->id;
		$this->companyid	= $cid;
		return $cid;
	}
}
