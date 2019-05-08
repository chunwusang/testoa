<?php
/**
*	应用.文档分区
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-15
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use Request;
use Rock;

class Flowagent_word extends Rockflow
{
	
	//不保存草稿
	protected $flowisturnbool	= false;
	public $defaultorder		= 'type,desc|id,desc';
	public $edittablebool		= true;
	
	
	protected function flowinit()
	{
		$this->fileobj = c('file');
	}
	
	protected function flowlistoption()
	{
		
		/*
		$barr[] 	 = [
			'name' 	=> '共享给',
			'click'	=> 'shateopt',
			'icons'	=> 'share',
			'class'	=> 'default'
		];
		$barr[] 	 = [
			'name' 	=> '取消共享',
			'click'	=> 'shatecancek',
			'class'	=> 'default'
		];*/
		
		$barr[] 	 = [
			'name' 	=> '分区管理',
			'click'	=> 'fenquguan',
			'class'	=> 'default'
		];
		$barr[] 	 = [
			'name' => '删除',
			'click'=> 'deldetebti',
			'class'=> 'danger',
			'attr' => 'disabled id="delbtn"',
			'icons'=> 'trash'
		];
		

		
		$barr[] 	 = [
			'name' 	=> '创建文件夹',
			'click'	=> 'createfloder',
			'icons'	=> 'folder-close',
			'attr'	=> 'disabled id="folderbtn"',
			'class'	=> 'default'
		];
		
		$barr[] 	 = [
			'name' 	=> '上传文件',
			'click'	=> 'upfilestart',
			'icons'	=> 'upload',
			'attr'	=> 'disabled id="upbtn"',
			'class'	=> 'primary'
		];
		$fqarr	  = $this->getmyfenqu();
		$allfq	  = '0';
		$leftstr  = '<select id="fenqusel" onchange="changefenqu(true)" class="form-control" style="width:130px">';
		$leftstr .= '<option uptype="" value="0" isup="0" isguan="0">-全部分区下-</option>';
		foreach($fqarr as $k=>$frs){
			$leftstr .= '<option value="'.$frs['id'].'" uptype="'.$frs['uptype'].'" isup="'.$frs['isup'].'" isguan="'.$frs['isguan'].'">'.$frs['name'].'</option>';
			$allfq.=','.$frs['id'].''; //所有分区
		}
		$leftstr .= '</select>';
		$leftstr .= '<input value="'.$allfq.'" type="hidden" id="allfqid">';
		
		//$cbarr = $this->addqueue('email','send');
		//print_r($cbarr);
		
		return [
			'checkcolums' => true,
			'btnarr' => $barr,
			'leftstr'	=> $leftstr
		];
	}
	
	/**
	*	应用显示时
	*	$lx0pc，1手机
	*/
	public function flowlistview($lx)
	{
		$barr = array();
		if($lx==0)$barr['menuarr'] = array();//PC端不需要菜单
		if($lx==1){
			$fqarr		= $this->getmyfenqu();
			$allfq			= '0';
			foreach($fqarr as $k=>$frs){
				$allfq.=','.$frs['id'].''; //所有分区
			}
			$barr['fqarr']	= $fqarr;
			$barr['allfq']  = $allfq;
		}
		return $barr;
	}
	
	//获取我的分区
	public function getmyfenqu()
	{
		$obj 	= $this->getModel('worc')->select();
		$obj->where('cid', $this->companyid);
		$rows 	= $obj->orderBy('sort')->get();
		$barr 	= array();
		$onbo	= $this->getNei('contain');
		$bobj	= $this->getNei('base');
		foreach($rows as $k=>$rs){
			if(!$onbo->iscontain($rs->receid))continue;
			$isup = 0;//是否可以上传
			$isguan = 0;//是否可以管理
			if($onbo->iscontain($rs->upuserid))$isup = 1;
			if($onbo->iscontain($rs->guanid))$isguan = 1;

			$barr[] = array(
				'name' 		=> $rs->name,
				'id'		=> $rs->id,
				'isup'		=> $isup,
				'uptype'	=> $rs->uptype,
				'isguan'	=> $isguan,
				'sizecn'	=> $bobj->formatsize($rs->sizeu)
			);
		}
		
		return $barr;
	}
	
	
	public function flowreplacers($rs)
	{
		$fileext	 = '/images/icons/folder.png';
		if($rs->type==0){
			if($this->fileobj->isimg($rs->fileext)){
				$fileext = $rs->thumbpath;
			}else{
				$fileext = '/images/fileicons/'.$this->fileobj->filelxext($rs->fileext).'.gif';
			}
			if($rs->isdel==1){
				$rs->ishui = 1;
				$rs->filename = '<s>'.$rs->filename.'</s>';
			}
		}else{
			$rs->optname = '';
			$rs->optdt 	= '';
			$rs->downci = $this->getModel()->where('folderid', $rs->id)->count(); //下级
		}
		$rs->fileext = '<img src="'.Rock::replaceurl($fileext).'" height="24">';
		return $rs;
	}
	
	public function flowbillwhere($obj, $atype)
	{
		$allfqid 	= explode(',', Request::get('allfqid'));
		$fqid 	 	= (int)Request::get('fqid','0');
		$folderid 	= (int)Request::get('folderid','0');
		
		$obj->whereIn('fqid', $allfqid);
		$this->fqid 	= $fqid;
		$this->folderid = $folderid;
		if($fqid>0){
			$obj->where('fqid', $fqid);
		}
		if($folderid>0)$obj->where('folderid', $folderid);
		
		return $obj;
	}
	
	//应用数据处理，获取到路径
	public function flowgetdata()
	{
		if($this->fqid==0){
			$lujarr[] = array(
				'name' 		=> '所有分区',
				'fqid' 		=> 0,
				'folderid'  => 0,
			);
		}else{
			$fqrs = $this->getModel('worc')->find($this->fqid);
			$lujarr[] = array(
				'name' 		=> $fqrs->name,
				'fqid' 		=> $fqrs->id,
				'folderid'  => 0,
			);
			
			if($this->folderid>0){
				$this->folderarr = array();
				$this->folderarrget($this->folderid);
				foreach($this->folderarr as $k=>$rs){
					$lujarr[] = array(
						'name' 		=> $rs->filename,
						'fqid' 		=> $rs->fqid,
						'folderid'  => $rs->id,
					);
				}
			}
			
		}
		return [
			'lujarr' => $lujarr
		];
	}
	private function folderarrget($id)
	{
		$frs = $this->getModel()->find($id);
		if($frs){
			if($frs->folderid>0)$this->folderarrget($frs->folderid);
			$this->folderarr[] = $frs;
		}
	}
	
	/**
	*	创建文件夹
	*/
	public function post_createfloder($request)
	{
		$barr 			= $this->saveData(0, array(
			'fqid' 		=> (int)$request->input('fqid','0'),
			'folderid' 	=> (int)$request->input('folderid','0'),
			'type' 		=> 1,
			'filename' 	=> nulltoempty($request->input('name')),
		));
		$barr['data'] = '创建成功';
		return $barr;
	}
	
	/**
	*	保存文件
	*/
	public function post_savefile($request)
	{
		$barr 			= $this->saveData(0, array(
			'fqid' 		=> (int)$request->input('fqid','0'),
			'folderid' 	=> (int)$request->input('folderid','0'),
			'type' 		=> 0,
			'filename' 	=> nulltoempty($request->input('filename')),
			'fileext' 	=> nulltoempty($request->input('fileext')),
			'filesizecn' => nulltoempty($request->input('filesizecn')),
			'filesize' 	=> nulltoempty($request->input('filesize')),
			'thumbpath' => nulltoempty($request->input('imgpath')),
			'filenum' 	=> nulltoempty($request->input('filenum')),
		));
	
		//返回当前分区大小
		$barr['data'] = '上传保存成功';
		return $barr;
	}
	
	/**
	*	重命名
	*/
	public function post_editname($request)
	{
		$id = (int)$request->input('id','0');
		$this->getModel()->where('id', $id)->update([
			'filename' => $request->input('name')
		]);
		return returnsuccess('修改成功');
	}
	
	//添加下载查看次数
	public function get_adddownci($request)
	{
		$sid = $request->input('sid');
		$obj = $this->getModel()->find($sid);
		$obj->downci = $obj->downci+1;
		$obj->save();
		return returnsuccess();
	}
	
	public function post_delfile($request)
	{
		$sida = explode(',', $request->input('sid'));
		foreach($sida as $sid){
			$barr = $this->delbill($sid,'', false);
			if(!$barr['success'])return $barr;
		}
		
		return returnsuccess('删除成功');
	}
	
	public function flowdelbillbefore()
	{
		$to = $this->getModel()->where('folderid', $this->mid)->count();
		if($to>0)return '底下有文件/文件夹不能删除';
	}
	
	public function flowdelbill()
	{
		$this->flowsaveafter($this->rs);
	}
	
	//更新大小
	public function flowsaveafter($arr)
	{
		$fqid = $arr->fqid;
		$sizeu= $this->getModel()->where('fqid', $fqid)->sum('filesize');
		$this->getModel('worc')->where('id', $fqid)->update(['sizeu'=>$sizeu]);
		return $sizeu;
	}
}