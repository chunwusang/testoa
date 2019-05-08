<?php
/**
*	单位管理主页
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Manage;

use Illuminate\Http\Request;


class HomeController extends ManageController
{
	/**
	*	显示视图
	*/
	public function getForm($cnum, $act='home',Request $request)
	{
		// $bbb = 2222;
  //   	return $bbb;
		$this->getCompanyInfo($cnum);//读取单位信息
		$this->getAuthory();//判断权限
		$this->getLimit();
		//$this->limit = 2;
		$acta	= explode('_', $act);
		$runa	= arrvalue($acta, 1, 'get').'Form';
		$obj 	= c('Unitage:'.$acta[0].'', $this->useainfo);
		$obj->setCompanyinfo($this->companyinfo, $this->limit);
		
		if(!method_exists($obj, $runa))
			return $this->returntishi(''.$runa.' not found');
		$barr	= $obj->$runa($request);
		
		//是不是返回分页
		if(isset($barr['pager'])){
			$barr['pager']	= $this->getPager('manage', $barr['total'], $barr['pager'], [$cnum,$act]);
		}
		$act 	= str_replace('_','',$act);
		$tpl	= 'manage/'.$act.'';
		$barr['helpstr']	= c('help')->helpstr($tpl,'Manage/Home','table/'.$act.'','Unitage/ChajianUnitage_'.$acta[0].'', 'Unitapi/ChajianUnitapi_'.$acta[0].'');
		// return $tpl;die;
		return $this->getShowView($tpl, $barr);
	}
}
