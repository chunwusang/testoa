<?php
/**
*	管理首页-组
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;

use App\Model\Base\GroupModel;
use App\Model\Base\SjoinModel;
use App\Model\Base\UseraModel;


class ChajianUnitage_group extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$total 		= 0;
		$gid		= (int)$request->get('gid','0');
		$groupdata  = $this->getNei('usera')->getGroupData($this->companyid, false);
		$data 		= array();
		
		//读取组下的人员
		if($gid>0){
			$sarr[] = 0;
			$sdata= SjoinModel::where('cid', $this->companyid)->where('type','gu')->where('mid', $gid)->get();
			foreach($sdata as $k=>$rs)$sarr[]=$rs->sid;
			$data = UseraModel::where('cid', $this->companyid)->whereIn('id', $sarr)->get();
		}
	
		return [
			'data' 		=> $data,
			'groupdata' => $groupdata,
			'gid' 		=> $gid,
			'mtable' 	=> c('rockjm')->encrypt('group')
		];
	}
	
	/**
	*	编辑获取
	*/
	public function editForm($request)
	{
		$id 	= (int)$request->get('id','0');
		
		$data 	= GroupModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->name = '';
			$data->sort= 0;
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		return [
			'pagetitles' 	=> trans('table/group.'.$ebts.''),
			'data'			=> $data
		];
	}
}