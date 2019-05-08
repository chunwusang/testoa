<?php
/**
*	应用
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;


class BaseModel
{
	
	/**
	*	获取单位下应用
	*/
	public static function getAgenh($cid, $aid=0)
	{
		$data 	= AgenhModel::select()
					->where('cid', $cid)
					->where('status', 1)
					->orderBy('sort')->get();		
		$barr		= array();
		foreach($data as $k=>$rs){
			$rso 			= new \StdClass();
			$rso->id 		= $rs->id;
			$rso->atype 	= $rs->atype;
			$rso->face 		= $rs->agenhface;
			$rso->agenhurlm 	= $rs->agenhurlm;
			$rso->agenhurlpc 	= $rs->agenhurlpc;
			$rso->name 			= $rs->name;
			$rso->num 			= $rs->num;
			$rso->pnum 			= $rs->pnum;
			$rso->agentid		= $rs->agentid;
			$rso->islu			= $rs->sysAgent->islu;
			$rso->yylx			= $rs->sysAgent->yylx; //应用类型0,1,2
			$barr[$k]			= $rso;
		}
		return $barr;
	}
	
	/**
	*	应用信息
	*/
	public static function agenhInfo($cid, $num, $pnum='')
	{
		if($pnum!='')$num .= '-'.$pnum.'';
		$data 	= AgenhModel::select()
					->where('cid', $cid)
					->where('num', $num)
					->first();		
		return $data;
	}
	
	/**
	*	获取单位信息
	*/
	public static function getCompany($num)
	{
		if(is_numeric($num)){
			$data = CompanyModel::find($num);
		}else{
			$data = CompanyModel::select()->where('num',$num)->first();
		}
		return $data;
	}
	
	/**
	*	获取单位下用户信息
	*/
	public static function getUsera($cid, $uid)
	{
		$data = UseraModel::select()->where('cid',$cid)->where('uid',$uid)->first();
		return $data;
	}
}
