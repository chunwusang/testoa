<?php
/**
*	REIM的
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;


use App\Model\Base\ImmessModel;
use App\Model\Base\ImmessztModel;
use App\Model\Base\ImchatModel;
use App\Model\Base\ImgroupModel;
use App\Model\Base\ImgroupuserModel;
use App\Model\Base\UseraModel;
use App\Model\Base\UsersModel;
use App\Model\Base\FileModel;
use App\Model\Base\TodoModel;
use App\Model\Base\AgenhModel;
use DB;

class ChajianBase_reim extends ChajianBase
{
	private $limit = 5;
	
	public function initChajian()
	{
		$this->useraobj = $this->getNei('usera');
	}
	
	/**
	*	接收人信息
	*/
	public function getreceinfo($type, $gid)
	{
		$info 	= new \StdClass();
		$info->name = '';
		$info->face = '/images/helpbg.png';
		$info->id   = $gid;
		if($type=='user'){
			$infoa = $this->useraobj->getuserainfo($gid);
			if($infoa)$info = $infoa;
		}
		if($type=='group'){
			$infoa = ImgroupModel::select('id','name','cid','deptid','face','aid','gonggao')->where('cid',$this->companyid)->find($gid);
			if($infoa)$info = $infoa;
		}
		if($type=='agenh'){
			$infoa = AgenhModel::where('cid',$this->companyid)->find($gid);
			if($infoa){
				$info->name = $infoa->name;
				$info->face = $infoa->face;
				$info->id   = $infoa->id;
			}
		}
		return $info;
	}
	
	/**
	*	获取会话全部信息
	*/
	public function getchatinfo($type, $gid, $glx=0)
	{
		$info = $this->getreceinfo($type, $gid);
		if(!$info)return false;
		$uiarr= array();
		if($type=='group'){
			$uiarr = $this->getgroupuser($gid);
			$info->usershu = count($uiarr);
			
			if($glx==0){
				$barr['createinfo'] = $this->useraobj->getuserainfo($info->aid);
			}
			
			$info->iscreate = ($info->aid == $this->useaid);
			$info->isin		= in_array($this->useaid, $uiarr); //判断我有没有在会话里
		}else{
			$uiarr = [$gid, $this->useaid];
			
		}
		if($glx==0 && $uiarr){
			$barr['userlist'] = UseraModel::select('id','name','cid','uid')->where('cid', $this->companyid)->whereIn('id', $uiarr)->orderBy('sort','desc')->get();
		}
		
		$barr['receinfo'] = $info;
		$barr['type'] = $type;
		
		return $barr;
	}
	
	public function gettypeid($type)
	{
		$barr['user'] = 0;
		$barr['group'] = 1;
		$barr['agenh'] = 2;
		return $barr[$type];
	}
	
	public function getidtype($type)
	{
		$barr[0] = 'user';
		$barr[1] = 'group';
		$barr[2] = 'agenh';
		return $barr[$type];
	}
	
	/**
	*	返回消息总数
	*/
	public function getrecordcount($type, $gid)
	{
		return $this->getrecord($type, $gid, 0, '', 2);
	}
	
	/**
	*	读取消息记录
	*/
	public function getrecord($type, $gid, $minid=0, $lastdt='', $clbo=0)
	{
		$type 	= $this->gettypeid($type);
		$obj 	= ImmessModel::where('cid', $this->companyid);
		$obj->where('type', $type);
		if($type==0){
			$obj->whereRaw('((`aid`='.$this->useaid.' and `receid`='.$gid.') or (`receid`='.$this->useaid.' and `aid`='.$gid.'))');
		}else{
			$obj->where('receid', $gid);
		}
		
		if($lastdt != ''){
			$lastdt	= nowdt('', $lastdt-2);
			$obj->where('optdt','>', $lastdt);
			$obj->where('aid','<>', $this->useaid);
		}elseif($minid>0){
			$obj->where('id','<', $minid);
		}
		
		$obj->whereRaw($this->dbinstr('receids', $this->useaid));
		
		if($clbo==1)return $obj->get();
		if($clbo==2)return $obj->count();
		
		$obj->orderBy('id','desc');
		
		//DB::connection()->enableQueryLog();
	
		$data 	= $obj->simplePaginate($this->limit)->getCollection();
		
		$mids	= $bdata  = array();
		
		foreach($data as $k=>$rs){
			$ars = UseraModel::find($rs->aid);
			$sendname	= '';
			$face		= '/images/noface.png';
			$das 		= new \StdClass();
			$das->cont 	= $rs->cont;
			$das->fileid 	= $rs->fileid;
			$das->id 	= $rs->id;
			if($rs->fileid>0){
				$filers		 = FileModel::find($rs->fileid);
				$das->filers = $filers;
			}
			$das->optdt 	= $rs->optdt;
			
			if($ars){
				$sendname = $ars->name;
				$face 	  = $ars->face;
			}
			$das->sendid 	= $rs->aid;
			$das->sendname = $sendname;
			$das->face 	= $face;
			
			$mids[] = $rs->id;
			
			$bdata[]= $das;
		}

		//把这几个消息标识已读
		if($mids)ImmessztModel::where('aid', $this->useaid)->whereIn('mid', $mids)->delete();
		
		$this->biaoyd($type, $gid);
	
		
		return array(
			'rows' 		=> $bdata,
			'lastdt' 	=> $lastdt
		);
	}
	
	/**
	*	历史会话标识已读
	*/
	public function biaoyd($type, $gid)
	{
		ImchatModel::where('type', $type)
							->where('aid', $this->useaid)
							->where('receid', $gid)
							->update(['stotal'=>0]);
	}
	
	/**
	*	删除会话
	*/
	public function delhchat($type, $gid)
	{
		$type	= $this->gettypeid($type);
		ImchatModel::where('type', $type)
							->where('aid', $this->useaid)
							->where('receid', $gid)
							->delete();
	}
	
	/**
	*	发消息保存
	*/
	public function sendinfor($typeo, $gid, $cont, $ocan=array())
	{
		$type 		= $this->gettypeid($typeo);
		if($type==1){
			$uiarr = $this->getgroupuser($gid);
			if(!in_array($this->useaid, $uiarr))return '你没有此会话中，不能发消息';
		}
		
		//过滤内容
		$jm 		= c('rockjm');
		$cont 		= $jm->base64decode($cont);
		$cont 		= str_replace('<br>','[BR]', $cont);
		$cont 		= str_replace(array('<','>'),array('&lt;','&gt;'), $cont);
		$cont 		= $jm->base64encode(str_replace('[BR]','<br>',$cont));
		
		$optdt 		= arrvalue($ocan,'optdt', nowdt());
		$title		= arrvalue($ocan, 'title');
		$obj 		= new ImmessModel();
		$obj->cid	= $this->companyid;
		$obj->uid	= $this->userid;
		$obj->aid	= $this->useaid;
		$obj->type		= $type;
		$obj->receid	= $gid;
		$obj->cont		= $cont;
		$obj->optdt		= $optdt;
		foreach($ocan as $k=>$v)$obj->$k = $v;
		$obj->save();
		$id 	= $obj->id; //得到消息id
		
		//保存到消息状态表
		$addzta = $addztd = $addchat = array();
		$addchat[]= array(
			'aid' 	 => $this->useaid,
			'receid' => $gid,
			'stotal' => 0,
		);
		if($type==0){
			$addzta[] = $gid;
			$addchat[]= array(
				'aid' 	 => $gid,
				'receid' => $this->useaid,
				'stotal' => 1,
			);
		}elseif($type==1){
			
			foreach($uiarr as $aid){ //加入会话历史
				if($aid != $this->useaid){
					$addzta[] = $aid;
					$addchat[]= array(
						'aid' 	 => $aid,
						'receid' => $gid,
						'stotal' => 1,
					);
				}
			}
			$shua = c('rockjm')->base64encode(''.$this->useainfo->name.':');
			$cont = $shua.$cont;
		}
		
		foreach($addzta as $aid){
			$addztd[] = array(
				'cid' => $this->companyid,
				'uid' => $this->userid,
				'aid' => $aid,
				'type'=> $type,
				'gid' => $gid,
				'mid' => $id,
			);
		}
		DB::table('immesszt')->insert($addztd);
		
		//加入到消息会话历史记录里imchat
		$receids		= $this->addchathist($addchat, $type, $cont, $title, $optdt);

		$obj->receids 	= $receids;
		$obj->save();
		

		//推送到REIM客户端。
		$this->pushreim($typeo, $gid, $receids, [
			'cont'		=> $cont,
			'optdt'		=> $optdt,
			'messid'	=> $id
		]);

		return $id;
	}
	private function addchathist($addchat, $type, $cont, $title, $optdt)
	{
		$receids 	= '';
		foreach($addchat as $k=>$rs){
			$uarr['cid'] 	= $this->companyid;
			$uarr['senduid']= $this->userid;
			$uarr['sendaid']= $this->useaid;
			$receid 		= $rs['receid'];
			$aid 		  	= $rs['aid'];
			$uarr['type'] 	= $type;
			$uarr['receid'] = $receid;
			$uarr['aid']  	= $aid;
			$uarr['cont']  	= substr($cont,0,400);
			$uarr['title']  = $title;
			$uarr['optdt']  = $optdt;
			$uarr['stotal'] = $rs['stotal'];
			
			$tors	= ImchatModel::where('type',$type)->where('aid',$aid)->where('receid',$receid)->first();
			if($tors){
				$uarr['stotal'] += $tors->stotal;
				DB::table('imchat')->where('id', $tors->id)->update($uarr);
			}else{
				DB::table('imchat')->insert($uarr);
			}
			$receids .= ','.$aid.'';
		}
		if($receids!='')$receids = substr($receids, 1);
		
		return $receids;
	}
	
	/**
	*	推送到REIM服务端给客户端
	*	$ptype:user单聊,group群,agenh应用消息
	*	$receid 接收单位用户ID
	*	$arr 数组
	*/
	public function pushreim($ptype, $gid, $receid, $arr)
	{
		if(isempt($receid))return '接收人不能为空';
		
		$arr['type']  = 'reim'; //固定的
		$arr['ptype'] = $ptype;
		$arr['gid']   = $gid;//单位用户Id,会话ID,应用agenh.ID
		if($this->useaid > 0){
			$arr['sendid']  = $this->useaid; //单位用户ID
			$arr['sendname']= $this->useainfo->name;
			$arr['sendface']= $this->useainfo->face;
			$arr['companyname']  = $this->useainfo->companyname;
			$arr['companylogo']  = $this->useainfo->companylogo;
			$arr['companynum']   = $this->useainfo->companynum;
		}
		
		//显示在会话列表
		if($ptype=='user'){
			$arr['name']	= $arr['sendname'];
			$arr['face']	= $arr['sendface'];
		}
		if($ptype=='group'){
			$info 	= $this->getreceinfo($ptype, $gid);
			$arr['name']	= $info->name;
			$arr['face']	= $info->face;
			$arr['deptid']	= $info->deptid;
		}
		if($ptype=='agenh'){
			$info 	= $this->getreceinfo($ptype, $gid);
			$arr['name']	= $info->name;
			$arr['face']	= $info->face;
		}
		
		
		$receid1 = $this->pushreceid($receid);
		if(isempt($receid1))return ''.$receid.'中没有激活用户';
		$arr['receid'] = $receid1;
		$conf 		 = config('rockreim');
		$arr['from'] = $conf['reimfrom'];
		$arr['cid']  = $this->companyid;
		
		$rarr[] = $arr;
		$barr 	= c('socket')->udpsend($rarr, $conf['ip'], $conf['port']);
		
		if(!$barr['success'])return $barr['msg'];
	}
	
	/**
	*	要推送人员的平台用户ID，需在线
	*/
	public function pushreceid($receid)
	{
		$uida = array();
		$rows = UseraModel::select('uid')->where([
			'cid' 		=> $this->companyid,
			'status' 	=> 1,
		])->whereIn('id', explode(',', $receid))->where('uid','>', 0)->get();
		foreach($rows as $k=>$rs){
			if($rs->online==1)$uida[] = $rs->uid;
		}
		return join(',', $uida);
	}
	
	/**
	*	应用消息推送
	*/
	public function pushagenh($aids, $gid, $title, $cont, $url='')
	{
		$addchat	= array();
		foreach($aids as $aid){
			$addchat[]	= array(
				'aid' 	 => $aid,
				'receid' => $gid,
				'stotal' => 1,
			);
		}
		$optdt		= nowdt();
		$cont 		= c('rockjm')->base64encode($cont);
		$receids	= $this->addchathist($addchat, 2, $cont, $title, $optdt);
		
		$this->pushreim('agenh', $gid, $receids, [
			'cont'		=> $cont,
			'optdt'		=> $optdt,
			'title'		=> $title,
			'url'		=> $url,
		]);
	}
	
	/**
	*	获取组下人员
	*/
	public function getgorupuser($gid)
	{
		
	}
	
	
	/**
	*	统计对应单位下未读的，从todo表读取
	*/
	public function getcharhist($comall=array())
	{
		$arr = array();
		$jm	 = c('rockjm');
		$wdu = false;
		if($comall)foreach($comall as $k=>$ars){
			$rs 	= $ars->company;
			if($rs->id==$this->companyid)continue;
			
			$lastrs = TodoModel::where('cid', $rs->id)
								 ->where('aid', $ars->id)
								 ->where('status', 0);
			$stotal	= $lastrs->count();		
			
			$lastrs = $lastrs->orderBy('id','desc')->first();
			if(!$lastrs)continue;
			$arr[] = array(
				'type' 		=> 'unit',
				'receid' 	=> $rs->num,
				'optdt' 	=> $lastrs->tododt,
				'face'		=> $rs->logo,
				'name'		=> $rs->name,
				'stotal' 	=> $stotal,
				'cont'		=> $jm->base64encode(''.$lastrs->typename.':'.$lastrs->mess.'')
			);
			$wdu = true;
		}
		
		$lrows	= ImchatModel::where('cid', $this->companyid)->where('aid', $this->useaid)->orderBy('optdt','desc')->get();
		foreach($lrows as $k=>$rs){
	
			$type = $this->getidtype($rs->type);
			$info = $this->getreceinfo($type, $rs->receid);
			
			$arr[] = array(
				'type' 		=> $type,
				'receid' 	=> $rs->receid,
				'optdt' 	=> $rs->optdt,
				'face'		=> $info->face,
				'name'		=> $info->name,
				'title'		=> $rs->title,
				'stotal' 	=> $rs->stotal,
				'cont'		=> $rs->cont
			);
		}
		
		//排序
		if($wdu)$arr = c('array')->order($arr,'optdt');
		
		return $arr;
	}
	
	
	/**
	*	创建会话
	*/
	public function createchat($name, $deptid)
	{
		$obj = new ImgroupModel();
		$obj->name = $name;
		$obj->deptid = $deptid;
		$obj->cid	= $this->companyid;
		$obj->uid	= $this->userid;
		$obj->aid	= $this->useaid;
		$obj->optdt	= nowdt();
		$obj->save();
		
		$gid = $obj->id;
		
		//默认自己要加入
		$uobj = new ImgroupuserModel();
		$uobj->cid	= $this->companyid;
		$uobj->aid	= $this->useaid; 
		$uobj->gid	= $gid;
		
		$uobj->save();
	}
	
	/**
	*	获取我加入的会话组
	*/
	public function getgroup()
	{
		$urows = ImgroupuserModel::where('cid', $this->companyid)->where('aid', $this->useaid)->get();
		$ugida = array();
		foreach($urows as $k=>$rs)$ugida[] = $rs->gid;
		$barr  = array();
		if($ugida){
			$barr = ImgroupModel::select('face','name','id','aid')->where('cid', $this->companyid)->whereIn('id', $ugida)->get();
		}
		return $barr;
	}
	
	/**
	*	获取会话下人员Id
	*/
	public function getgroupuser($gid, $glx=0)
	{
		$uarr['cid'] = $this->companyid;
		$uarr['gid'] = $gid;
		if($glx==1)return ImgroupuserModel::where($uarr)->count();
		$aida  = array();
		$urows = ImgroupuserModel::where($uarr)->get();
		foreach($urows as $k=>$rs)$aida[] = $rs->aid;
		return $aida;
	}
	
	/**
	*	邀请添加
	*/
	public function chatadduser($gid, $receid)
	{
		
		$adduari = $this->getNei('contain')->getaids($receid);
		$oi 	 = 0;
		$aida 	 = $this->getgroupuser($gid); //已加入
		$inarr 	 = array();
		if($adduari){
			$adduari = explode(',', $adduari);
			foreach($adduari as $aid){
				if(!in_array($aid, $aida)){
					$inarr[] = array(
						'cid' => $this->companyid,
						'aid' => $aid,
						'gid' => $gid,
					);
					$oi++;
				}
			}
		}
		if($inarr)DB::table('imgroupuser')->insert($inarr);
		return $oi;
	}
	
	/**
	*	修改群名
	*/
	public function editchatname($gid,$name)
	{
		$frs = ImgroupModel::where('cid', $this->companyid)->where('id', $gid)->first();
		if($frs){
			$frs->name = $name;
			$frs->save();
		}
	}
	
	/**
	*	修改公告
	*/
	public function editchatgong($gid,$cont)
	{
		$frs = ImgroupModel::where('cid', $this->companyid)->where('id', $gid)->first();
		if($frs){
			$frs->gonggao = nulltoempty($cont);
			$frs->save();
		}
	}
	
	/**
	*	退出
	*/
	public function exitchat($gid)
	{
		$info = $this->getreceinfo('group', $gid);
		if(!$info){
			ImgroupuserModel::where('gid', $gid)->delete();
		}else{
			ImgroupuserModel::where('gid', $gid)->where('aid', $this->useaid)->delete();
			//如果是创建人，随机改成别的用户
			if($this->useaid == $info->aid){
				$ransrs = ImgroupuserModel::where('gid', $gid)->first();
				if($ransrs){
					$info->aid = $ransrs->aid;
					$info->uid = 0;
					$info->save();
				}
			}
		}
	}
	
	/**
	*	清空
	*/
	public function clearrecord($type, $gid)
	{
		$resaall = $this->getrecord($type, $gid, 0, '', 1);
		$type 	 = $this->gettypeid($type);
		$aid 	 = $this->useaid;
		$oi		 = 0;
		foreach($resaall as $k=>$rs){
			$receida = explode(',', $rs->receids);
			$lasr	 = array();
			foreach($receida as $aid1)if($aid != $aid1)$lasr[] = $aid1;
		
			$rs->receids = join(',', $lasr);
			$rs->save();
			$oi++;
		}
		ImmessztModel::where(['aid'=>$aid,'type'=>$type,'gid'=>$gid])->delete();
	
		$this->biaoyd($type, $gid);
		return $oi;
	}
	
	/**
	*	删除记录
	*/
	public function delrecord($ids)
	{
		$mids	 = explode(',', $ids);
		$resaall = ImmessModel::where('cid', $this->companyid)->whereIn('id', $mids)->get();
		$aid 	 = $this->useaid;
		$oi		 = 0;
		foreach($resaall as $k=>$rs){
			$receida = explode(',', $rs->receids);
			$lasr	 = array();
			foreach($receida as $aid1)if($aid != $aid1)$lasr[] = $aid1;
		
			$rs->receids = join(',', $lasr);
			$rs->save();
			$oi++;
		}
		ImmessztModel::where('aid', $this->useaid)->whereIn('mid', $mids)->delete();
		return $oi;
	}
}