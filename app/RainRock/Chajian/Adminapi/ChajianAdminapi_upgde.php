<?php
/**
*	连接官网升级
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-05
*/

namespace App\RainRock\Chajian\Adminapi;

use App\Model\Base\ChargemsModel;
use App\Model\Base\AgentModel;
use App\Model\Base\AgentfieldsModel;
use App\Model\Base\AgentmenuModel;
use App\Model\Base\FlowmenuModel;
use App\Model\Base\AgenttodoModel;

class ChajianAdminapi_upgde extends ChajianAdminapi
{
	private function getsysnum()
	{
		$optboj	= c('option');
		$val 	= $optboj->getval('systemnum');
		if(isempt($val)){
			$val  	= md5(str_shuffle('abcedfghijk').rand(1000,9999));
			$optboj->setval('systemnum', $val);
		}
		return $val;
	}
	
	/**
	*	获取数据
	*/
	public function getData()
	{
		$barr = c('xinhuapi')->get('mode');
		if($barr['success']){
			$rows = $barr['data'];
			foreach($rows as $k=>$rs){
				if($rs['isaz']=='0')continue;
				$state = 0; //未安装
				$fors  = ChargemsModel::where('type',0)->where('mid', $rs['id'])->first();
				if($fors && !isempt($fors->updatedt)){
					$state = 1;
					if($rs['updatedt']>$fors->updatedt)$state=2;
				}
				$rows[$k]['state'] = $state;
			}
			$barr['data'] = $rows;
		}
		return $barr;
	}
	
	
	/**
	*	设置安装key
	*/
	public function postsetkey($request)
	{
		$mid 	= (int)c('rockjm')->base64decode($request->get('mode'));
		$key 	= trim(nulltoempty($request->input('key')));
		
		$fors   = ChargemsModel::where('type',0)->where('mid', $mid)->first();
		
		if(!$fors){
			$fors = new ChargemsModel();
			$fors->optdt 	= nowdt();
			$fors->updatedt = null;
		}
		$fors->type 	= 0;
		$fors->mid 		= $mid;
		$fors->modeid 	= $mid;
		$fors->key 		= c('rockjm')->encrypt($key);
		$fors->save();
		return returnsuccess();
	}
	
	/**
	*	获取要更新的文件
	*/
	public function getfile($request)
	{
		$mid 	= (int)c('rockjm')->base64decode($request->get('mode'));
		$sysnum = $this->getsysnum();
		$key 	= '';
		$fors   = ChargemsModel::where('type',0)->where('mid', $mid)->first();
		if($fors){
			$key = c('rockjm')->uncrypt($fors->key);
		}
		$barr 	= c('xinhuapi')->get('getinstallfile',['id'=>$mid,'key'=>$key,'sysnum'=>$sysnum]);
		
		//读取忽略文件
		$hurow	= ChargemsModel::where('type',2)->get();
		$huarr  = array();
		foreach($hurow as $k1=>$rs1)$huarr[$rs1->mid] = $rs1->mid;
		
		if($barr['success']){
			$rows = $barr['data']['rows'];
			$luj  = str_replace('\\','/', base_path());
			foreach($rows as $k=>$rs){
				$state = 0; //1需要更新
				$ishl  = 0; //是否忽略
				$filepath 	= $rs['filepath'];
				$file	 	= $luj.'/'.$filepath;
				if(file_exists($file)){
					$_size = filesize($file);
					if($_size!=$rs['filesize'])$state = 1;
				}else{
					$state = 1;
				}
				if(isset($huarr[$rs['id']]))$ishl = 1;
				$rows[$k]['state'] = $state;
				$rows[$k]['ishl']  = $ishl;
			}
			$barr['data']['rows'] = $rows;
		}
		return $barr;
	}
	
	
	
	/**
	*	升级
	*/
	public function postupfile($request)
	{
		$fileid = (int)$request->input('fileid','0');
		$mid 	= (int)$request->input('mid','0');
		$zmid 	= (int)$request->input('zmid','0');
		$uplx 	= (int)$request->input('uplx','0');
		$len 	= (int)$request->input('len','0');
		$oi 	= (int)$request->input('oi','0');
		$key 	= trim(nulltoempty($request->input('key')));
		$fors   = ChargemsModel::where('type',0)->where('mid', $mid)->first();
		$jm 	= c('rockjm');
		
		if(isempt($key) && $fors){
			$key = $jm->uncrypt($fors->key);
		}
		$syslx	= config('rock.systype');
		
		if($syslx=='demo')return returnerror(''.$syslx.'模式禁止操作');
		
		
		if($fileid>0){
			$sysnum = $this->getsysnum();
			$barr 	= c('xinhuapi')->get('getinstallfileid',['fid'=>$fileid,'key'=>$key,'sysnum'=>$sysnum]);
			if(!$barr['success'])return $barr;
			$data = $barr['data'];
			$type 		= $data['type'];
			$filepath 	= $data['filepath'];
			$luj  		= str_replace('\\','/', base_path());
			$xiugpeiz	= true;
			if(1==1){
				$file	 	= $luj.'/'.$filepath;
				$xiugpeiz	= file_exists($file); //是否存在
				$bo 		= c('base')->createdir($filepath);
				if(!$bo)return returnerror('没权限创建文件夹：'.$filepath.'');//创建文件夹
				
				$filecont	= c('rockjm')->base64decode($data['content']);
				@$bo 		= file_put_contents($file, $filecont);
				if(!$bo)return returnerror('没权限写入文件：'.$filepath.'');
			}
			
			//数据库更新$uplx==2，自动更新数据库
			if($type==1){
				$upb = c('mysql')->updatefabric($filecont);
				if($upb!='ok')return returnerror($upb);
			}
			
			//修改应用配置 && ($uplx==1 || !$xiugpeiz)，直接更新
			if($type==10){
				$this->updatepei($filecont);
			}
		}
		
		//已更新完成
		if($oi+1==$len){
			$fors   = ChargemsModel::where('type',0)->where('mid', $zmid)->first();
			if(!$fors){
				$fors = new ChargemsModel();
				$fors->optdt = nowdt();
			}
			$fors->type 	= 0;
			$fors->mid 		= $zmid;
			$fors->modeid 	= $zmid;
			//$fors->key 		= $jm->encrypt($key);
			$fors->updatedt = nowdt();
			$fors->save();
		}
		
		return returnsuccess();
	}
	
