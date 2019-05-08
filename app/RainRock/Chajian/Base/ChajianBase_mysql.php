<?php
/**
*	插件-mysql数据库操作
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;

use DB;

class ChajianBase_mysql extends ChajianBase
{
	
	/**
	*	获取系统上所有的表
	*/
	public function getAllTable($tabs='')
	{
		$rows 	= DB::select('show tables');
		$tables	= array();
		foreach($rows as $k=>$rs){
			foreach($rs as $k1=>$v1)$tables[] = $v1;
		}
		if($tabs!=''){
			$tabs = DB::getTablePrefix().$tabs;
			return in_array($tabs, $tables);
		}
		return $tables;
	}
	
	/**
	*	流水编号
	*	$num 规则
	*/		
	public function sericnum($num, $table, $fields='sericnum', $ws=4, $whe='')
	{
		$dts 	= explode('-', date('Y-m-d'));
		$ymd 	= $dts[0].$dts[1].$dts[2];
		$ym 	= $dts[0].$dts[1];
		$num	= str_replace('Ymd', $ymd, $num);
		$num	= str_replace('Ym', $ym, $num);
		$num	= str_replace('Year', $dts[0], $num);
		$num	= str_replace('Day', $dts[2], $num);
		$num	= str_replace('Month', $dts[1], $num);
		$where 	= "`$fields` like '".$num."%' $whe";
		//$max	= (int)$this->getmou($table, "max(cast(replace(`$fields`,'$num','') as decimal(10)))", $where);
		$max 	= 0;
		$maxrs 	= DB::table($table)->select(DB::raw("max(cast(replace(`$fields`,'$num','') as decimal(10))) as `stotal`"))->where('cid', $this->companyid)->whereRaw($where)->first();
		if($maxrs)$max = $maxrs->stotal;
		$max++;
		$wsnum	= ''.$max.'';
		$len 	= strlen($wsnum);
		$oix	= $ws - $len;
		for($i=1;$i<=$oix;$i++)$wsnum='0'.$wsnum;
		$num   .= $wsnum;
		return $num;
	}
	
	/**
	*	字符串转数组更新
	*/
	public function strtoarr($str)
	{
		$str = str_replace(['and','`'], [',',''], $str);
		$star= explode(',', $str);
		$uarr= array();
		foreach($star as $stra){
			$straa = explode('=', $stra);
			$val   = arrvalue($straa,1);
			if($val=='null')$val = null;
			$uarr[$straa[0]] = $val;
		}
		return $uarr;
	}
	
	
	
	
	
	public function updatefabric($cont)
	{
		$bos 	= $this->updatefabricfile($cont);
		if($bos)return 'err:'.$bos;
		return 'ok';
	}
	
	public function updatefabricfile($cont='')
	{
		if($cont=='')return false;
		$data = json_decode($cont, true);
		$qianz= DB::getTablePrefix();
		foreach($data as $tabe=>$da){
			$table 	= str_replace('zwoa_', $qianz, $tabe);
			$fields = $da['fields'];
			$nowfiel= $this->getfieldsa($table);   
			$str 	= '';
			$sql	= '';
			//不存在就创建
			if(!$nowfiel){
				$sql 	= $da['createsql'];
				$sql 	= str_replace('`zwoa_','`'.$qianz.'', $sql);
			}else{
				foreach($fields as $k=>$frs){
					$fname = $frs['name'];
					if($fname=='id')continue;
					$nstr  = $this->getfielstr($frs);
					if(!isset($nowfiel[$fname])){
						$str.=',add '.$nstr.'';
					}else{
						$ofrs = $nowfiel[$fname]; //系统上字段类型
						$ostr = $this->getfielstr($ofrs);
						$lxarr= array('text','mediumtext','bigint');
						
						//如果自己字段长度大于官网就不更新
						if($frs['type']==$ofrs['type'] && !isempt($ofrs['lens']) && $ofrs['lens']>$frs['lens']){
							
						}else if($nstr != $ostr && !in_array($ofrs['type'], $lxarr)){
							$str.=',MODIFY '.$nstr.'';
						}
					}
				}
				if($str!=''){
					$str = substr($str, 1);
					$sql = "alter table `$table` $str";
				}
			}
			if($sql!=''){
				try {
					DB::select($sql);
					addlogs($sql, 'upgmysql_'.$table.'');
				} catch(\Illuminate\Database\QueryException $ex) {
					return $ex->getMessage();
				}
			}
		}
		return '';
	}
	private function getfieldsa($table)
	{
		$nowfiel= $this->gettablefields($table);
		$a 		= array();
		foreach($nowfiel as $k=>$rs){
			$a[$rs['name']] = $rs;
		}
		return $a;
	}
	private function getfielstr($rs)
	{
		$str 	= '`'.$rs['name'].'` '.$rs['types'].'';
		$dev 	= $rs['dev'];
		$isnull = $rs['isnull'];
		if($isnull=='NO')$str.=' NOT NULL';
		if(is_null($dev)){
			if($isnull != 'NO')$str.=' DEFAULT NULL';
		}else{
			$str.=" DEFAULT '$dev'";
		}
		if(!isempt($rs['explain']))$str.=" COMMENT '".$rs['explain']."'";
		return $str;
	}
	
	/**
	*	获取字段信息
	*/
	public function gettablefields($table, $base='')
	{
		if($base=='')$base = DB::getDatabaseName();
		$sql	= "select COLUMN_NAME as `name`,DATA_TYPE as `type`,COLUMN_COMMENT as `explain`,COLUMN_TYPE as `types`,`COLUMN_DEFAULT` as dev,`IS_NULLABLE` as isnull,`CHARACTER_MAXIMUM_LENGTH` as lens from information_schema.COLUMNS where `TABLE_NAME`='$table' and `TABLE_SCHEMA` ='$base' order by `ORDINAL_POSITION`";
		$rows =  DB::select($sql);
		$arr  = array();
		foreach($rows as $k=>$rs){
			$arr[] = array(
				'name' => $rs->name,
				'type' => $rs->type,
				'explain' => $rs->explain,
				'types' => $rs->types,
				'dev' => $rs->dev,
				'isnull' => $rs->isnull,
				'lens' => $rs->lens,
			);
		}
		return $arr;
	}
}