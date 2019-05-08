<?php
/**
*	应用列表
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Model\Base\AgenhModel;
use App\Model\Base\BaseModel;
use Rock;

class HomeController extends WebController
{
  
	/**
	*	列表页面
	*/
    public function index($cnum, Request $request)
    {
		$this->getCompany($cnum);
		if($this->useaid==0)return $this->returntishi(trans('validation.notagenh',['num'=>$num]));
		
		$barr['jspath']		= $jspath;
		
		return $this->getShowView('list', $cnum, $num, $request,$barr);
    }
	
	/**
	*	列表应用数据读取
	*/
	public function listdata($cnum, $num, Request $request)
	{
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));

		$limit 		= (int)$request->get('limit','0');
		$atype 		= $request->get('atype');
		if($limit<1)$limit = 10;
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		$flow->limit= $limit;
		$page 		= 1;
		if($request->has('offset')){
			$page	= (int)$request->get('offset') / $limit + 1;
		}
		
		//排序
		if($request->has('sort') && $request->has('order')){
			$flow->defaultorder = ''.$request->get('sort').','.$request->get('order').'';
		}
			
		if($request->has('page'))$page	= (int)$request->get('page');
		if($page<1)$page = 1;
		
		$data 		= $flow->getYingData($atype, $page, 1);
		
		//微章角标
		$bagstr		= $request->get('bagstr');
		$bagarr		= array();
		if(!isempt($bagstr)){
			$bagstra= explode(',', $bagstr);
			foreach($bagstra as $bag)$bagarr[$bag] = $flow->getstotal($bag);
		}
		
	
		return [
			'total' => $data['rowsCount'],
			'rows' 	=> $data['rows'],
			'bagarr'=> $bagarr
		];
	}
	
	public function postlistRun($cnum, $num, $act, Request $request)
	{
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		if(!method_exists($flow, $act))return $this->returnerror(trans('validation.notact',['act'=>$act]));
		
		$barr 		= $flow->$act($request);
		if(!$barr['success'])return $this->returnerror($barr['msg']);
	}
}