<?php
/**
*	数据选项默认值,默认流程,默认权限
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Rockmos;

use App\RainRock\Chajian\Chajian;
use App\Model\Base\OptionModel;

class ChajianRockmos extends Chajian
{
	/**
	*	获取默认的数据选项
	*/
	public function getOptiondata($num)
	{
		if(isset($this->$num)){
			$cdata = $this->$num;
			if(is_string($cdata)){
				$cdata 	= c('array')->strtoarray($cdata);
				$barr	= array();
				foreach($cdata as $k=>$rs){
					$nas  = $rs[1];
					$val  = $rs[0];
					if($val==$nas)$val = '';
					$barr[] = array(
						'name' => $nas,
						'value' => $val,
					);
				}
				return $barr;
			}else if(is_array($cdata)){
				return $cdata;
			}
		}
		return array();
	}
	
	/**
	*	获取默认流程
	*/
	public function getCoursedata($num)
	{
		return array();
	}
	
	/**
	*	获取默认的权限
	*/
	public function getAuthory($num)
	{
		return array();
	}
	
	/**
	*	默认数据选项
	*/
	public function defaultOption()
	{
		return array();
	}
	public function adddefaultOption($pname)
	{
		if(isempt($pname))return 0;
		$barr 	= $this->defaultOption();
		$ppid	= 0;
		$dxu	= 0;
		foreach($barr as $k=>$rs){
			$num = arrvalue($rs, 'num');
			if(isempt($num))continue;
			$fors= OptionModel::where(['cid'=>$this->companyid,'num'=>$num])->first();
			if($fors)continue;
			
			if($ppid==0){
				$pone = OptionModel::where('cid', $this->companyid)->where('pid',0)->where('name', $pname)->first();
				if(!$pone){
					$pone = new OptionModel();
					$pone->name = $pname;
					$pone->pid = 0;
					$pone->cid = $this->companyid;
					$pone->optdt = nowdt();
					$pone->save();
				}
				$ppid = $pone->id;
			}
			
			$pone = new OptionModel();
			$pone->name = arrvalue($rs, 'name');
			$pone->num = $num;
			$pone->pid = $ppid;
			$pone->cid = $this->companyid;
			$pone->optdt = nowdt();
			$pone->explain 	= arrvalue($rs,'explain');
			$pone->value 	= arrvalue($rs,'value');
			$pone->save();
			$dxu++;//导入数量
		}
		return $dxu;
	}
	
}