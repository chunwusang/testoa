<?php
/**
*	用户设置
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
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


class CogController extends UsersController
{
  

    public function showCogForm(Request $request)
    {
		
		$mybot 		= \Auth::user()->bootstyle;
		$stylearr	= c('bootstyle')->getStylearr();
		foreach($stylearr as &$rs){
			$rs['checked'] = ($rs['value']==$mybot)?'checked':'';
		}
		$type = $request->get('type');
		// $url = $request->getRequestUri();
		$first = $request->segment(1);
		$second = $request->segment(3);
		// $act = $request->get('act');
		$tpl = $first.'/'.$second;
		$arr = [
			'stylearr' 	=> $stylearr,
			'data'		=> \Auth::user()
		];

		$tpl = $this->common($first,$second);
		// var_dump($tpl);die;
		return $this->getShowView($tpl,$arr,$type);
    }
}
