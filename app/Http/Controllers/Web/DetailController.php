<?php
/**
*	单据详情展示
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Web;

use App\Model\Base\BaseModel;
use Illuminate\Http\Request;
use Rock;

class DetailController extends WebController
{
	
	protected $authform	= 'we';
	
	/**
	*	单据详情PC端的
	*/
    public function index($cnum, $num, $mid, Request $request)
    {
    	// $bcd = 123123;
    	// return $bcd;
		$this->getAgenh($cnum, $num);
		if($this->useaid==0)abort(404, 'not found usera');
		
		$flow 		= Rock::getFlow($num, $this->useainfo);
		$data 		= $flow->getDatail($mid);
		
		$tplpath 	= 'web/detail/'.$num.'';
		if(!file_exists(base_path('resources/views/'.$tplpath.'.blade.php')))
			$tplpath 	= 'web/detail/base';
		
		//相关js
		$jspath		= 'res/agent/'.$num.'_detail.js';
		if(!file_exists(public_path($jspath)))$jspath = '';
		
		$tplpaths 	= 'web/detail/'.$num.'_detail';
		if(!file_exists(base_path('resources/views/'.$tplpaths.'.blade.php')))
			$tplpaths 	= '';
		
		$flowinfo	= $data['flowinfo'];
		$barr	= [
			'mid' 		=> $mid,
			'jspath'	=> $jspath,
			'tplpath'	=> $tplpath,
			'tplpaths'	=> $tplpaths,
			'ischeck'	=> $flowinfo['ischeck'],
			'isflow'	=> $flowinfo['isflow'],
		];
		foreach($data as $k=>$v)$barr[$k] =$v;
		
		return $this->getShowView('detail', $cnum, $num, $request, $barr);
    }
	
}