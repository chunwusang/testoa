<?php
/**
*	web应用
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Base\BaseModel;

class WebController extends Controller
{
	
	protected $authform	= 'web';
	
	public $userid 		= 0; //users.id
	public $useaid 		= 0; //usera.id
	public $companyid 	= 0; //company.id
	public $userinfo;
	public $useainfo;
	public $companyinfo;
	public $agenhinfo;
	
	public function __construct()
    {
		parent::__construct();
		$this->middleware('apiauth:'.$this->authform.'');
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
	*	获取单位信息,$cnum单位编号
	*/
	public function getCompany($cnum)
	{
		$this->getUserId();
		$this->companyinfo	= BaseModel::getCompany($cnum);
		if(!$this->companyinfo)abort(404, 'not found company['.$cnum.']');
		$this->companyid	= $this->companyinfo->id;
		$this->useainfo		= BaseModel::getUsera($this->companyid, $this->userid); //单位用户ID
		if($this->useainfo)$this->useaid = $this->useainfo->id;
	}
	
	/**
	*	是否显示头部
	*/
	public function isShowHeader($request)
	{
		$userAgent	=  strtolower($request->userAgent());
		$isshow 	= 1;
		if(contain($userAgent, 'dingtalk'))$isshow = 0;
		if(contain($userAgent, 'micromessenger'))$isshow = 0;
		return $isshow;
	}
	
	public function getAgenh($cnum, $num)
	{
		$this->getCompany($cnum);
		$this->agenhinfo	= BaseModel::agenhInfo($this->companyid, $num);
		return $this->agenhinfo;
	}
	
	/**
	*	显示视图
	*	$tpl模版 $num应用编号 $cnum 单位编号
	*/
	public function getShowView($tpl,$cnum, $num, $request, $params=array())
	{
		if(!$this->agenhinfo)return 'agenh['.$num.'] not found';
		
		$arr = [
			'pagetitle'		=> $this->agenhinfo->name,
			'cnum'			=> $cnum, //单位编号
			'agenhnum'		=> $num, //应用编号
			'agenhinfo'		=> $this->agenhinfo,
			'companyinfo'	=> $this->companyinfo,
			'userinfo'	=> $this->userinfo,
			'useainfo'	=> $this->useainfo,
			'ismobile'	=> c('base')->ismobile(),
			'showheader'=> $this->isShowHeader($request)
		];
		foreach($params as $k=>$v)$arr[$k]=$v;
		return view('web.'.$tpl.'',$arr);
	}
}
