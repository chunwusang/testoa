<?php
/**
*	移动端应用显示
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Apijct;

use App\Model\Base\BaseModel;
use Illuminate\Http\Request;
use Rock;

class YingController extends ApijctController
{
	/**
	*	移动端显示应用
	*/
    public function index( Request $request)
    {
        $cnum =Request('cnum'); $num = Request('num');
		$this->getAgenh($cnum, $num);
		if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
		
		$flow 				= $this->flow = Rock::getFlow($num, $this->useainfo);
		
		//是否有新增的菜单
		$menuarr = c('agenh')->getMenuArr($this->agenhinfo, true);
		$addxu	 = -1;
		foreach($menuarr as $k=>$rs){
			$rs->disabled = '';
			if($rs->type=='add'){
				$rs->disabled = $flow->isaddqx() ? '' : 'weui_navbar_item_disabled';
				$addxu = $k;
			}
		}
		
		//应用类型分用户应用和系统的
		$yinglx		= ($this->agenhinfo->agentid>0) ? 'rocksys' : 'rockuse';
	
		$tplpath 	= 'web/'.$yinglx.'/'.$num.'_mobile';
		$autobo		= true;
		if(!file_exists(base_path('resources/views/'.$tplpath.'.blade.php'))){
			$tplpath = 'web/list/base_mobile';
			$autobo	 = false;
		}
		
		
		
		$barr['tplpath']	= $tplpath;
		$barr['menuarr']	= $menuarr;
		$barr['addxu']		= $addxu;
		$barr['searchbool']	= $autobo==false; //是否显示搜索
		$barr['autopath']	= $autobo; //是否自定义页面
		
		$larr	= $flow->flowlistview(1, $barr);
		if($larr && is_array($larr))foreach($larr as $k=>$v)$barr[$k] = $v;
        $barr['code']=200;
        $barr['msg']='success';
		return $barr;
    }
}
