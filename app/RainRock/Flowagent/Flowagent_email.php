<?php
/**
*	应用.邮件
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;

class Flowagent_email  extends Rockflow
{
	public function getDatailtitle()
	{
		return '';
	}
	
	public function flowreplacers($rs, $lx=0)
	{
		if($rs->isread==0 && $rs->aid != $this->adminid)$rs->isbold = 1;
		if(!isempt($rs->senddt))$rs->senddt = $this->dtssss($rs->senddt);
		$lex  = 'email';
		if(contain($rs->huifuid, $this->adminid,','))$lex = 'email_go';
		
		$rs->temp_type = '<img src="/images/'.$lex.'.png" height="16" width="16">';
		if($rs->isfile==1 && $lx==2)$rs->title .= '<img src="/images/attach.png" height="16" width="16">';
		return $rs;
	}
	
	/**
	*	读取原来邮件内容
	*/
	public function getoldcont($hid, $bo=true)
	{
		$hid = (int)$hid;
		if($hid==0)return '';
		$hrs = $this->getone($hid);
		if(!$hrs)return '';
		$dts 	= $this->dtssss($hrs['senddt']);
		$fstr	= m('file')->getstr($this->mtable, $hrs['id'], 1);
		$s = '<div style="color:#888888;font-size:12px;margin-top:20px">------------------ 原始邮件 ------------------</div>';
		$s.= '<div style="font-size: 12px;background:#efefef;padding:8px;line-height:18px;">发件人: '.$hrs['sendname'].'<br>
			发送时间: '.$dts.'<br>
			收件人: '.$hrs['recename'].'<br>
			主题: '.$hrs['title'].'</div>';
		$s.= '<div style="margin-top:10px">'.$hrs['content'].'<br>'.$fstr.'</div>';
		if($bo)$s.= $this->getoldcont($hrs['hid'], $bo);
		return $s;
	}
	
	//回复时读取默认值
	public function flowinputdefault()
	{
		$emailid = (int)\Request::get('emailid','0');
		$title	 = '';
		if($emailid>0){
			$onrs = $this->getModel()->where(['cid'=>$this->companyid,'id'=>$emailid])->first();
			if($onrs){
				$title = '回复：'.$onrs->title.'';
			}else{
				$emailid = 0;
			}
		}
		$barr = [
			'emailid'=> $emailid,
			'title'	 => $title,
		];
		if($emailid>0){
			$barr['receid'] = 'u'.$onrs->aid.'';
			$barr['recename'] = $onrs->applyname;
		}
		return $barr;
	}
	
	private function dtssss($dt)
	{
		$cnw = c('date')->cnweek($dt);
		return date('Y年m月d日(星期'.$cnw.')H:i:s',strtotime($dt));
	}
	
	//保存前判断
	public function flowsavebefore($arr)
	{
		$data = array();
		if($arr->isturn==1)$data['senddt'] = nowdt();//发送时间 && isempt($arr->dingdt)
		$data['isfile'] = $this->isfujian;//有附件
		return array(
			'data' => $data
		);
	}
	
	//保存说我已回复
	public function flowsaveafter($arr)
	{
		$emailid = $arr->emailid;
		//回复时添加到
		if($emailid>0){
			$onrs = $this->getModel()->where(['cid'=>$this->companyid,'id'=>$emailid])->first();
			if($onrs){
				$huifuid = $onrs->huifuid;
				if(isempt($huifuid)){
					$huifuid = $arr->aid;
				}else{
					$huifuid .= ','.$arr->aid.'';
				}
				$onrs->huifuid = $huifuid;
				$onrs->save();
			}
		}
	}
	
	protected function flowlistoption()
	{
		$leftstr	= '<input readonly placeholder="发送日期" id="sousenddt" onclick="js.datechange(this)" class="form-control input_date" style="width:110px">';
		return [
			'leftstr'	=> $leftstr
		];
	}
	
	public function flowbillwhere($obj, $atype, $glx=1)
	{
		$dt = \Request::get('dt');
		if(!isempt($dt))$obj = $obj->where('senddt','like',''.$dt.'%');
		
		//过滤删除的
		$bstr 	= '(not '.$this->authoryobj->dbinstr('delid', $this->adminid).')';
		$obj 	= $obj->whereRaw($bstr);
		
		//必须大于用户创建时间
		if($atype=='all' || $atype=='allunread'){
			if(!isempt($this->useainfo->createdt))
				$obj	= $obj->where('optdt','>', $this->useainfo->createdt);
		}
		
		if($atype=='chaos' && $glx==2)$this->flowbillatype = 'unread'; //统计未读
		
		return $obj;
	}
	
	//删除邮件
	public function delbill($mid, $sm='', $delpx=true)
	{
		$cbar = $this->initdata($mid);
		if(!$cbar['success'])return $cbar;
		$onrs = $this->getModel()->where(['cid'=>$this->companyid,'id'=>$this->mid])->first();
		if($onrs){
			$delid = $onrs->delid;
			if(isempt($delid)){
				$delid = $this->adminid;
			}else{
				$delid .= ','.$this->adminid.'';
			}
			$onrs->delid = $delid;
			$onrs->save();
		}
		return returnsuccess();
	}
	
	public function flowlistview($lx)
	{
		return array(
			'keywordmsg' => '主题/收件人/发件人'
		);
	}
}