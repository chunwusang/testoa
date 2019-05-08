<?php 
/**
	html相关插件
*/
namespace App\RainRock\Chajian\Base;



class ChajianBase_html extends ChajianBase{
	

	public function htmlremove($str)
	{
		$str = preg_replace("/<[^>]*>/si",'',$str);
		$str = str_replace(array(' ','	',"\n"),array('','',''), $str);
		return $str;
	}
	
	public function substrstr($str, $start, $length=null) {  
		preg_match_all('/./us', $str, $match);  
		$chars = is_null($length)? array_slice($match[0], $start ) : array_slice($match[0], $start, $length);  
		unset($str);
		return implode('', $chars);  
	} 
	
	//判断字符串是否包含html代码
	public function ishtml($val)
	{
		$bo = false;
		if(isempt($val))return $bo;
		$valstr = strtolower($val);
		$sparr 	= explode(',','p,div,span,font,table,b,a');
		foreach($sparr as $sp){
			if(contain($valstr,'<'.$sp.'')){
				$bo=true;
				break;
			}
		}
		return $bo;
	}
	
	/**
	*	读取导入数据库
	*/
	public function importdata($fields,$btfid='', $val='')
	{
		$rows 	= array();
		if($val=='')return $rows;
		$arrs 	= explode("\n", $val);
		$farr 	= explode(',', $fields);
		$fars 	= explode(',', $btfid);
		foreach($arrs as $valss){
			$dars 	= explode('	', $valss);
			$barr 	= array();
			foreach($farr as $k=>$fid){
				$barr[$fid] = isset($dars[$k]) ?  $dars[$k] : '';
				$barr[$fid] = str_replace('[XINHUBR]', "\n", $barr[$fid]);
			}
			$bos 	= true;
			foreach($fars as $fids){
				if(isset($barr[$fids]) && isempt($barr[$fids]))$bos = false;
			}
			if($bos)$rows[] = $barr;
		}
		return $rows;
	}
}                                  