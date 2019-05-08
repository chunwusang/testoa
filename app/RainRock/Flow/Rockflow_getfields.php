<?php
/**
*	工作流-核心文件_获取字段
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\RainRock\Flow;

use App\Model\Base\AgentModel;
use App\Model\Base\FlowchaoModel;

trait Rockflow_getfields
{
	/**
	*	获取字段,$lx=0默认,1列表页和设置使用,2详情的,3录入,4导入,5导出,6手机列表
	*/
	public function getFieldsArr($lx=0)
	{
		$laxrr	= array('dev'=> 0,'list' => 1,'cog'=> 1,'xiang'=> 2,'lu'=> 3,'daoru'=> 4,'daochu'=>5,'mlist'=>6);
		if(is_string($lx))$lx = arrvalue($laxrr, $lx, 0);
		if($this->fieldsArr)return $this->getFieldsArrs($lx, $this->fieldsArr);
		if($this->agenhinfo->agentid==0)return array();
		$rows 	= AgentModel::find($this->agenhinfo->agentid)
					->getFields()
					->orderBy('sort')->get();
		$arr 	= array();
		$fields_islu	= $this->agenhinfo->fields_islu; //录入
		$fields_islb	= $this->agenhinfo->fields_islb;	//列表
		$fields_ispx	= $this->agenhinfo->fields_ispx;	//排序
		$fields_isss	= $this->agenhinfo->fields_isss;	//搜索
		
		if(!isempt($fields_islu))$fields_islu = ','.$fields_islu.',';
		if(!isempt($fields_islb))$fields_islb = ','.$fields_islb.',';
		if(!isempt($fields_ispx))$fields_ispx = ','.$fields_ispx.',';
		if(!isempt($fields_isss))$fields_isss = ','.$fields_isss.',';
		
		$dvssou	= array('text','date','datetime');
		$dvssox	= array('date','datetime');
		$dvssoq	= array('htmlediter','textarea','uploadfile');
		
		if($lx==1){
			$arr[] = $this->createFields('','sysxu','number');
		}
		
		if($lx==1 || $lx==6 || $lx==2){
			//$arr[] = $this->createFields('ID','id','number');
			
			if($this->isflow>0){
				$das = $this->createFields('申请人部门','base_deptname');
				$das->iszs = 1;
				$arr[] = $das;
				
				$das = $this->createFields('申请人','base_name');
				$das->iszs = 1;
				$arr[] = $das;
				
				$das = $this->createFields('单号','base_sericnum');
				$das->islb  = 0;
				$das->iszs = 1;
				$arr[] = $das;
			}
		}
		
		foreach($rows as $k=>$rs)$arr[] = $rs;
		
		if($lx==1 || $lx==6){
			if($this->isflow>0){
				$das 	= $this->createFields('状态','status','select');
				$das->isml = 1;
				$arr[] 	= $das;
			}
		}
		
		$fnamearr	= $this->flowfieldsname($lx); //其他字段
		if($fnamearr){
			$narr 		= $this->adddatas($fnamearr,'fields_before');
			if($narr)$arr 	= array_merge($narr, $arr);
			$arrs 			= array();
			foreach($arr as $k=>$rs){
				$arrs[] = $rs;
				$fids = 'fields_'.$rs->fields.'_after';
				$narr = $this->adddatas($fnamearr, $fids);
				if($narr)foreach($narr as $k1=>$rs1)$arrs[] = $rs1;
			}
			$arr 	= $arrs;
			
			//最后面
			$narr 		= $this->adddatas($fnamearr,'fields_after');
			if($narr)$arr 	= array_merge($arr, $narr);
		}
		
		//录入的是否需要上传文件
		if($lx==3 || $lx==2){
			$isup = (int)objvalue($this->agentinfo,'isup','0');
			if($isup>0){
				$uptypea = explode('|', $this->agentinfo->uptype);
				$das = $this->createFields(arrvalue($uptypea, 1, '相关文件'), 'sysupfile','uploadfile');
				$das->islu = 1;
				$das->iszs = 1;
				$das->isbt = $isup==1 ? 1: 0;
				$das->data = $uptypea[0];
				$arr[] = $das;
			}
		}
		
		
		foreach($arr as $k=>$rs){
			$islu 	= $ispx = $isss = $islb = $iszs = 0;
			$field1 = ','.$rs->fields.',';
			$fieldstype = $rs->fieldstype;
			
			//替换的
			if(isset($fnamearr[$rs->fields])){
				$bstar 	  = $fnamearr[$rs->fields];
				if($bstar===false)continue; //跳出
				if(is_string($bstar)){
					$rs->name = $bstar;
				}else{
					foreach($bstar as $k1=>$v1)$rs->$k1 = $v1;
				}
				unset($fnamearr[$rs->fields]);
			}
			
			$iszs	= $rs->iszs;
			
			$islu	= $rs->islu;
			if(!isempt($fields_islu)){
				$islu = 0;
				if(contain($fields_islu, $field1))$islu = 1;
			}
			
			$islb	= $rs->islb;
			if(!isempt($fields_islb)){
				$islb = 0;
				if(contain($fields_islb, $field1))$islb = 1;
			}
			
			$ispx	= $rs->ispx;
			if(!isempt($fields_ispx)){
				$ispx = 0;
				if(contain($fields_ispx, $field1))$islb = 1;
			}
			
			$isss	= $rs->isss;
			if(!isempt($fields_isss)){
				if(contain($fields_isss, $field1))$isss = 1;
			}
			
			
			$rs->islu = $islu; //录入
			$rs->ispx = $ispx;  //排序
			$rs->islb = $islb; //列表页
			$rs->isss = $isss; //搜索
			
			$arr[$k] 	 = $rs;
		}
		
		$this->fieldsArr = $arr;
		return $this->getFieldsArrs($lx, $arr);
	}
	
	private function getFieldsArrs($lx, $arr)
	{
		$barr = array();
		if($lx==3){
			$fida 	= explode(',', 'id,fields,fieldstype,data,islu,iszb,mstyle,isbt,name,attr,placeholder,height,width,lengs,dev,gongsi');
			foreach($arr as $k=>$rs){
				if($rs->status==1 && $rs->islu==1){
					$das = new \StdClass();
					foreach($fida as $fi1){
						$das->$fi1 = objvalue($rs, $fi1);
					}
					$barr[] = $das;
				}
			}
		}else if($lx==2){
			foreach($arr as $k=>$rs)
				if($rs->status==1 && $rs->iszs==1)$barr[] = $rs;
		}else if($lx==4){
			$fida 	= explode(',', 'fields,isbt,name,fieldstype');
			foreach($arr as $k=>$rs){
				if($rs->status==1 && $rs->iszb==0 && $rs->isdr){
					$das = new \StdClass();
					foreach($fida as $fi1)$das->$fi1 = $rs->$fi1;
					$barr[] = $das;
				}
			}	
		}else if($lx==6){	
			$fida 	= explode(',', 'fields,name,fieldstype');
			foreach($arr as $k=>$rs){
				if($rs->status==1 && $rs->isml){
					$das = new \StdClass();
					foreach($fida as $fi1)$das->$fi1 = $rs->$fi1;
					$barr[] = $das;
				}
			}
		}else{
			$barr = $arr;
		}
		return $barr;
	}
	
	public function createFields($name='',$fid='', $type='text')
	{
		$das = new \StdClass();
		$das->id 		= '';
		$das->name 		= $name;
		$das->fields 	= $fid;
		$das->fieldstype= $type;
		$das->islu 		= 0;
		$das->status 	= 1;
		$das->data 		= '';
		$das->mstyle 	= '';
		$das->iszb 		= 0;
		$das->isbt 		= 0;
		$das->attr 		= '';
		$das->placeholder = '';
		$das->lengs 	= 0;
		$das->dev 		= '';
		$das->islb 		= 1;
		$das->ispx 		= 0;
		$das->isss 		= 0;
		$das->isdr 		= 0;
		$das->iszs 		= 0;
		$das->isml 		= 0;
		$das->gongsi 	= '';
		return $das;
	}
	
	//加数组
	private function adddatas(&$fnamearr, $key)
	{
		$arr = array();
		if(isset($fnamearr[$key])){
			foreach($fnamearr[$key] as $fid=>$farr){
				$das = $this->createFields('',$fid);
				if(is_string($farr)){
					$das->name = $farr;
				}else{
					foreach($farr as $k1=>$v1)$das->$k1 = $v1;
				}
				$arr[] = $das;
			}
			unset($fnamearr[$key]);
		}
		return $arr;
	}
	
	
	/**
	*	抄送类型
	*/	
	public function getChaosong()
	{
		$lx = 0;
		$chaoname 	= $chaoid = '';
		$fieldsobj	= new \StdClass();
		if($this->agentinfo)$lx = (int)objvalue($this->agentinfo, 'iscs', '0');
		if($lx>0){
			$fieldsobj->islu 	= 1;
			$fieldsobj->status 	= 1;
			$fieldsobj->iszb 	= 0;
			$fieldsobj->fields 		= 'sys_chaoname';
			$fieldsobj->fieldstype 	= 'changeusercheck';
			$fieldsobj->mstyle 	= '';
			$fieldsobj->isbt 	= ($lx==1) ? 1 : 0;
			$fieldsobj->name 			= '抄送';
			$fieldsobj->placeholder 	= '选择抄送给人员';
			$fieldsobj->data 	= 'sys_chaoid';
			$crs	= false;
			if($this->mid>0){
				$crs = FlowchaoModel::where('mtable', $this->mtable)->where('mid', $this->mid)->first();
			}else{
				$crs = FlowchaoModel::where('cid', $this->companyid)
					->where('aid', $this->useaid)
					->where('agentid', $this->agentid)
					->orderBy('id','desc')
					->first();
			}
			if($crs){
				$chaoname 	= $crs->chaoname;
				$chaoid 	= $crs->chaoid;
			}
		}
		
		$das = new \StdClass();
		$das->iscs = $lx;
		$das->chaoname 	= $chaoname;
		$das->chaoid 	= $chaoid;
		$das->fieldsobj = $fieldsobj;
		return $das;
	}
}