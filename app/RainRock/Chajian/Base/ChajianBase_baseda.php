<?php
/**
*	插件-基础的数据写这里
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_baseda extends ChajianBase
{
	/**
	*	性别
	*/
	public function gender()
	{
		return $this->strtostore('1|男,2|女');
	}
	
	/**
	*	学历
	*/
	public function xueli()
	{
		return $this->strtostore('小学以下,小学,中学,中专,高中,大专,本科,研究生,博士,其他');
	}
	
	public function minzu()
	{
		$str = '汉族、蒙古族、回族、藏族、维吾尔族、苗族、彝族、壮族、布依族、朝鲜族、满族、侗族、瑶族、白族、土家族、哈尼族、哈萨克族、傣族、黎族、僳僳族、佤族、畲族、高山族、拉祜族、水族、东乡族、纳西族、景颇族、柯尔克孜族、土族、达斡尔族、仫佬族、羌族、布朗族、撒拉族、毛南族、仡佬族、锡伯族、阿昌族、普米族、塔吉克族、怒族、乌孜别克族、俄罗斯族、鄂温克族、德昂族、保安族、裕固族、京族、塔塔尔族、独龙族、鄂伦春族、赫哲族、门巴族、珞巴族、基诺族、台湾、香港、澳门、华侨、国外';
		return $this->strtostore(str_replace('、',',', $str));
	}
	
	public function hunyin()
	{
		return $this->strtostore('未婚,已婚');
	}
	
	/**
	*	转为自己数据
	*/
	public function strtostore($str)
	{
		$garr	= $this->strtoarray($str);
		$barr 	= array();
		foreach($garr as $k=>$rs){
			$val 	= $rs[0];
			$barr[] = array(
				'name'  => $rs[1],
				'value' => $val,
			);
		}
		return $barr;
	}
	
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
}