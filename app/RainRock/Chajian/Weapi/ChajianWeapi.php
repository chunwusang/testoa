<?php
/**
*	插件-手机/app等api
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Weapi;

use App\RainRock\Chajian\Chajian;
use App\Model\Base\UseraModel;

class ChajianWeapi extends Chajian
{

	public $allcompany;
	
	/**
	*	初始化读取当前操作哪个单位下的
	*/
	public function initUseainfo($cnum, $userinfo, $joincompany)
	{
		$useainfo		= $allcompany	= array();
		if($joincompany){
			foreach($joincompany as $k=>$rs){
				//激活的
				if($rs->status==1){
					$allcompany[] 	= $rs;	
					if(!$useainfo)$useainfo = $rs;
					if(!isempt($cnum)){
						if($cnum==$rs->company->num)$useainfo = $rs;
					}else{
						if($userinfo->devcid==$rs->cid)$useainfo = $rs;
					}
				}
			}
			if($useainfo){
				$nowcompany	= $useainfo->company;	
				$this->initUsera($useainfo);
			}
		}
		
		$this->userinfo		= $userinfo;
		$this->userid		= $userinfo->id;
		$this->allcompany	= $allcompany;
	}
}