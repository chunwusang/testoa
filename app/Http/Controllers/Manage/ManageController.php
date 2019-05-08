<?php
/**
*	单位后台管理
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use App\Model\Base\CompanyModel;
use App\Model\Base\BaseModel;


class ManageController extends Controller
{
	public $userid 		= 0; //users.id
	public $useaid 		= 0; //usera.id
	public $useatype	= 0; //0普通用户,1普通管理员,2超级管理员
	public $userinfo;
	public $useainfo;
	public $companyinfo;
	public $companyid;
	
    /**
     * @return void
     */
    public function __construct()
    {
		$this->middleware('auth:users');
    }
	
	/**
	*	获取单位信息
	*/
	public function getCompanyInfo($cid)
	{
		if(is_numeric($cid)){
			$data = CompanyModel::find($this->companyid);
		}else{
			$data = CompanyModel::where('num', $cid)->first();
		}
		if(!$data)exit('access Invalid');
		$this->companyinfo 	= $data;
		$this->companyid	= $data->id;
		return $data;
	}
	
	
	
	/**
	*	获取用户信息
	*/
	public function getUserInfo()
	{
		//if($this->userid>0)return;
		$this->userinfo		= \Auth::user();
		$this->userid		= $this->userinfo->id; //平台用户ID
		$this->useainfo		= BaseModel::getUsera($this->companyid, $this->userid); //单位用户ID
		if($this->useainfo)$this->useaid = $this->useainfo->id;
	}
	
	/**
	*	权限，判断当前用户是不是管理员，0普通用户,1管理员,2超级管理员
	public $userid 		= 0; //users.id
	public $useaid 		= 0; //usera.id
	public $useatype	= 0; //0普通用户,1普通管理员,2超级管理员
	public $userinfo;
	public $useainfo;
	public $companyinfo;
	public $companyid;
	*/
	public function getAuthory($bo=true)
	{
		$this->getUserInfo();
		$qx = 0;
		if($this->companyinfo->uid==$this->userid)$qx = 2;//创建人是超级管理员
		if($qx != 2){
			
		}
		$this->useatype = $qx;
		if($qx==0 && $bo)return $this->returntishi(trans('validation.notmanage'));
		return $qx;
	}

    /**
     *	权限，判断当前用户是不是管理员，0普通用户,1管理员,2超级管理员
    public $userid 		= 0; //users.id
    public $useaid 		= 0; //usera.id
    public $useatype	= 0; //0普通用户,1普通管理员,2超级管理员
    public $userinfo;
    public $useainfo;
    public $companyinfo;
    public $companyid;
     */
	public function getShowView($tpl, $params=array())
	{
		$arr = [
			'pagetitle' 	=> $this->companyinfo->name,
			'companyinfo' 	=> $this->companyinfo,
			'cnum' 			=> $this->companyinfo->num,
			'cid' 			=> $this->companyinfo->id,
			'useatype' 		=> $this->useatype,
			'tpl' 			=> $tpl,
			'helpstr'		=> ''
		];
		if($params)foreach($params as $k=>$v)$arr[$k]=$v;
		if(!view()->exists($tpl))return $this->returntishi('view '.$tpl.' not found');
		return view($tpl,$arr);
	}
}
