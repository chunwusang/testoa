<?php
/**
*	插件-数据选项
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*	使用方法 $obj = c('option');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\OptionModel;

class ChajianBase_option extends ChajianBase
{
	
	/**
	*	获取数据选项的值,0value
	*/
	public function getval($num, $lx=0, $dev='')
	{
		$val = $dev;
		$obj = OptionModel::where('cid', $this->companyid)->where('num', $num)->first();
		if($obj){
			$gfa = ['value','name','optdt','id'];
			$val = objvalue($obj, $gfa[$lx], $val);
		}
		return $val;
	}
	
	/**
	*	设置数据选项
	*/
	public function setval($num,$val,$sm='')
	{
		$obj = OptionModel::where('cid', $this->companyid)->where('num', $num)->first();
		if(!$obj){
			if(isempt($val))return;
			$obj = new OptionModel();
			$obj->num 	= $num;
			$obj->cid 	= $this->companyid;
		}
		if($sm != '')$obj->explain 	= $sm;
		$obj->value = $val;
		$obj->optdt = nowdt();
		$obj->save();
		return $obj->id;
	}
	
	public function getone($where)
	{
		$obj = OptionModel::where('cid', $this->companyid)->whereRaw($where)->first();
		if(!$obj)return false;
		return [
			'name' 	=> $obj->name,
			'sort' 	=> $obj->sort,
			'id' 	=> $obj->id,
			'value' => $obj->value,
			'num' 	=> $obj->num,
			'optdt' => $obj->optdt,
		];
	}
	
	/**
	*	获取数据[{}]
	*/
	public function getdata($num, $pname='', $sname='', $mtable='')
	{
		$pid = $this->getval($num, 3);
		$data= array();
		if($pid!=''){
			$data = OptionModel::where('pid', $pid)->orderBy('sort','asc')->get()->toArray();
		}
		//没有数据就用默认数据并保存到数据库
		if(!$data && $mtable!=''){
			$cobj = $this->getNei('Rockmos:'.$mtable.'');
			if($cobj)$data = $cobj->getOptiondata($num);
			
			//自动加到系统选项
			if($data && $pname!='' && $sname!=''){
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
				
				//根据保存num
				if($pid==''){
					$pone = new OptionModel();
					$pone->name = $sname;
					$pone->num = $num;
					$pone->pid = $ppid;
					$pone->cid = $this->companyid;
					$pone->optdt = nowdt();
					$pone->save();
					$pid = $pone->id;
				}
				
				foreach($data as $k=>$rs){
					$oboj = new OptionModel();
					$oboj->pid = $pid;
					$oboj->sort = $k;
					$oboj->optdt = nowdt();
					$oboj->cid = $this->companyid;
					foreach($rs as $k1=>$v1)$oboj->$k1 = $v1;
					$oboj->save();
				}
			}
		}
		return $data;
	}
	
	/**
	*	判断是否有安装企业微信
	*/
	public function iswxqy()
	{
		$copid 	= $this->getval('weixinqy_corpid');
		$bo 	= 0;
		if(!isempt($copid))$bo = 1;
		return $bo;
	}
}