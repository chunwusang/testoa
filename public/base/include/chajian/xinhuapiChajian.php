<?php 
/**
*	连接官网API
*/

class xinhuapiChajian extends Chajian{
		
	private $xinhuobj;	

	protected function initChajian()
	{
		$this->xinhuobj = c('smsxinhu');
	}
	
	
	
	/**
	*	work文件在线预览通过官网插件
	*/
	public function officesend($fileid, $lx=0)
	{
		$yulx 	= ',doc,docx,xls,xlsx,ppt,pptx,';
		$frs 	= m('fileda')->getone($fileid);
		if(!$frs)return returnerror('文件不存在1');
		$filepath = $frs['filepath'];
		$fileext  = $frs['fileext'];
		$filesize = floatval($frs['filesize']);
		if(!contain($yulx,','.$fileext.','))return returnerror('不是文档类型');
		if(isempt($filepath) || !file_exists($filepath))return returnerror('文件不存在2');
		
		$pdfpath	= $frs['pdfpath'];
		if(!isempt($pdfpath) && file_exists($pdfpath))return returnerror('已转过了');
		
		if(getconfig('officeyl')=='1'){
			$barr 	=$this->xinhuobj->postdata('office','recedata', array(
				'data' 		=> $this->rock->jm->base64encode(file_get_contents($filepath)),
				'fileid' 	=> $fileid,
				'fileext'	=> $fileext,
				'filesize'	=> $filesize,
				'filesizecn'=> $frs['filesizecn'],
			));
		}else{
			if(!contain(PHP_OS,'WIN'))return returnerror('只能在windows的服务器下转化');
			$bo 		= c('socket')->topdf($frs['filepath'], $fileid, $fileext);
			if(!$bo || is_string($bo))return returnerror(''.$bo.'');
			$barr 	= returnsuccess();
		}
		
		if($barr['success']){
			$times = ceil($filesize/(30*1024));//默认50/秒
			if($times<10)$times = 10;
			$barr['data']['times'] = $times;
		}
		
		return $barr;
	}
	
	public function officestatus($fileid)
	{
		if(getconfig('officeyl')=='1'){
			$barr =  $this->xinhuobj->getdata('office','getstatus', array(
				'fileid' => $fileid
			));
		}else{
			$frs 	= m('fileda')->getone($fileid);
			if(!$frs)return returnerror('文件不存在1');
			$filepath 	= $frs['filepath'];
			$pdfpath	= str_replace('.'.$frs['fileext'].'', '.pdf', $filepath);
			$status 	= 0;
			if(file_exists($pdfpath)){
				$status = 1;
			}
			if($status==1){
				m('fileda')->update(array(
					'pdfpath' => $pdfpath
				), $fileid);
			}
			$barr 	= returnsuccess(array('status'=>$status,'ftype'=>'0'));
		}
		return $barr;
	}
	
	public function officedown($fileid)
	{
		$barr =  $this->xinhuobj->getdata('office','down', array(
			'fileid' => $fileid
		));
		if($barr['success']){
			$data 	 = $barr['data'];
			$pdfpath = $data['pdfpath'];
			$this->rock->createtxt($pdfpath, $this->rock->jm->base64decode($data['data']));
			m('fileda')->update(array(
				'pdfpath' => $pdfpath,
			),$fileid);
			$barr = returnsuccess();
		}
		return $barr;
	}
}