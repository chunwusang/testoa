<?php
/**
*	单位应用菜单
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Model\Base;

use Illuminate\Database\Eloquent\Model;

class AgenhmenuModel extends Model
{
	protected $table 	= 'agenhmenu';
	
	public $timestamps 	= false;
	
	
	public function getRecenameAttribute($val)
    {
		if(isempt($val)){
			$val = trans('table/agentmenu.recename_empty');
		}
		return $val;
    }
}
