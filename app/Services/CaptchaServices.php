<?php
/**
*	技术验证码
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Services;


use Session;

class CaptchaServices
{
	
	private $keyvalue = 'captchaval';

	
	public function __construct($config=null)
	{
		
    }
	
	/**
	*	验证验证码
	*/
	public function check($val)
	{
		if(isempt($val))return false;
		if(!Session::has($this->keyvalue)){
			return false;
		}
		$val  = strtolower($val);
		$gvel = Session::get($this->keyvalue);
		if(isempt($gvel))return false;
		Session::remove($this->keyvalue);
		return $gvel==md5('abc'.$val);
	}
	
	/**
	*	生成验证码
	*/
	public function create()
	{
		header("Content-type:image/gif");
		$a		= rand(0,9);
		$b		= rand(0,9);
		$h  	= 30;
		$code 	= 'abc'.($a+$b).'';
		$w 		= 70;
		$im		= imagecreatetruecolor($w,$h);

		$bg		= imagecolorallocate($im,255,255,255);
		imagefill($im,0,0,$bg);	//添加背景颜色

		$black	= imagecolorallocate($im,0,0,0);

		for($i=0;$i<2;$i++){//画线条
			$at1=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imageline($im,0,rand(0,$h),$w,rand(0,$h),$at1);
		}

		for($i=0;$i<200;$i++){//画点
			$at1=imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			imagesetpixel($im,rand(0,$w),rand(0,$h),$at1);
		}

		imagestring($im,5,rand(0,30),rand(0,$h-15),''.$a.'+'.$b.'=?',$black);
		
		imagegif($im);
		imagedestroy($im);
		Session::put($this->keyvalue, md5($code));
	}
}
