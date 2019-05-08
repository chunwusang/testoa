<?php
/**
*	应用菜单
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;

class AgentmenuModel extends Model
{
	protected $table 	= 'agentmenu';
	
	public $timestamps 	= false;
	
	/**
	*	元素字段类型
	*/
	public function typeArr($dev)
	{
		$typear = ['auto','url','add'];
		$arr 	= array();
		foreach($typear as $lx){
			$sel = '';
			if($dev==$lx)$sel='selected';
			$arr[] = ['value'=>$lx,'name'=>trans('table/agentmenu.type_'.$lx.'').'('.$lx.')','selected'=>$sel];
		}
		return $arr;
	}
	
}
