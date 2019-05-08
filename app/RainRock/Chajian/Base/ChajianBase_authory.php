<?php
/**
*	插件-权限
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*	使用方法 $obj = c('authory');
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\AuthoryModel;



class ChajianBase_authory extends ChajianBase
{
	private $useatypetmp = -1;
	
	private $deptrootrs; //顶级部门信息
	private $deptrootid	 = -1; //顶级部门id
	
	
	protected function initChajian()
	{
		$this->deptrootrs = $this->getNei('dept')->getroot();
		if($this->deptrootrs)$this->deptrootid = $this->deptrootrs->id;
	}
	
	/**
	*	是否有新增权限
	*/
	public function isadd($agenhid)
	{
		if($this->useatype()!=0)return 1; //是管理员都可以新增
		return $this->ispanduan($agenhid, 3);
	}
	
	/**
	*	是否有导出权限
	*/
	public function isdaochu($agenhid)
	{
		return $this->ispanduan($agenhid, 7);
	}
	
	/**
	*	是否有导入权限
	*/
	public function isdaoru($agenhid)
	{
		return $this->ispanduan($agenhid, 6);
	}
	
	/**
	*	当前用户类型，权限，判断当前用户是不是管理员，0普通用户,1管理员,2超级管理员
	*/
	public function useatype()
	{
		if($this->useatypetmp !=-1 )return $this->useatypetmp;
		$qx = 0;
		if($this->useainfo->uid==$this->useainfo->company->uid)$qx = 2;//创建人是超级管理员
		if($qx != 2){
			$rows 	= AuthoryModel::where('cid', $this->companyid)
					->whereIn('atype', [0,1])
					->where('status', 1)
					->get();
			$barr	= c('contain', $this->useainfo)->getcontarr($rows, 'objectid');
			if($barr)foreach($barr as $k=>$rs){
				if($rs->atype==1){
					$qx = 2;
					break;
				}
				if($rs->atype==0){
					$qx = 1;//普通管理员
				}
			}				
		}
		$this->useatypetmp = $qx;
		return $qx;
	}
	
	
	//$atype 3新增 6导入,7导出 0普通管理员,1超级管理员
	private function ispanduan($agenhid, $atype)
	{
		$is	 	= 0;
		$barr 	= $this->getwherearr($agenhid, $atype);
		if(count($barr)>0)$is = 1;
		return $is;
	}
	
	/**
	*	查看条件返回all就是全部
	*/
	public function getviewwhere($agenhid)
	{
		return $this->getwherearr($agenhid, 2);
	}
	
	/**
	*	编辑的
	*/
	public function geteditwhere($agenhid)
	{
		return 	$this->getwherearr($agenhid, 4);
	}
	
	/**
	*	删除的
	*/
	public function getdelwhere($agenhid)
	{
		return 	$this->getwherearr($agenhid, 5);
	}
	
	//获取条件
	private function getwherearr($agenhid, $atype)
	{
		$rows 	= AuthoryModel::where('cid', $this->companyid)
					->where('agenhid', $agenhid)
					->where('atype', $atype)
					->where('status', 1)
					->get();
		$conobj	= $this->getNei('contain');		
		$barr	= $conobj->getcontarr($rows, 'objectid');
		//print_r($barr->toArray());
		if($barr)foreach($barr as $k=>$rs){
			$wherestr 	= $rs->wherestr;
			$receid 	= $rs->receid;
			$where		= '1=1';
			
			//判断是不是包含顶级部门
			if(contain(','.$receid.',',',d'.$this->deptrootid.',')){
				$where		= '1=1';
				$receid		= '';
				$wherestr	= '';
			}
			
			if($wherestr != '' || $receid != ''){
				$where  = '';
				if($wherestr != '')
					$where  = $this->getNei('devdata')->replacesql($wherestr, false);
				
				if($receid != ''){
					$aids = $conobj->getaids($receid);
					if(!$aids)$aids = '0';
					if($where != '')$where.=' and ';
					$where .= '`aid` in('.$aids.')';
				}
				$where = '('.$where.')';
			}
			
			$barr[$k]->wherestr = $where;
		}
		return $barr;	
	}
}