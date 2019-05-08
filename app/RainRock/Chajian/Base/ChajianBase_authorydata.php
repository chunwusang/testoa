<?php
/**
*	插件-权限默认数据
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;


use App\Model\Base\DeptModel;
use App\Model\Base\AgenhModel;
use DB;


class ChajianBase_authorydata extends ChajianBase
{
	public function addauthory($agenhid, $num, $mtable)
	{
		if(isempt($mtable))return;//没有主表就不用设置
		
		$cobj = $this->getNei('Rockmos:'.$mtable.'');
		if(!$cobj)return; //没有设置默认值
		
		$deptrs 	= DeptModel::where('cid', $this->companyid)->where('pid',0)->first();
		$deptname 	= $deptrs->name;
		$deptid 	= 'd'.$deptrs->id;
		$naid		= 'u'.$this->useaid.'';
		$nname		= $this->useainfo->name;
		
		$atypetoid	= array(
			'edit' 		=> 4,
			'del' 		=> 5,
			'view' 		=> 2,
			'add' 		=> 3,
			'daoru' 	=> 6,
			'daochu' 	=> 7,
		);
		
		
		//添加默认权限
		$data = $cobj->getAuthory($num);
		if($data)foreach($data as $k=>$arr){
			$iarr = array();
			
			$atype= arrvalue($arr, 'atype');
			if(!$atype)continue;
			$atype= arrvalue($atypetoid, $atype);
			if(!$atype)continue;
			
			$iarr['cid']		= $this->companyid;
			$iarr['agenhid']	= $agenhid;
			$iarr['objectid']	= $deptid;
			$iarr['objectname']	= $deptname;
			foreach($arr as $k=>$v)$iarr[$k] = $v;
			
			$iarr['atype']		= $atype;
			$wherestr	= arrvalue($iarr, 'wherestr','');
			if(!isempt($wherestr)){
				$wherestr = str_replace('{my}', '`aid`={aid}', $wherestr);
			}
			$iarr['wherestr']	= $wherestr;
			if($iarr['objectid']=='my'){
				$iarr['objectid']	= $naid;
				$iarr['objectname']	= $nname;
			}
			DB::table('authory')->insert($iarr);
		}
		
		//添加默认审核步骤
		$fdata = $cobj->getCoursedata($num);
		if($fdata && is_array($fdata)){
			AgenhModel::where('id', $agenhid)->update(['isflow'=>1]);
			foreach($fdata as $k=>$arr){
				$iarr = $arr;
				$iarr['cid']		= $this->companyid;
				$iarr['agenhid']	= $agenhid;
				DB::table('flowcourse')->insert($iarr);
			}
		}
	}
}