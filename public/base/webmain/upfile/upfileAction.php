<?php
/**
*	上传文件服务
*	主页：http://www.rockoa.com/
*	软件：信呼平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

class upfileClassAction extends Action
{
	public $accessbool	= false;
	
	public function initAction()
	{
		$this->display	= false;
		$this->upobj	= c('upfile');
		if(getconfig('alloworigin')){
			header('Access-Control-Allow-Origin:'.getconfig('alloworigin').'');
			header('Access-Control-Allow-Headers: X-Requested-With, Content-Type');
		}
	}
	
	/**
	*	上传文件，成功返回 array('filepath'=>'文件路径','thumbpath'=>'缩略图地址','filesize'=>'文件大小','filesizecn'=>'文件大小中文','pdfpath'=>'转为pdf后路径','filename'=>'文件名','filetype'=>'文件mime类型','fileext'=>'文件扩展名','filenum'=>'文件唯一标识编号');
	*/
	public function defaultAction($uplx=null)
	{
		$_msg 	= $this->isaccess();
		if($_msg)return returnerror($_msg);
		
		if(!$_FILES)return returnerror('sorry1');
		$maxsize	= (int)$this->get('maxsize', 0);
		if($maxsize==0)$maxsize = $this->upobj->getmaxzhao();
		
		$uptype		= $this->get('uptype', '*'); //上传类型
		$thumbnail	= $this->get('thumbnail');	//缩略图规则
		if($uplx!==null)$uptype = $uplx;
		$this->upobj->initupfile($uptype, ''.UPDIR.'|'.date('Y-m').'', $maxsize);
		$uparr		= $this->upobj->up('file');
		
		if(!is_array($uparr))return returnerror($uparr); //上传错误
		
		$barr 	= c('down')->uploadback($uparr, $thumbnail);
		
		return returnsuccess($barr);
	}
	
	//验证权限
	private function isaccess()
	{
		$uptoken	= $this->get('uptoken');
		if(isempt($uptoken))return 'uptoken empty';
		if($uptoken != $this->createtoken())return 'uptoken error';
		return '';
	}
	
	/**
	*	判断文件是否已经上传过了，上传过了直接返回原来记录
	*/
	public function exiesAction()
	{
		$_msg 	= $this->isaccess();
		if($_msg)return returnerror($_msg);
		$fileext	= $this->rock->post('fileext');
		$filesize	= $this->rock->post('filesize');
		$filename	= $this->rock->post('filename');
		
		$ors 		= m('fileda')->getone("`fileext`='$fileext' and `filesize`='$filesize'",'*','id desc');
		if($ors){
			$filepath 	= $ors['filepath'];
			if(substr($filepath,0,4)!='http' && !file_exists($filepath))return returnsuccess('ok');
			
			$barr		= $ors;
			if($filename!=$ors['filename']){
				$barr['filename']	= $filename;
				unset($barr['id']);
				$oid	= $ors['oid'];
				if($oid=='0')$oid = $ors['id'];
				$barr['filenum'] 	= $this->db->ranknum('[Q]basefileda','filenum');
				$barr['adddt']		= $this->rock->now;
				$barr['ip']			= $this->rock->ip;
				$barr['web']		= $this->rock->web;
				$barr['oid']		= $oid;
				$barr['id']			= m('fileda')->insert($barr);
			}else{
				$barr['adddt']		= $this->rock->now;
			}
			
			if($this->upobj->isimg($barr['fileext'])){
				$barr['imgpath']	= '{baseurl}'.$barr['thumbpath'];//缩略图片地址
				$barr['allpath']	= '{baseurl}'.$barr['filepath'];//图片地址
				
				$barr['viewpath']	= getconfig('baseurl', URL).$barr['filepath'];
				$barr['viewpats']	= getconfig('baseurl', URL).$barr['thumbpath'];
			}
			return returnsuccess($barr);
		}
		
		return returnsuccess('ok');
	}
	
	/**
	*	初始化上传并且返回签名
	*/
	public function infoAction()
	{
		$maxsize 	= $this->upobj->getmaxupsize();
		$data = array(
			'maxsize' 	=> $this->upobj->getmaxzhao(), //M兆
			'maxsizecn' => $this->upobj->formatsize($maxsize),
			'uptoken' 	=> $this->createtoken()
		);
		
		return returnsuccess($data);
	}
	
	/**
	*	上传截图
	*/
	public function upcontAction()
	{
		$_msg 	= $this->isaccess();
		if($_msg)return returnerror($_msg);
		
		$cont 	= $this->rock->jm->base64decode($this->post('content'));
		$barr 	= c('down')->createimage($cont, 'png', '截图', '');
		if(!is_array($barr))return returnerror($barr);

		return returnsuccess($barr);
	}
}