<?php
/**
*	信呼官网提供短信服务api
*	官网：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

class smsxinhuChajian extends Chajian{
	

	protected function initChajian()
	{
		if(getconfig('systype')=='dev'){
			$this->updatekeys  = 'aHR0cDovLzEyNy4wLjAuMS9hcHAvcm9ja2FwaS8:';
		}else{
			$this->updatekeys  = 'aHR0cDovL2FwaS5yb2Nrb2EuY29tLw::';
		}
		$this->updatekey		= $this->rock->jm->base64decode($this->updatekeys);
		
		//官网key
		$this->xinhukey 		= $this->rock->get('xinhukey');
		if(isempt($this->xinhukey))$this->xinhukey = c('cache')->get('xinhukey');
	}
	
	/**
	*	发送短信
	*/
	public function send($mobile, $qianm, $tplid, $params=array())
	{
		$para['sys_tomobile'] = $mobile;
		$para['sys_tplnum']   = $tplid;
		$para['sys_qiannum']  = $qianm;
		if(isset($params['url'])){
			$para['sys_url']   	  = $this->rock->jm->base64encode($params['url']); //详情的URL
			unset($params['url']);
		}
		foreach($params as $k=>$v)$para['can_'.$k.''] = $v;
		return $this->postdata('sms','send', $para);
	}
	
	
	public function geturlstr($mod, $act, $can=array())
	{
		$url 	= $this->updatekey;
		$url.= '?m='.$mod.'&a='.$act.'';
		$url.= '&xinhukey='.$this->xinhukey.'';
		foreach($can as $k=>$v)$url.='&'.$k.'='.$v.'';
		return $url;
	}
	
	/**
	*	get获取数据
	*/
	public function getdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act, $can);
		$cont 	= c('curl')->getcurl($url);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问到官网API的,'.$cont.'');
		}
		return $data;
	}
	
	/**
	*	post发送数据
	*/
	public function postdata($mod, $act, $can=array())
	{
		$url 	= $this->geturlstr($mod, $act);
		$cont 	= c('curl')->postcurl($url, $can);
		if(!isempt($cont) && contain($cont, 'success')){
			$data  	= json_decode($cont, true);
		}else{
			$data 	= returnerror('无法访问到官网API的,'.$cont.'');
		}
		return $data;
	}
}