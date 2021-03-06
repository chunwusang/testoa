<?php
/**
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;


class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     * 后台认证验证
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
		parent::__construct();//调用父类的__construct
				// public function __construct()
		  //   {
		  //       $this->now = date('Y-m-d H:i:s');
		  //   }
    }
	
	private function getStyle()
	{
		$style = \Auth::user()->bootstyle;
		if(isempt($style))$style = config('rock.adminstyle');
		return  $style;
	}
	
	//params  参数
	/*
	*
	helpstr是判断
	*/
	public function getShowView($tpl, $params=array())
	{
		$arr = [
			'tpl' 			=> $tpl,//模板,传进来的
			'bootstyle'		=> $this->getStyle(),//调用getStyle方法,样式
			'helpstr'		=> c('help')->helpstr($tpl, arrvalue($params,'kzq'), arrvalue($params,'lang'))//调用自己封装的c方法去引入helpstr插件来获得返回的字符串
			//arrvalue是获取数组上对应值
		];
		//下面循环的params是传进来的
		foreach($params as $k=>$v)$arr[$k]=$v;//循环将传进来的params塞进数组$arr  下标为$k
		return view($tpl, $arr);//返回视图
	}
}
