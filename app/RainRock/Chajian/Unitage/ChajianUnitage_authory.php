<?php
/**
*	管理首页-组
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\AuthoryModel;
use App\Model\Base\AgenhModel;


class ChajianUnitage_authory extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$obj 	= AuthoryModel::select();
		$obj->where('cid' ,$this->companyid);
		$atype		= nulltoempty($request->get('atype'));
		$agenhid	= nulltoempty($request->get('agenhid'));
		
		if($atype!='')$obj->where('atype', $atype);
		if($agenhid!='')$obj->where('agenhid', $agenhid);
		
		$total 	= $obj->count();
		$data 	= $obj->orderBy('id','desc')->simplePaginate($this->limit)->getCollection();
		foreach($data as $k=>$rs){
			if($rs->agenhid>0){
				$agrs = AgenhModel::find($rs->agenhid);
				if($agrs)$data[$k]->agenhid = ''.$rs->agenhid.'.'.$agrs->name.'';
			}
		}
		$agenharr	= $this->getNei('agenh')->getAtypeAgenh();
		foreach($agenharr as $lx=>$arrs){
			foreach($arrs as $k=>$rs)$agenharr[$lx][$k]->sel = ($rs->id==$agenhid)?'selected' : '';
		}
		
		return [
			'data'		=> $data,
			'atype'		=> $atype,
			'total'		=> $total,
			'agenharr'	=> $agenharr,
			'pager'		=> ['atype'=>$atype,'agenhid'=>$agenhid],
			'mtable' 	=> c('rockjm')->encrypt('authory')
		];
	}
	
	/**
	*	编辑获取
	*/
	public function editForm($request)
	{
		$id 	= (int)$request->get('id','0');
		$data 	= AuthoryModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 		= 0;
			$data->status 	= 1;
			$data->objectid 	= '';
			$data->objectname 	= '';
			$data->atype 	= 0;
			$data->agenhid 	= 0;
			$data->receid 	= '';
			$data->recename	= '';
			$data->explain	= '';
			$data->wherestr	= '';
		}
		
		$ebts		= ($data->id==0) ? 'addtext' : 'edittext';
		
		$agenharr	= $this->getNei('agenh')->getAtypeAgenh();
		foreach($agenharr as $lx=>$arrs){
			foreach($arrs as $k=>$rs)$agenharr[$lx][$k]->sel = ($rs->id==$data->agenhid)?'selected' : '';
		}
		
		return [
			'pagetitles' 	=> trans('table/authory.'.$ebts.''),
			'data'			=> $data,
			'agenharr'		=> $agenharr,
		];
	}
}