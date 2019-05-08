<?php
/**
*	插件-包含判断
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use App\Model\Base\AuthoryModel;
use App\Model\Base\UseraModel;


class ChajianBase_contain extends ChajianBase
{
	
	
	/**
	*	获取条件
	*/
	public function getcontarr($rows, $fid='receid', $konall=false)
	{
		$barr	= array();
		if($rows)foreach($rows as $k=>$rs){
			$receidstr 	= $rs->$fid;
			$bo			= false;
			if($konall && isempt($receidstr))$bo = true;
			if(!$bo)$bo 	= $this->iscontain($receidstr);
			if($bo){
				$barr[] = $rs;
			}
		}
		
		return $barr;
	}
	
	/**
	*	判断当前用户是否在receid里面
	*/
	public function iscontain($receidstr, $usea=false)
	{
		if($receidstr=='')return false;
		if(!$usea)$usea = $this->useainfo;
		
		$receidstr = ','.$receidstr.',';
		
		//用户
		if(contain($receidstr,',u'.$usea->id.',') || contain($receidstr,','.$usea->id.',')){
			return true;	
		}
		
		//部门
		$depts = $usea->deptpath;
		if(!isempt($depts)){
			$depta = explode(',', $depts);
			foreach($depta as $did){
				if(contain($receidstr,',d'.$did.',')){
					return true;
				}
			}
		}
		
		//组
		$groups= $usea->grouppath;
		if(!isempt($groups)){
			$groupa = explode(',', $groups);
			foreach($groupa as $gid){
				if(contain($receidstr,',g'.$gid.',')){
					return true;
				}
			}
		}
		return false;
	}
	
	/**
	*	如d1,u1,g3，返回人员Id等
	*	$glx0 返回用户id聚合如：1,2,3
	*/
	public function getaids($aids, $lx='ud', $glx=0)
	{
		if(isempt($aids))return false;
		if(!contain($aids, 'd') && !contain($aids, 'g')){
			$aids= str_replace('u','', $aids);
			return $aids;
		}
		$aida = $dida = $gida = array();
		$aidar= explode(',',$aids);
		foreach($aidar as $ssid){
			$lx = substr($ssid,0,1);
			$sid= str_replace(['u','d','g'],['','',''], $ssid);
			if($lx=='d'){
				$dida[] = $sid;
			}else if($lx=='g'){
				$gida[] = $sid;
			}else{
				$aida[] = $sid;
			}
		}
		
		$orwh = array();
		foreach($dida as $did)$orwh[] = $this->dbinstr("`deptpath`", ''.$did.'');
		foreach($gida as $gid)$orwh[] = $this->dbinstr("`grouppath`", ''.$gid.'');
		
		if($orwh){
			$arows= UseraModel::where('cid', $this->companyid)
						->whereRaw(sprintf('(%s)', join(' or ', $orwh)))
						->get();
			if($arows)foreach($arows as $k=>$rs){
				$aida[] = $rs->id;
			}				
		}
		return join(',', $aida);
	}
	
	/**
	*	返回包含里人ID数组
	*/
	public function getaida($aids)
	{
		if(isempt($aids))return [0];
		$rootid	= $this->getNei('dept')->getrootid($this->companyid);
		if(contain(','.$aids.',',',d'.$rootid.','))return 'all';//全部
		$aids 	= $this->getaids($aids);
		if(!$aids)$aids = '0';
		return explode(',', $aids);
	}
}