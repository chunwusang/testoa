<?php
/**
*	插件-帮助
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_help extends ChajianBase
{
	/**
	*	显示帮助
	*/
	public function show($num, $txt='')
	{
		if($txt=='')$txt = trans('base.help');
		return '<a href="'.config('rock.urly').'/view_'.$num.'.html" target="_blank">'.$txt.'</a>';
	}
	
	public function helpstr($tpl,$kzq, $yuy='',$cj='',$jk='')
	{
		$url = config('app.url');//识别当前URL
		if(!contain($url,':93'))return '';//contain是判断变量是否包含在另一变量里面  helpers.php
		
		$str	= ' <a href="javascript:;" onclick="js.openkaifa(0,\''.$tpl.'\')">模版页'.$tpl.'</a>';
		if($kzq!='')
			$str.='，<a href="javascript:;"  onclick="js.openkaifa(1,\''.$kzq.'\')">控制器'.$kzq.'Controller.php</a>';
		if($yuy!='')
			$str.='，<a href="javascript:;"  onclick="js.openkaifa(2,\''.$yuy.'\')">语言包'.$yuy.'</a>';
		if($cj!='')
			$str.='，<a href="javascript:;"  onclick="js.openkaifa(3,\''.$cj.'\')">插件控制器'.$cj.'</a>';
		if($jk!='')
			$str.='，<a href="javascript:;"  onclick="js.openkaifa(3,\''.$jk.'\')">接口'.$jk.'</a>';
		return $str;
	}
}