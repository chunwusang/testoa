<?php
/**
*	用户单位下主页
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

namespace App\Http\Controllers\Users;

use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\CompanyModel;
use App\Model\Base\AgentmenuModel;
use Auth;
use App\Model\Base\BaseModel;
use Rock;
use Illuminate\Http\Request;

class HomeController extends UsersController
{

    /**
     * 主页显示，$cnum对应单位编号
     *这里是显示导航栏内容
     */
    public function index($cnum='')
    {
    	// $bbb = 123123123;
    	// return $bbb;
		//获取默认的单位
		$auth 		 = Auth::user();
		$joincompany = $auth->joincompany()->get();//查出
		// $a = $auth->devcid;
		// die($a);
		$nowcompany	 = $allcompany	= $agenharr = array();//给他一个空数组
		// die($joincompany);
		if($joincompany){
			foreach($joincompany as $k=>$rs){
			// die($rs);
			// die($rs->company);
				//需要激活状态
				if($rs->status==1){//如果status=1则说明以激活 状态0待激活,1已激活,2停用
					$allcompany[] 	= $rs->company;//将company的值赋值给$allcompany
					// die($allcompany);
					if(!$nowcompany)$nowcompany = $rs->company;//如果$nowcompany不为空就赋值

					if(!isempt($cnum)){//isempt()函数判断变量是否为空  //传进去的参数为空,或者null,或者为'',且不能为数字和字符串;   则返回true
						//不为空走这里
						if($cnum==$rs->company->num)$nowcompany = $rs->company;//判断传进来的$cnum是否等于num
					}else{
						//空值走这里

						if($auth->devcid==$rs->cid)$nowcompany = $rs->company;//判断
					}
				}
			}
			//显示对应应用
			if($nowcompany){
				$useainfo	= UseraModel::where('cid', $nowcompany->id)->where('uid', $auth->id)->first();
				
				$agenhbarr	= c('agenh',$useainfo)->getAgenh();
				
				$agenharr	= $agenhbarr[0];
				$agenhtarr	= $agenhbarr[1];
				$xu 		= 0;
				foreach($agenhtarr as $at=>$v){
					if($v==0)$v = '';
					$agenhtarr[$at] = [$v, $xu++];
				}
				
				//简单显示
				foreach($agenharr as $at=>$arow){
					foreach($arow as $k1=>$rs1){
						$rs2 = new \StdClass();
						$rs2->id = $rs1->id;
						$rs2->name = $rs1->name;
						$rs2->num = $rs1->num;
						$rs2->url = $rs1->url;
						$rs2->face 	= $rs1->face;
						$rs2->atypes = $rs1->atypes;
						$rs2->stotal = $rs1->stotal;
						$agenharr[$at][$k1] = $rs2;
					}
				}
			}
		}
		if(!$nowcompany)return redirect(route('usersmanage')); //没有加入
	
		$barr = [
			'joincompany' 	=> $allcompany, //加入单位企业
			'companyinfo' 	=> $nowcompany,
			'agenharr' 		=> $agenharr,
			'agenhtarr' 	=> $agenhtarr,
			'useainfo' 		=> $useainfo,
		];
		
		//用户类型0,1,2
		$barr['useatype']	= c('authory', $useainfo)->useatype();
//		 return $barr；
		// var_dump(111);die;
		// return $this->getShowView('users/index',$barr);
        return view('users/index',$barr);
    }


	
	/**
	*	用户首页
	*/
	/**
	*   这里是显示中间内容
	*/
    public function home($cnum,Request $request)
    {
    	// $type = $request->input('type');
    	// var_dump($type);die;
    	// return $bb;
    	// $bbbb = 123123123123;
    	// return $bbbb;
		$auth 		 = Auth::user();
		$companyinfo = CompanyModel::where('num', $cnum)->first();
		$useainfo 	 = UseraModel::where('cid', $companyinfo->id)
						->where('uid',$auth->id)
						->where('status',1)
						->first();
		if(!$useainfo)return $this->returntishi('error usera');
		
		$officicdata  = $noticedata  = $meetdata = $tododata  = $applydata  = array();
		$allagenhnum  = array();
		
		//首页快捷键
		$showlista 	= explode(',', 'flow-daiban');
		$agenharrs	= $agenharr = array();
		$agenhbarr	= c('agenh',$useainfo)->getAgenh();	
		//print_r($agenhbarr);exit;
		foreach($agenhbarr[0] as $atype=>$ars)$agenharrs = array_merge($agenharrs, $ars);
		foreach($agenharrs as $k=>$rs){
			$allagenhnum[] = $rs->num;
			if(in_array($rs->num, $showlista))$agenharr[] = $rs;
			if($rs->issy==1)$agenharr[] = $rs;
		}
		
		
		//首页简要列表
		if(in_array('notice',$allagenhnum))
			$noticedata	 = Rock::getFlow('notice', $useainfo)->getBaseData('all');
		
		if(in_array('flow-apply',$allagenhnum))
			$applydata	 = Rock::getFlow('flow-apply', $useainfo)->getBaseData('woapply');
		
		if(in_array('meet',$allagenhnum))
			$meetdata	 = Rock::getFlow('meet', $useainfo)->getBaseData('today');
		
		// if(in_array('officic',$allagenhnum))
		// 	$officicdata = Rock::getFlow('officic', $useainfo)->getBaseData('all');
		
		if(in_array('todo',$allagenhnum))
			$tododata	 = Rock::getFlow('todo', $useainfo)->getBaseData('mywd');
		
		
		$barr = [
			'companyinfo' 	=> $companyinfo,//
			'officicdata' 	=> $officicdata,//空数组
			'noticedata' 	=> $noticedata,
			'tododata' 		=> $tododata,
			'meetdata' 		=> $meetdata,
			'applydata' 	=> $applydata,
			'agenharr' 		=> $agenharr,//返回的是中间顶部的五个图标的数据
		];
		// var_dump(123);
		// die;
		// return $barr['officicdata'];
		// return $this->getShowView('users/home',$barr);
		return view('users/home',$barr);	
		
    }

    /*
	test方法是测试用的
    */

    public function test(Request $request)
    {
    	// var_dump('123');die;
    	// return view('users/index');
    	$type = $request->get('type');
    	// var_dump($bb);
    	$arr = '这只是个测试';
    	// var_dump($arr);die;
    	return $this->getShowView('view',[
    		'test'		=> $arr,
    	],$type);
    }

}
