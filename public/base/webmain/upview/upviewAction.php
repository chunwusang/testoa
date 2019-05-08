<?php
/**
*	上传文件下载/预览
*	主页：http://www.rockoa.com/
*	软件：信呼平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

class upviewClassAction extends Action
{
	
	public $accessbool	= false;
	
	private function getfrs()
	{
		$showkey	= $this->get('showkey');
		if(isempt($showkey))return '访问有误';
		$showval 	= c('cache')->get($showkey);
		if(isempt($showval))return '链接已失效了，请重新打开';
		$num 		= $this->jm->uncrypt($showval);
		if(!$num)return 'filenum isempty';
		$frs 	= m('fileda')->getone("`filenum`='$num'");
		if(!$frs)return 'frs not found';

		$this->smartydata['showkey'] = $showkey;
		return $frs;
	}
	
	/**
	*	预览文件
	*/
	public function defaultAction()
	{
		$frs 		 	= $this->getfrs();
		if(!is_array($frs))return $frs;
		
		$this->display 	= true;
		$type 		= $frs['fileext'];
		$filepath 	= $frs['filepath'];
		$types 		= ','.$type.',';
		//可读取文件预览的扩展名
		$vest 	= ',txt,log,html,htm,js,php,php3,cs,sql,java,json,css,asp,aspx,shtml,c,vbs,jsp,xml,bat,sh,';
		$docx	= ',doc,docx,xls,xlsx,ppt,pptx,';
		$this->displayfile = ''.P.'/upview/pdfview.html';
		if(contain($docx, $types)){
			$filepath 	= $frs['pdfpath'];
			if(isempt($filepath)){
				$this->topdfshow($frs);
				return;
			}
			if(!file_exists($filepath)){
				$this->topdfshow($frs);
				return;
			}else{
				$exta = substr($filepath, -4);
				if($exta=='html')$this->rock->location($filepath);
			}
		}else if(contain($vest, $types)){
			$content  = file_get_contents($filepath);
			if(substr($filepath,-6)=='uptemp')$content = base64_decode($content);
			$bm =  c('check')->getencode($content);
			if(!contain($bm, 'utf')){
				$content = @iconv($bm,'utf-8', $content);
			}
			$this->smartydata['content'] = $content;
			$this->displayfile = ''.P.'/upview/fileopen.html';
		}else if($type=='pdf'){
			
		}else{
			$this->topdfshow($frs);
			return;
		}

		$this->smartydata['filepath'] = $filepath;
		
	}
	
	private function topdfshow($frs)
	{
		$this->displayfile = ''.P.'/upview/filetopdf.html';
		$this->smartydata['frs'] = $frs;
		$this->smartydata['ismobile'] = $this->rock->ismobile()?'1':'0';
	}
	
	
	/**
	*	直接下载
	*/
	public function downAction()
	{
		$rs  = $this->getfrs();
		if(!is_array($rs))return $rs;
		
		m('fileda')->update("`downci`=`downci`+1", $rs['id']);
		
		$filepath	= $rs['filepath'];
		$filename	= $rs['filename'];
		$filesize 	= $rs['filesize'];
		$fileext 	= $rs['fileext'];
		$filenum 	= $rs['filenum'];
		if($this->rock->contain($filepath,'http')){
			header('location:'.$filepath.'');
		}else{
			if(!file_exists($filepath))exit('404 Not find files');
				
			c('file')->fileheader($filename, $fileext);	

			if(substr($filepath,-4)=='temp'){
				$content	= file_get_contents($filepath);
				echo base64_decode($content);
			}else{
				if($rs['filesize'] > 3*1024*1024){
					header('location:'.$filepath.'');
				}else{
					echo file_get_contents($filepath);
				}
			}	
		}
	}
	
	/**
	*	转pdf请求
	*/
	public function zhuangAction()
	{
		$rs  = $this->getfrs();
		if(!is_array($rs))return returnerror($rs);
		
		$id = (int)$this->get('id','0');
		return c('xinhuapi')->officesend($id);
	}
	
	/**
	*	获取状态
	*/
	public function officestatusAction()
	{
		$rs  = $this->getfrs();
		if(!is_array($rs))return returnerror($rs);
		
		$id = (int)$this->get('id','0');
		return c('xinhuapi')->officestatus($id);
	}
	
	
	/**
	*	下载
	*/
	public function officedownAction()
	{
		$rs  = $this->getfrs();
		if(!is_array($rs))return returnerror($rs);
		
		$id = (int)$this->get('id','0');
		return c('xinhuapi')->officedown($id);
	}
}