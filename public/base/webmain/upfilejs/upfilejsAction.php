<?php
/**
*	上传文件js
*	主页：http://www.rockoa.com/
*	软件：信呼平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

class upfilejsClassAction extends Action
{
	public $accessbool	= false;
	
	public function defaultAction()
	{
		$this->display	= true;
		$gtype	= $this->get('gtype');
		$upobj	= c('upfile');
		$uptoken= $this->createtoken();
		if($gtype=='url')$uptoken = '';
		$this->smartydata['maxsize'] = $upobj->getmaxzhao();
		$this->smartydata['uptoken'] = $uptoken;
		$this->smartydata['baseurl'] = getconfig('baseurl', URL);
	}
}