	//更新应用配置
	private function updatepei($cont)
	{
		$arr = json_decode($cont, true);
		$agentinfo 	= $arr['agent'];
		$num 		= $agentinfo['num'];

		unset($agentinfo['id']);
		$agentid 	= 0;
		
		//保存应用信息
		$frs 		= AgentModel::where('num', $num)->first();
		if(!$frs){
			$frs	= new AgentModel();
		}
		foreach($agentinfo as $k=>$v)$frs->$k = $v;
		
		$frs->save();
		$agentid 	= $frs->id;
		
		//保存字段信息
		$agentfields	= $arr['agentfields'];
		foreach($agentfields as $k=>$rsarr){
			$oners = AgentfieldsModel::where(['agentid'=>$agentid,'fields'=>$rsarr['fields']])->first();
			if(!$oners)$oners	= new AgentfieldsModel();
			
			unset($rsarr['id']);
			foreach($rsarr as $k1=>$v){
				$oners->$k1 = $v;
			}
			$oners->agentid = $agentid;
			$oners->save();
		}
		
		//保存应用列表菜单
		$agentmenu	= $arr['agentmenu'];
		$jpidar		= $jpidarpid = $nxuxu = array();
		foreach($agentmenu as $k=>$rsarr){
			$jpidar[$rsarr['id']] = $k;
		}
		foreach($agentmenu as $k=>$rsarr){
			if($rsarr['pid']>0)$jpidarpid[$rsarr['pid']] = $jpidar[$rsarr['pid']]; //记录对应哪个序号
		}
		foreach($agentmenu as $k=>$rsarr){
			$oners = AgentmenuModel::where(['agentid'=>$agentid,'name'=>$rsarr['name']])->first();
			if(!$oners)$oners	= new AgentmenuModel();
			
			unset($rsarr['id']);
			foreach($rsarr as $k1=>$v){
				$oners->$k1 = $v;
			}
			$oners->agentid = $agentid;
			$oners->save();
			
			$nxuxu[$k] =  $oners->id; //记录序号最后ID
		}
		//更新升级ID
		foreach($jpidarpid as $pid=>$xu){
			$npid = $nxuxu[$xu];
			if($npid==$pid)continue;
			AgentmenuModel::where(['agentid'=>$agentid,'pid'=>$pid])->update(['pid'=>$npid]);
		}
		
		
		//更新操作菜单
		$flowmenu	= $arr['flowmenu'];
		foreach($flowmenu as $k=>$rsarr){
			$oners = FlowmenuModel::where(['agentid'=>$agentid,'name'=>$rsarr['name']])->first();
			if(!$oners)$oners	= new FlowmenuModel();
			
			unset($rsarr['id']);
			foreach($rsarr as $k1=>$v){
				$oners->$k1 = $v;
			}
			$oners->agentid = $agentid;
			$oners->save();
		}
		
		//单据通知
		$agenttodo	= $arr['agenttodo'];
		foreach($agenttodo as $k=>$rsarr){
			$oners = AgenttodoModel::where(['agentid'=>$agentid,'name'=>$rsarr['name']])->first();
			if(!$oners)$oners	= new AgenttodoModel();
			
			unset($rsarr['id']);
			foreach($rsarr as $k1=>$v){
				$oners->$k1 = $v;
			}
			$oners->agentid = $agentid;
			$oners->save();
		}
		
	}
	
	/**
	*	忽略	
	*/
	public function posthulue($request)
	{
		$lx = (int)$request->input('lx');
		$id = (int)$request->input('id');
		$mid = (int)$request->input('mid');
		$where = [
			'mid' 		=> $id,
			'modeid' 	=> $mid,
			'type'		=> 2
		];
		if($lx==0){
			ChargemsModel::where($where)->delete();
		}else{
			$ors = ChargemsModel::where($where)->first();
			if(!$ors)$ors = new ChargemsModel();
			$ors->mid = $id;
			$ors->modeid = $mid;
			$ors->type = 2;
			$ors->optdt = nowdt();
			$ors->save();
		}
		return returnsuccess();
	}
}