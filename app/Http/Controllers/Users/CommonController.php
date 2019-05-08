<?php
/**
*	用户单位下主页
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-04-15
*/

namespace App\Http\Controllers\Users;

use App\Model\Base\UsersModel;
use App\Model\Base\UseraModel;
use App\Model\Base\CompanyModel;
use App\Model\Base\AgentmenuModel;
use Auth;
use App\Model\Base\BaseModel;
use Rock;
use Illuminate\Http\Request;

class CommonController extends UsersController
{

	public function category(Request $request)
	{
		$type = $request->get('type');
		$arr = '这只是个测试';
    	// var_dump($arr);die;
    	return $this->getShowView('view',[
    		'category'		=> $arr,
    	],$type);
	}

	public function common()
	{
		// $type = $request->get('type');
		// // $url = $request->getRequestUri();
		// $first = $request->segment(1);
		// $second = $request->segment(3);
		// var_dump($com);
		// var_dump($url);
		$arr = '这只是个测试';
		$act = $request->get('act');
		// var_dump($type);
		$tpl = $first.'/'.$second;
		// var_dump($tpl);die;
		return $this->getShowView($tpl,[
    		'category'		=> $arr,
    	],$type);
	}
}
 ?>