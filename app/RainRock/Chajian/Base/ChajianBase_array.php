<?php
/**
*	插件-数组转化
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('array');
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_array extends ChajianBase
{
	/**
	*	二维数组排序
	*/
	public function order($arr, $field, $tyee='desc')
	{
		$temp_a = array();
		foreach ($arr as $arrs) {
			$temp_a[] = $arrs[$field];
		}
		$tyee	= strtolower($tyee);
		if($tyee == 'desc'){
			array_multisort($temp_a, SORT_DESC, $arr);
		}else{
			array_multisort($temp_a, SORT_ASC, $arr);
		}
		return $arr;
	}
	
	/**
	*	转换为用数字做的键值
	*/
	public function tonumarray($arr, $otarr='')
	{
		$varr=array();
		if(is_array($otarr))$varr[]=$otarr;
		if(is_array($arr)){
			foreach($arr as $da){
				$key = array_keys($da);
				$zarr=array();
				for($i=0;$i<count($key);$i++)$zarr[$i]=$da[$key[$i]];
				$varr[]=$zarr;
			}			
		}
		return $varr;
	}
	
	/**
		$str  转化为 数组 0|昨天,2|d = array($key, $value);
	*/
	public function strtoarray($str)
	{
		$a	= explode(',', $str);
		$arr= array();
		foreach($a as $a1){
			$a2	= explode('|', $a1);
			$v 	= $a2[0];
			$n 	= $a2[0];
			$c	= '';
			if(isset($a2[1]))$n = $a2[1];
			if(isset($a2[2]))$c = $a2[2];
			$arr[] = array($v, $n, $c);
		}
		return $arr;
	}
	
	/**
		$str  转化为 数组对象 0|昨天,2|d
	*/
	public function strtoobject($str)
	{
		$rowa = $this->strtoarray($str);
		$arr  = array();
		foreach($rowa as $k=>$rs){
			$arr[$rs[0]] = $rs[1];
		}
		return $arr;
	}
	
	/**
		[{}]数组转化为{ke1:ke2}
	*/
	public function arrrytoobject($arr, $lx=0)
	{
		$rows = array();
		foreach($arr as $k=>$da){
			$karr 	= array_keys($da);
			$key	= $da[$karr[0]];
			$nav	= $key;
			if(count($karr)>1){
				$nav = $da[$karr[1]];
			}
			if($lx == 1){
				$rows[$key] = $da;
			}else{
				$rows[$key] = $nav;
			}
		}
		return $rows;
	}
}