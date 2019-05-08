<?php
/**
*	插件-api移动端首页
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Base\UseraModel;
use App\Model\Base\DeptModel;
use App\Model\Base\CompanyModel;
use Illuminate\Foundation\Validation\ValidatesRequests;

class ChajianWeapi_company extends ChajianWeapi
{
	
	use ValidatesRequests;
	
	/**
	*	读取我加入的单位，和已加入
	*/
	public function getData($req=null, $auth=null)
	{
		if($auth==null)$auth 	= $this->userinfo;
		$daicompany 	= UseraModel::where('mobile', $auth->mobile)
							->where('mobilecode', $auth->mobilecode)
							->where('uid', 0)
							->get();
							
		$joincompany	= $auth->joincompany()->get();
		$joincoma 		= array();
		foreach($joincompany as $k=>$rs){
			$ars 	= $rs->company;
			$ars->mystatus	= $rs->status;
			$ars->myname	= $rs->name;
			$ars->ismoren	= $this->userinfo->devcid==$ars->id;
			$joincoma[] = $ars;
		}
		foreach($daicompany as $k=>$rs){
			$ars 	= $rs->company;
			$ars->mystatus	= $rs->status;
			$ars->myname	= $rs->name;
			$ars->mobile	= substr($rs->mobile,0,3).'****'.substr($rs->mobile,-4);
			$ars->mobilesho = c('rockjm')->base64encode($rs->mobile);
			$ars->aid 		= $rs->id;
			$ars->ismoren	= $this->userinfo->devcid==$ars->id;
			$joincoma[] 	= $ars;
		}
		
		return returnsuccess([
			'joincompany' => $joincoma
		]);
	}
	
	/**
	*	设置默认单位
	*/
	public function postsetdevcid($req)
	{
		$devcid	= $req->input('devcid');
		$this->userinfo->devcid = $devcid;
		$this->userinfo->save();
		return returnsuccess($devcid);
	}
	
	/**
	*	激活单位
	*/
	public function postjoinactive($request, $userinfo=null)
	{
		$aid		= (int)$request->input('aid');
		$device		= $request->input('device');
		$code		= $request->input('mobileyzm');
		if($userinfo==null)$userinfo = $this->userinfo;
		$ars 		= UseraModel::find($aid);
		if(!$ars || $ars->status!=0)return returnerror('error1');
		
		//单位下用户手机号和平台用户手机号不一致
		$umobile1	= $ars->mobilecode.$ars->mobile;
		$umobile2	= $userinfo->mobilecode.$userinfo->mobile;
		if($umobile1 != $umobile2)return returnerror('error2');
		
		$mobile		= $ars->mobile;
		
		//验证验证码
		$barr 		= c('rockapi')->checkcode($mobile, $code,'join', $device);
		if(!$barr['success'])return returnerror($barr['msg']);
		
		//激活成功
		$ars->status 	= 1;
		$ars->name 		= $userinfo->name;
		$ars->uid 		= $userinfo->id;
		$ars->joindt 	= nowdt();
		$ars->save();
		
		c('usera')->reloaddata($ars->cid); //更新数据
		
		return returnsuccess();
	}
	
	/**
	*	获取单位用户信息
	*/
	public function getainfo($request)
	{
		$aid		= (int)$request->get('id');
		$ars 		= UseraModel::where('cid', $this->companyid)->where('id',$aid)->first();
		if(!$ars)return returnerror('信息不存在');
		
		return returnsuccess(c('rockjm')->encryptarr($ars, 1));
	}
	
	/**
	*	创建单位
	*/
	public function postcreate($request)
	{
		//创建数据库是不是超过了
		if($this->userinfo->flasks>=$this->userinfo->flaskm)
			return returnerror(sprintf(trans('table/company.extchao'), $this->userinfo->flaskm));
		
		
        $this->validate($request, [
            'name' 			=> 'required|unique:company',
            'shortname' 	=> 'required'
        ],[
			'name.unique' 	=> trans('table/company.name_unique')
		]);
		
		//保存的单位表
		$obj = new CompanyModel();
		$obj->name 		= $request->name;
		$obj->shortname = $request->shortname;
		if(!isempt($request->logo))$obj->logo 			= $request->logo;
		if(!isempt($request->tel))$obj->tel 			= $request->tel;
		if(!isempt($request->contacts))$obj->contacts 	= $request->contacts;
		$obj->uid 		= $this->userid;
		$obj->num 		= strtolower(str_random(6));//随机
		$obj->flaskm 	= 100;
		$obj->flasks 	= 0;
		$obj->save();
		$cid 		= $obj->id;
		
		
		//创建一个顶级部门
		$dbj		= c('dept')->save($cid, 0, ['name'=>$request->name]);
		$did 		= $dbj->id; 
		
		//保存的单位用户表
		$optdt		= nowdt();
		$abj		= new UseraModel();
		$abj->cid 	= $cid;
		$abj->deptid= $did;
		$abj->deptname	 = $request->name;
		$abj->deptallname= $request->name;
		$abj->user		 = c('pingyin')->get($this->userinfo->name);
		$abj->position	 = '创建人';
		$abj->status= 1; //我创建是是激活状态
		$abj->type	= 1; //创建人就是管理员
		$abj->uid 	= $this->userid;
		$abj->name 	= $this->userinfo->name;
		$abj->mobile= $this->userinfo->mobile;
		$abj->optdt 	= $optdt;
		$abj->createdt 	= $optdt;
		$abj->joindt 	= $optdt; //加入时间
		$abj->save();
		
		return returnsuccess();
	}
}