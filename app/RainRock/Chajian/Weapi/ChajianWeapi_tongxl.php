<?php
/**
*	插件-api移动端首页
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\Model\Base\DeptModel;
use App\Model\Base\UseraModel;

class ChajianWeapi_tongxl extends ChajianWeapi
{
	/**
	*	获取通讯录
	*/
	public function getData()
	{
		$deptarr	= $useaarr = $grouparr = $deptroot = array();
		$jm 		= $this->getNei('rockjm');
		if($this->companyid>0){
			$deptarr	= DeptModel::where('cid', $this->companyid)->where('status',1)->orderBy('sort','desc')->get();
			
			$useaarr	= UseraModel::select('id','name','cid','uid','deptid','deptname','deptallname','gender','mobile','status','email','tel','pingyin','deptids','deptpath','position')
							->where('cid', $this->companyid)
							->where('status','<>',2)
							->where('istxl',1)
							->orderBy('sort','desc')
							->get();
			$deptroot	= $this->getNei('Base:dept')->getroot();
			
			$udepta 	= array();
			foreach($useaarr as $k=>$rs){
				if(!isempt($rs->deptpath)){
					$depta = explode(',', $rs->deptpath);
					foreach($depta as $did){
						if(!isset($udepta[$did]))$udepta[$did] = 0;
						$udepta[$did]++;
					}
				}
				$rs->mobile = $jm->encrypt($rs->mobile);
				$rs->email  = $jm->encrypt($rs->email);
			}
				
			foreach($deptarr as $k=>$rs){
				$deptarr[$k]->ntotal = arrvalue($udepta, $rs->id, 0);
			}
			
			$reimobj = $this->getNei('reim');
			
			$grouparr= $reimobj->getgroup();
		}
		$barr = [
			'deptarr' => $deptarr,
			'useaarr' => $useaarr,
			'deptroot' => $deptroot,
			'grouparr' => $grouparr, //会话
		];
		return returnsuccess($barr);
	}
}