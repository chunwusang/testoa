<?php
/**
*	管理首页-数据选项
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitage;


use App\Model\Base\OptionModel;

class ChajianUnitage_option extends ChajianUnitage
{
	
	public function getForm($request)
	{
		$pid	= (int)$request->input('pid','0');
		$data 	= OptionModel::where('cid', $this->companyid)
				->where('pid', $pid)
				->where('name','<>', '')
				->orderBy('sort','asc')
				->get();
		foreach($data as $k=>$rs){
			$data[$k]->stotal = OptionModel::where('pid', $rs->id)->count();
		}			
		
		return [
			'data' 		=> $data,
			'pid'		=> $pid,
			'mtable' 	=> c('rockjm')->encrypt('option')
		];
	}
	

}