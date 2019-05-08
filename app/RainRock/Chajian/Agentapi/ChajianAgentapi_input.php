<?php
/**
*	录入页面上的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Agentapi;

use DB;
use App\Model\Base\AgentModel;

class ChajianAgentapi_input extends ChajianAgentapi
{
	
	/**
	*	录入数据保存
	*/
	public function postData($request)
	{
		$mid	= (int)$request->input('mainmid','0');
		
		//判断是否有新增权限
		if($mid==0){
			$isadd 		= $this->getNei('authory')->isadd($this->agenhinfo->id);
			if($isadd!=1)
				return returnerror(trans('validation.notadd',['name'=>$this->agenhinfo->name]));
		}else{
			
		}
		
		//相关文件Id
		$xiangfileid= array();
		
		$scan 		= array();
		$fieldsarr 	= $this->flow->getFieldsArr('lu');
		$this->fieldsarr = $fieldsarr;
		$check 		= $this->getNei('check');
		foreach($fieldsarr as $k=>$rs){
			$fid = $rs->fields;
			$flx = $rs->fieldstype;
			if($flx=='subtable'){
				
				continue;
			}
			$attr = $rs->attr;
			if($rs->iszb==0){
				$val = trim(nulltoempty($request->input($fid)));
				if($rs->isbt==1 && isempt($val))
					return returnerror(''.$rs->name.'不能为空');
				
				if(in_array($flx,['date','datetime']) && $val=='')$val=null;
				$scan[$fid] = $val;
				
				//多扩展字段
				if(!isempt($rs->data)){
					$fieds 	= explode(',', $rs->data);
					$fieds1 = '';
					if(substr($flx,0,6)=='change'){
						$fieds1 = $fieds[0];
					}
					if(substr($flx,0,10)=='selectdata'){
						$fieds1	= arrvalue($fieds, 1);
					}
					if(!isempt($fieds1)){
						$val 	= nulltoempty($request->input($fieds1));
						$scan[$fieds1] = $val;
					}
				}
				
				
				if($val){
					if(contain($attr,'email')){
						if(!$check->isemail($val))
							return returnerror(''.$rs->name.'必须是邮箱的格式');
					}
					if(contain($attr,'cnmobile')){
						if(!$check->iscnmobile($val))
							return returnerror(''.$rs->name.'必须是中国大陆手机号格式');
					}
					if(contain($attr,'noincn')){
						if($check->isincn($val))
							return returnerror(''.$rs->name.'不能包含中文');
					}
				}
				if($flx=='uploadfile'){
					if(!isempt($val))$xiangfileid[] = $val;
				}
			}
			
		}
		$scan['xiangfileid']	= join(',', $xiangfileid);
		
		$scan['isturn']	= (int)$request->input('isturn','0'); //是否提交
		
		//子表的数据
		$tabless	 	= $this->flow->agentinfo->tables;
		$subdata 		= array();
		if(!isempt($tabless)){
			$tablessa = explode(',', $tabless);
			foreach($tablessa as $zbx=>$tablessas){
				$subdata[$zbx] = $this->getsubtabledata($zbx+1, $request);
			}
		}
		
		//子表保存前判断
		$this->flow->subtabledata	= $subdata;
		if($subdata)foreach($subdata as $zb1=>$sdata){
			$submsg 	= $this->flow->flowsavebeforesubdata($zb1+1, $sdata, $scan);
			if($submsg){
				if(is_string($submsg))return returnerror($submsg);
				if(is_array($submsg)){
					$msg1 = arrvalue($submsg,'msg');
					if(!isempt($msg1))return returnerror($msg1);
					if(isset($submsg['subdata']) && is_array($submsg['subdata'])){
						$subdata[$zb1] = $submsg['subdata'];
					}
					//返回主表保存的
					if(isset($submsg['data']) && is_array($submsg['data'])){
						foreach($submsg['data'] as $k1=>$v1)$scan[$k1] = $v1;
					}
				}
			}
		}
		
		
		//保存数据库
		$cbarr 	= $this->flow->saveData($mid, $scan);
		if(!$cbarr['success'])return returnerror($cbarr['msg']); 
		$mid 	= $cbarr['data'];
		$mobj 	= $this->flow->rs;
		
		//保存多行子表
		if($subdata)foreach($subdata as $zbx=>$sdata){
			$tablessas = $tablessa[$zbx];
			$msg = $this->savesubtable($tablessas, $mobj, $zbx+1, $sdata);
		}
		
		return returnsuccess($mid);
	}
	
	/**
	*	获取多行子表数据
	*/
	public function getsubtabledata($xu, $request)
	{
		$arr 	= array();
		$oi 	= (int)$request->input('sub_totals'.$xu.'');
		if($oi<=0)return $arr;
		$farr		= array();
		foreach($this->fieldsarr as $k1=>$rs1){
			$flx	= $rs1->fieldstype;
			if($rs1->iszb==$xu){
				$farr[] = $rs1;
				
				//多扩展字段
				if(!isempt($rs1->data)){
					$fieds 	= explode(',', $rs1->data);
					$fieds1 = '';
					if(substr($flx,0,6)=='change'){
						$fieds1 = $fieds[0];
					}
					if(substr($flx,0,10)=='selectdata'){
						$fieds1	= arrvalue($fieds, 1);
					}
					if(!isempt($fieds1)){
						$rso = clone $rs1;
						$rso->fields = $fieds1;
						$farr[] = $rso;
					}
				}
			}
		}
		$sort 		= 0;
		for($i=0; $i<$oi; $i++){
			$sidna 	= 'sid'.$xu.'_'.$i.'';
			if(!$request->has($sidna))continue;
			$sid  	= (int)$request->input($sidna);
			$bos  	= true;
			$uaarr	= array();
			$uaarr['id'] = $sid;
			foreach($farr as $k=>$rs){
				$fid		= $rs->fields;
				$fieldstype	= $rs->fieldstype;
				if(substr($fid,0,5)=='temp_' || substr($fid,0,5)=='base_' || $fieldstype=='subtable')continue;
				
				$na 	= ''.$fid.''.$xu.'_'.$i.'';
				$val	= nulltoempty($request->input($na));
				if($rs->isbt==1 && isempt($val))$bos=false; //必填的没有写
				
				if($val=='' && in_array($fieldstype, ['date','datetime']))$val = null; //日期不能为空的
	
				$uaarr[$fid] = $val;
			}
			
			if(!$bos)continue;
			$uaarr['sort'] 	= $sort;
			$uaarr['sslx'] 	= $xu;
			$sort++;
			$arr[] = $uaarr;
		}
		return $arr;
	}
	
	//多行子表的保存
	private function savesubtable($tables, $mobj, $xu, $data)
	{
		$mid 		= $mobj->id;
		
		//$data 		= $this->getsubtabledata($xu, $request);
		$idss		= [0];
		$allfields 	= \Schema::getColumnListing($tables);
		$oarray 	= array();
		if(in_array('optdt', $allfields))$oarray['optdt'] 		= nowdt();
		if(in_array('optid', $allfields))$oarray['optid'] 		= $this->useaid;
		if(in_array('optname', $allfields))$oarray['optname'] 	= $this->useainfo->name;
		if(in_array('status', $allfields))$oarray['status']		= $mobj->status;
		if(in_array('aid', $allfields))$oarray['aid']			= $mobj->aid;
		
		if($data)foreach($data as $k=>$uaarr){
			$sid 			= $uaarr['id'];
			$uaarr['mid'] 	= $mid;
			$uaarr['cid'] 	= $this->companyid;
			foreach($oarray as $k1=>$v1)$uaarr[$k1]=$v1;
			$obj = $this->flow->getModels($tables);
			if($sid>0){
				$obj = $obj->find($sid);
			}
			
			unset($uaarr['id']);
			foreach($uaarr as $k1=>$v1)$obj->$k1 = $v1;
			$obj->save();
			$sid 	= $obj->id;
			$idss[] = $sid;
		}
		$this->flow->getModels($tables)
				->where(['mid'=>$mid,'sslx'=>$xu])
				->whereNotin('id', $idss)->delete();
	}
	
	
	/**
	*	数据源读取
	*/
	public function selectdata($request)
	{
		$fid 		= (int)$request->get('fieldsid','0');
		$mid 		= (int)$request->get('mid','0');
		$fieldsarr 	= AgentModel::find($this->flow->agentid)
					->getFields()->where('id', $fid)
					->orderBy('sort')->get();
		$fields 	= $fieldsarr[0]->fields;
		$this->flow->initdata($mid);
		$farr		= $this->flow->inputfieldsarr($fieldsarr, $this->flow->rs, 1);
		$fieldsarr	= $farr['fieldsarr'];
		$store		= $farr['store'];
		
		$data 		= arrvalue($store, $fields, array());
		
		return returnsuccess($data);
	}
}