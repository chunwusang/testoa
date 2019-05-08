<?php 
/**
	html相关插件
*/
namespace App\RainRock\Chajian\Base;

use App\Model\Base\DeptModel;

class ChajianBase_dept extends ChajianBase{
	
	/**
	*	保存部门Id
	*/
	public function save($cid=0,$id, $cans)
	{
		if($cid==0)$cid = $this->companyid;
		$data = false;
		if($id>0){
			$data = DeptModel::where(['cid'=>$cid,'id'=>$id])->first();
		}
		if(!$data){
			if($id<=0)$id 	= $this->getmaxid($cid);
			$data 	= new DeptModel();
		}
		$data->cid 		= $cid;
		$data->id 		= $id;
		$data->optdt 	= nowdt();
		foreach($cans as $k=>$v)$data->$k = $v;
		$data->save();
		$data->id 		= $id;
		return $data;
	}
	
	public function getmaxid($cid)
	{
		$id  = 1;
		$xu	 = 0;
		$firset	= DeptModel::where('cid', $cid)->orderBy('id', 'asc')->get();
		if($firset){
			foreach($firset as $k=>$rs){
				$xu++;
				$id = $rs->id + 1;
				if($xu!=$rs->id){
					$id = $xu;
					break;
				}
			}
		}
		return $id;
	}
	
	/**
	*	获取顶级部门Id和名称
	*/
	public function getroot($cid=0)
	{
		if($cid==0)$cid = $this->companyid;
		$firset	= DeptModel::where('cid', $cid)->where('pid', 0)->first();
		return $firset;
	}
	
	public function getrootid($cid=0)
	{
		$id = 1;
		$frs = $this->getroot($cid);
		if($frs)$id = $frs->id;
		return $id;
	}
	
	/**
	*	选择部门的
	*/
	public function getDeptData($cid=0, $uida=false)
	{
		if($cid==0)$cid = $this->companyid;
		$obj 		= DeptModel::select('id','name','pid')->where('cid', $cid)->where('status', 1);
		if($uida!==false){
			$depar= array();
			$dids = '0';
			foreach($uida as $k=>$rs){
				$dids .= ','.$rs['deptid'].'';
				if(!isempt($rs['deptids']))$dids .= ','.$rs['deptids'].'';
				if(!isempt($rs['deptpath']))$dids .= ','.$rs['deptpath'].'';
			}
			$dida = explode(',', $dids);
			$obj->whereIn('id', $dida);
		}
		return $obj->orderBy('sort','desc')->get();	
	}
	
	/**
	*	获取部门
	*/
	public function getDeptArr($cid)
	{
		$this->getDeptArrss = array();
		$rows = DeptModel::where('cid', $cid)->orderBy('sort','desc')->get();
		$this->getDeptArrs($rows, 0, 0);
		return $this->getDeptArrss;
	}
	private $getDeptArrss;
	private function getDeptArrs($rows, $pid, $level)
	{
		foreach($rows as $k=>$rs){
			if($rs->pid == $pid){
				$rs->level = $level;
				$this->getDeptArrss[] = $rs;
				$this->getDeptArrs($rows, $rs->id, $level+1);
			}
		}
	}
}                                  