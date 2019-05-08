<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;


use App\Model\Base\CompanyModel;
use App\Model\Base\DeptModel;
use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\AgentModel;
use App\Model\Base\LogModel;

class HomeController extends AdminController
{
  

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	
		$companytotal = CompanyModel::count();
		$userstotal = UsersModel::count();
		$useratotal = UseraModel::count();
		$depttotal = DeptModel::count();
		$agenttotal = AgentModel::count();
		$logtotal 	= LogModel::whereRaw("`optdt` like '".nowdt('dt')."%'")->count();
		$logtotals 	= LogModel::whereRaw("`optdt` like '".nowdt('dt')."%' and `level`=2")->count();
		/*
		'companytotal' => '单位数量',
		'userstotal' => '平台用户数',
		'useratotal' => '单位下用户数',
		'depttotal' => '部门数',
		'agenttotal' => '应用数',
		
		'logtotal' 	=> '今日日志',
		*/
        return $this->getShowView('admin/home',[
			'companytotal' 	=> $companytotal,//单位数量(公司)
			'userstotal' 	=> $userstotal,//平台用户数量
			'useratotal' 	=> $useratotal,//单位下用户数
			'depttotal' 	=> $depttotal,//部门数量
			'agenttotal' 	=> $agenttotal,//应用数量
			'logtotal' 		=> $logtotal,//今日日志总数
			'logtotals' 		=> $logtotals,//今日level=2日志总数
		]);
    }
    
    
}
