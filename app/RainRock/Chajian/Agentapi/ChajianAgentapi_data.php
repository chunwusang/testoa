<?php
/**
*	获取应用数据
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Agentapi;



class ChajianAgentapi_data extends ChajianAgentapi
{
	
	/**
	*	应用获取列表数据 
	*/
	public function getData($request)
	{
		$limit 		= (int)$request->get('limit','0');
		$atype 		= $request->get('atype');
		if($limit<1)$limit = 10;
		
		$flow 		= $this->flow;
		$flow->limit= $limit;
		$page 		= 1;

		//排序
		if($request->has('sort') && $request->has('order')){
			$flow->defaultorder = ''.$request->get('sort').','.$request->get('order').'';
		}
			
		if($request->has('page'))$page	= (int)$request->get('page');
		if($page<1)$page = 1;
		$fieldsarr			= $flow->getFieldsArr('mlist');
		$data 				= $flow->getYingData($atype, $page, 1);
		$data['fieldsarr']	= $fieldsarr;
		
		//数据格式化
		$rows 	 = $data['rows'];
		foreach($rows as $k=>$rs){
			foreach($fieldsarr as $k1=>$rs1){
				$fid  = $rs1->fields;
				if(isset($rs->$fid) && $rs1->fieldstype=='uploadimg'){
					$rows[$k]->$fid = $flow->getimgyuan($rs->$fid);
				}
			}
		}

		return returnsuccess($data);
	}
	
	
	
	/**
	*	获取操作菜单
	*/
	public function getoptmenu($request)
	{
		$mid 	= (int)$request->get('mid', '0');
		$barr 	= $this->flow->getOptmenu($mid);
		return $barr;
	}
	
	/**
	*	删除单据
	*/
	public function postdelbill($request)
	{
		$mid 	= (int)$request->input('mid', '0');
		$barr 	= $this->flow->delbill($mid);
		return $barr;
	}
	
	/**
	*	单据操作菜单执行
	*/
	public function postoptmenu($request)
	{
		$mid 	= (int)$request->input('mid', '0');
		$optmid 	= (int)$request->input('optmid',0);
		$sm 		= nulltoempty($request->input('sm'));
		$barr 		= $this->flow->postOptmenu($mid, $optmid, $sm);
		return $barr;
	}
	
	/**
	*	获取字段
	*/
	public function daofields()
	{
		$farr = $this->flow->getFieldsArr('daochu');
		
		return returnsuccess($farr);
	}
}