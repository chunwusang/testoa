<?php
/**
*	应用.单据通知设置
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-03-05
*/

namespace App\RainRock\Flowagent;

use App\RainRock\Flow\Rockflow;
use Request;

class Flowagent_remind  extends Rockflow
{
	//默认排序
	public $defaultorder	= 'optdt,desc';
	
	//不保存草稿
	protected $flowisturnbool	= false;
	
	
	protected function flowinit()
	{
		$this->changeStatus();	
	}
	
	public function flowinputdefault($req=null)
	{
		$num = Request::get('num');
		$mid = (int)Request::get('mid','0');
		if(isempt($num) || $mid==0)return array();
		
		$flow = \Rock::getFlow($num, $this->useainfo);
		$flow->initdata($mid);
		$barr = $this->getaddparams($flow);
		return $barr;
	}
	
	private function getaddparams($flow)
	{
		$barr = [
			'agenhid' 	=> $flow->agenhid,
			'agenhname' => $flow->agenhname,
			'mid'	 	=> $flow->mid,
			'mtable'	=> $flow->mtable,
			'status'	=> 1,
			'todotit'	=> ''.$flow->agenhname.'提醒',
		];
		
		//获取默认提醒的模版
		$narr = $flow->flowreminddata();
		foreach($narr as $k=>$v)$barr[$k] = $v;
		
		
		if(!isset($barr['todocont']))$barr['todocont'] = $flow->getsummary();
		return $barr;
	}
	
	/**
	*	加入到提醒表里
	*/
	public function addremind($flow)
	{
		$barr 	= $this->getaddparams($flow);
		$barr['isturn'] 	= 1; //是要提醒
		$mid 	= c('remind', $this->useainfo)->getmid($barr['mtable'], $barr['mid']);
		return $this->saveData($mid, $barr); //保存
	}
	
	
	//自定义的元素
	public function inputAuto_temp_rateval()
	{
		$rate		= objvalue($this->rs, 'rate');
		$rateval	= objvalue($this->rs, 'rateval');;
		$str		= '<div id="pinlv">';
		
		$ratea		= explode(',', $rate);
		$rateb		= explode(',', $rateval);
		$len 		= count($ratea);
		$selarr['o'] = '仅一次';
		$selarr['h'] = '每小时';
		$selarr['d'] = '每天';
		//$selarr['g'] = '每工作日';
		//$selarr['x'] = '每休息日';
		$selarr['w1'] = '每周一';
		$selarr['w2'] = '每周二';
		$selarr['w3'] = '每周三';
		$selarr['w4'] = '每周四';
		$selarr['w5'] = '每周五';
		$selarr['w6'] = '每周六';
		$selarr['w7'] = '每周日';
		$selarr['m']  = '每月';
		$selarr['y']  = '每年';
		$isbr 		  = '';
		for($i=0; $i<$len; $i++){
			$selstr	= '';
			$v1 	= $ratea[$i];
			$v2a 	= explode('|', $rateb[$i]);
			$v2 	= $v2a[0];
			$v3 	= arrvalue($v2a, 1);
			foreach($selarr as $k=>$v){
				$slde 	= ($k==$v1) ? 'selected' : '';
				$selstr.='<option value="'.$k.'" '.$slde.'>'.$v.'</option>';
			}
			
			$fontss = ($v1=='h')?'':'none';
			$stsnn  = ($i>0)? 'style="padding-top:10px;margin-top:10px;border-top:1px #cccccc solid"' : '';
			$str .= '<div '.$stsnn.'><select onchange="changerate(this)" style="width:auto" class="inputs" name="rave_pinlvs1">'.$selstr.'</select>';
			$str.= '<input onblur="changeblur(this)"  style="width:auto" class="inputs input_date" onclick="js.datechange(this,\'datetime\')" readonly value="'.$v2.'" name="rave_pinlvs2" type="text">'.$isbr.'<font style="display:'.$fontss.'">&nbsp;每天截止至&nbsp;<input onblur="changeblur2(this)"  style="width:80px" class="inputs input_date" onclick="js.datechange(this,\'time\')" readonly value="'.$v3.'" name="rave_pinlvs3" type="text"></font>'.$isbr.'<input type="button" onclick="changeadd(this)" value="＋" class="input_btn"><input onclick="changejian(this)" type="button" value="－" class="input_btn">&nbsp;<span></span>';
			$str.= '</div>';
		}
		
		$str .= '</div>';
		
		return $str;
	}
}