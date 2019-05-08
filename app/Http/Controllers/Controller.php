<?php

namespace App\Http\Controllers;


use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests,  ValidatesRequests;
	
	
	public $now;	//当前时间
	public $limit = 15;//默认分页数
	private $error_message; //错误信息
	
	public function __construct()
    {
        $this->now = date('Y-m-d H:i:s');
    }
	
	
	/**
	*	返回错误信息
	*/
	public function returnerror($msg, $code=422)
	{
		$this->error_message = $msg;
		return response($msg, $code);
	}
	
	public function geterrormessage()
	{
		return $this->error_message;
	}
	
	public function returnerrors($request, $fid, $msg)
	{
		$barr[$fid] = [$msg];
		return $this->buildFailedValidationResponse($request,$barr);
	}
	
	public function returntishi($msg, $code=402)
	{
		return abort($code, $msg);
	}
	
	
	/**
	*	获取分页数组
	*	route 地址路由
	*	total 总数量
	*/
	public function getPager($route, $total=0, $urlparams=array(), $ots=array())
	{
		$page 	= (int)\Request::input('page','1');
		$limit	= (int)\Request::input('limit','0');
		
		$limits	= $limit> 0 ? $limit	: $this->limit;
		
		$maxpage = ceil($total/$limits);

		$url 	 = route($route, $ots).'?page=%d';
		if(\Request::has('limit'))$url.='&limit='.$limit.'';
		foreach($urlparams as $k=>$v)if(!isempt($v))$url.='&'.$k.'='.$v.'';
		$pager 	 = [
			'total'		=> $total,
			'lastpage'  => $page-1,
			'nextpage'  => $page+1,
			'page'  	=> $page,
			'limit'  	=> $limits,
			'maxpage' 	=> $maxpage,
			'url'		=> $url
		];
		
		return $pager;
	}
	
	/**
	*	每页数量
	*/
	public function getLimit()
	{
		//如果没有传入每页显示多少条数据就默认15条
		// var_dump(123);die;
		$this->limit	= (int)\Request::input('limit', $this->limit);
		return $this->limit;
	}

	// public function test(Request $request)
 //    {
 //    	$bb = $request->get('type');
 //    	var_dump($bb);
 //    	if ($bb == 'view') {
 //    		var_dump($bb);die;
 //    	}
 //    	$aa = 'data';
 //    	var_dump($aa);
    	
 //    	return $aa;
 //    	// var_dump($bb);
 //    	// var_dump('口吐芬芳');
 //    	// die;
 //    }
	// public function showview($tpl)
	// {
	// 	// var_dump(appurl());
	// 	$url = \Request::route()->getName();
	// 	// var_dump(parse_url($url));
	// 	// var_dump($url);
	// 	// var_dump(111);die;
	// 	$tpl= $url.'/'.$tpl;
	// 	return view($tpl);
	// }
}
