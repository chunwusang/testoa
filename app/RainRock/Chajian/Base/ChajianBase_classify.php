<?php
/**
*	插件-公共分类
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\ClassifyModel;

class ChajianBase_classify extends ChajianBase
{
	
	private	$getypsarr = array();
	
	//编号获取ID
	public function numtoid($num)
	{
		$id  = 0;
		$ors = ClassifyModel::where(['cid'=>$this->companyid,'num'=>$num])->first();
		if($ors)$id = $ors->id;
		return $id;
	}
	
	/**
	*	判断是否存在，不存在就添加哦
	*/
	public function changetype($num, $name)
	{
		$id = $this->numtoid($num);
		if($id==0){
			$nobj = new ClassifyModel();
			$nobj->cid 	= $this->companyid;
			$nobj->name = $name;
			$nobj->num  = $num;
			$nobj->pid 	= 0;
			$nobj->optdt = nowdt();
			$nobj->aid 	= $this->useaid;
			$nobj->save();
		}
	}
	
	/**
	*	获取分类的数据源
	*/
	public function data($data, $frs)
	{
		$num  = objvalue($frs,'attr','numsts');
		return $this->getdata($num);
	}
	
	public function getdata($num)
	{
		$barr = array();
		$this->datatrarr = array();
		$id   = $this->numtoid($num);
		if($id>0){
			$this->datatree($id, 0);
		}
		foreach($this->datatrarr as $k=>$rs){
			$barr[] = array(
				'value' => $rs->id,
				'level'	=> $rs->level,
				'name' 	=> $rs->name,
				'padding' => $rs->padding,
			);
		}
		return $barr;
	}
	
	private function datatree($id, $xu)
	{
		$rows = ClassifyModel::where(['cid'=>$this->companyid,'pid'=>$id])->orderBy('sort')->get();
		foreach($rows as $k=>$rs){
			$rs->padding = $xu*30;
			$rs->level   = $xu;
			$this->datatrarr[] = $rs;
			$this->datatree($rs->id, $xu+1);
		}
	}
	
	/**
	*	获取路径
	*/
	public function getpatharr($id)
	{
		$lujarr = array();
		$this->folderarr = array();
		$this->folderarrget($id);
		foreach($this->folderarr as $k=>$rs){
			$lujarr[] = array(
				'name' 		=> $rs->name,
				'id' 		=> $rs->id,
				'pid'  		=> $rs->pid,
			);
		}
		return $lujarr;
	}
	private function folderarrget($id)
	{
		$frs = ClassifyModel::find($id);
		if($frs){
			if($frs->pid>0)$this->folderarrget($frs->pid);
			$this->folderarr[] = $frs;
		}
	}
	
	/**
	*	获取所有下级Id
	*/
	
	
	/**
	*	根据名称如：技术姿势/PHP知识 得到对应ID
	*/
	public function gettypeid($djnum,$s)
	{
		if(isset($this->getypsarr[$s]))return $this->getypsarr[$s];
		$sid = 0;
		$s 	 = str_replace(',','/', $s);
		$djid= $this->numtoid($djnum);
		if($djid==0){
			return 0;
		}
		$dsja= $djid;
		$sarr= explode('/', $s);
		foreach($sarr as $safs){
			$pid 	= $djid;
			$djrs 	= ClassifyModel::where(['cid'=>$this->companyid,'pid'=>$pid,'name'=>$safs])->first();
			if($djrs){
				$djid = $djrs->id;
			}else{
				$nobj = new ClassifyModel();
				$nobj->cid 	= $this->companyid;
				$nobj->name = $safs;
				$nobj->pid 	= $pid;
				$nobj->optdt = nowdt();
				$nobj->aid 	= $this->useaid;
				$nobj->save();
				$djid = $nobj->id;
			}
		}
		if($djid != $dsja)$sid 	= $djid;
		
		$sarr = [$sid, $safs];
		$this->getypsarr[$s] 	= $sarr;
		
		return $sarr;
	}
	
}