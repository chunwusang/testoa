<?php
/**
*	api单位管理-应用管理
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-06-18
*/

namespace App\RainRock\Chajian\Unitapi;

use Illuminate\Http\Request;
use App\Model\Base\AgenhModel;
use App\Model\Base\AgentModel;
use App\Model\Base\AgentfieldsModel;
use App\Model\Base\AgenhmenuModel;
use App\Model\Base\FlowcourseModel;
use App\Model\Base\AuthoryModel;
use Illuminate\Foundation\Validation\ValidatesRequests;
use DB;

class ChajianUnitapi_agenh extends ChajianUnitapi
{
	use ValidatesRequests;
	
	/**
	*	获取系统应用
	*/
	public function getsysagent(Request $request)
	{
		$cid 	= $this->companyid;
		$mall	= AgenhModel::where('cid', $cid)->where('agentid','>', 0)->get();
		$yins   = array();
		foreach($mall as $rs){
			$yins[]= $rs->num; //已添加应用存起来
		}
		$data 	= AgentModel::select()->where('status',1)->orderBy('sort')->get();
		$barr 	= array();
		$arobj	= c('array');
		foreach($data as $k=>$rs){
			$pmenustr = $rs->pmenustr;
			$rs->pnum = '';
			$pdnum 	  = $rs->num;
			if(isempt($pmenustr)){
				if(!in_array($pdnum, $yins))$barr[] = $rs;
			}else{
				$pmenuar = $arobj->strtoarray($pmenustr);
				foreach($pmenuar as $paa){
					$pnum 	  = $paa[0]; //分组编号
					$pdnum 	  = $rs->num;
					if(!isempt($pnum))$pdnum.='-'.$pnum.'';
					if(in_array($pdnum, $yins))continue;
				
					$brs = clone $rs;
					$brs->pnum = $pnum;
					$brs->name = $paa[1];
					$brs->face = arrvalue($paa,2,$rs->face);
					$barr[] = $brs;
				}
			}
		}
		return returnsuccess($barr);
	}
	
	/**
	*	添加系统应用到单位下
	*/
	public function postaddsave(Request $request)
	{
		$agentids 	= $request->input('agentids');
		if(isempt($agentids))return returnerror('add agentid isempty');
		$cid 	= $this->companyid; 	
		$arobj		= c('array');
		$agentida 	= $arobj->strtoarray($agentids);
		
		foreach($agentida as $aga){
			$agentid	= $aga[0];
			$pnum		= $aga[1];
			if(AgenhModel::where('cid', $cid)->where('agentid', $agentid)->where('pnum', $pnum)->count()==0){
				$obj 		= new AgenhModel();
				$agentobj 	= AgentModel::find($agentid);
				$face 		= '';
				$name 		= '';
				$num 		= $agentobj->num;
				
				//有选分组的
				if(!isempt($pnum)){
					$pmenuar	= $arobj->strtoarray($agentobj->pmenustr);
					$xuanza 	= array();
					foreach($pmenuar as $pmea){
						if($pmea[0]==$pnum){
							$xuanza = $pmea;
							break;
						}
					}
					if(!$xuanza)continue;//不存在就跳过
					$name = $xuanza[1];
					$face = $xuanza[2];
					$num .= '-'.$pnum.'';
				}
				
				$obj->cid = $cid;
				$obj->num = $num;
				$obj->pnum= $pnum;
				$obj->name= $name;
				$obj->sort= $agentobj->sort;
				$obj->atype 	= $agentobj->atype;
				$obj->atypes 	= $agentobj->atypes;//子分类
				$obj->agentid 	= $agentid;
				$obj->face 		= $face;
				$obj->issy 		= 5;
				$obj->yylx 		= 5;
				$obj->save();
				
				c('authorydata', $this->useainfo)->addauthory($obj->id, $obj->num, $agentobj->table);
			}
		}
		return returnsuccess();
	}
	
	/**
	*	应用删除
	*/
	public function postdelcheck(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('id empty');
		
		AgenhModel::find($id)->delete();
		return returnsuccess();
	}
	
	/**
	*	自建应用保存
	*/
	public function postsave(Request $request)
	{
		$id 	= (int)$request->input('id');
		$cid 	= $this->companyid; 
		$this->validate($request, [
            'name' 		=> 'required',
			'face'		=> 'required',
			'num'		=> 'required',
			'atype'		=> 'required'
        ]);
		
		
		$data 	= ($id > 0) ? AgenhModel::find($id) : new AgenhModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}
		
		if(AgenhModel::select()->where('id','<>', $id)->where('cid', $cid)->where('num', $request->num)->count()>0)return returnerrors('num', trans('table/agenh.num_unique'));
		
		
		$data->name 	= $request->name;
		$data->sort 	= (int)$request->input('sort',0);
		$data->status 	= (int)$request->input('status',0);
		$data->isflow 	= (int)$request->input('isflow',0);
		$data->yylx 	= (int)$request->input('yylx',0);
		$data->issy 	= (int)$request->input('issy',0);
		$data->face 	= $request->face;
		$data->atype 	= $request->atype;
		$data->description 	= nulltoempty($request->description);
		$data->usablename 	= nulltoempty($request->usablename);
		$data->usableid 	= nulltoempty($request->usableid);
		$data->summarx 		= nulltoempty($request->summarx);
		
		if($id==0)$data->cid = $cid;
		if($id==0 || $data->agentid==0){
			$data->urlm		= nulltoempty($request->urlm);
			$data->urlpc	= nulltoempty($request->urlpc);
			$data->num 		= $request->num;
		}
		
		$data->save();
		return returnsuccess();
	}
	
	
	
	/**
	*	保存单位应用菜单
	*/
	public function postmenusave(Request $request)
	{
		$cid 	= $this->companyid;
		$id 	= (int)$request->input('id');
		
		$this->validate($request, [
            'name' 		=> 'required',
			'type'		=> 'required'
        ]);
		
		
		$data 	= ($id > 0) ? AgenhmenuModel::find($id) : new AgenhmenuModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}
		
		$data->name 	= $request->input('name');
		$data->pid 		= (int)$request->input('pid','0');
	
		$data->num 		= nulltoempty($request->input('num'));
		
		$data->type 	= $request->input('type');
		$data->url 		= nulltoempty($request->input('url'));
		
		$data->receid	= nulltoempty($request->input('receid'));
		$data->recename	= nulltoempty($request->input('recename'));
		if(isempt($data->receid))$data->recename = '';
		
		$data->color 	= nulltoempty($request->input('color'));
		$data->status 	= (int)$request->input('status');
		$data->sort 	= (int)$request->input('sort');
		
		
		if($id==0){
			$data->cid = $cid;
			$data->agenhid 	= (int)$request->input('agenhid','0');
		}
		
		$data->save();
		return returnsuccess();
	}
	
	/**
	*	删除菜单
	*/
	public function postdelmenucheck(Request $request)
	{
		$cid 	= $this->companyid;
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('id empty');

		if(AgenhmenuModel::select()->where('pid', $id)->count()>0)return $this->returnerror(trans('table/agentmenu.delmsg'));
		
		$data 	 = AgenhmenuModel::find($id);
		$data->delete();
		return returnsuccess();
	}
	
	
	/**
	*	保存设置
	*/
	public function postcogsave(Request $request)
	{
		$cid 	= $this->companyid;
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('id empty');
		
		$data 	= AgenhModel::find($id);
		$data->fields_islu = nulltoempty($request->input('fields_islu'));
		$data->fields_islb = nulltoempty($request->input('fields_islb'));
		$data->fields_ispx = nulltoempty($request->input('fields_ispx'));
		$data->fields_isss = nulltoempty($request->input('fields_isss'));
		
		$data->save();
		return returnsuccess();
	}
	
	
	public function postcoursesave(Request $request)
	{
		$cid 	= $this->companyid;
		
		$id 	= (int)$request->input('id');
		
		$this->validate($request, [
            'name' 		=> 'required',
			'checktype'	=> 'required'
        ]);
		
		$data 	= ($id > 0) ? FlowcourseModel::find($id) : new FlowcourseModel();
		
		if($id>0){
			if(!$data || $data->cid != $cid)
			return returnerror(trans('validation.notextent').'1');//不是同一个单位
		}
		$agenhid		= (int)$request->agenhid;
		
		$data->name 	= $request->name;
		$data->num 		= nulltoempty($request->num);
		$data->sort 	= (int)$request->sort;
		$data->status 	= (int)$request->status;
		$data->agenhid 	= $agenhid;
		$data->pid 		= (int)$request->pid;
		$data->isqm 	= (int)$request->isqm;
		$data->iszb 	= (int)$request->iszb;
		$data->checksmlx 	= (int)$request->checksmlx;
		$data->checkwhere 	= nulltoempty($request->checkwhere);
		$data->recename 	= nulltoempty($request->recename);
		$data->receid 		= nulltoempty($request->receid);
		$data->checktype 	= nulltoempty($request->checktype);
		$data->checktypeid 	= nulltoempty($request->checktypeid);
		$data->checktypename= nulltoempty($request->checktypename);
		$data->checkfields	= nulltoempty($request->checkfields);
		$data->courseact	= nulltoempty($request->courseact);
		
		if($id==0)$data->cid = $cid;

		$data->save();
		
		$hrs = AgenhModel::find($agenhid);
		$this->getNei('flow')->pipeiall($cid, $hrs->num); //从新匹配流程
		
		return returnsuccess();
	}
	/**
	*	流程步骤删除
	*/
	public function postdelcoursecheck(Request $request)
	{
		$cid 	= $this->companyid;
		$id 	= (int)$request->input('id','0');
		if($id<=0)return returnerror('id empty');

		$data 	 = FlowcourseModel::find($id);
		$data->delete();
		
		return returnsuccess();
	}
	
	/**
	*	清空数据
	*/
	public function postcleardata(Request $request)
	{
		$agenhid 	= (int)$request->input('agenhid','0');
		
		$mingling 	= $request->input('mingling');
		if($mingling!='CLEAE')return returnerror('命令错误');
		
		$hrs		= AgenhModel::where('cid', $this->companyid)->where('id',$agenhid)->first();
		if(!$hrs)return returnerror('应用不存在');
		
		$agentrs 	= $hrs->sysAgent;
		if(!$agentrs)return returnerror('此应用不需要清空数据1');
		
		$mtable 	= $agentrs->table;
		if(isempt($mtable))return returnerror('此应用不需要清空数据2');
		
		$flow		= \Rock::getFlow($hrs->num, $this->useainfo);
		return $flow->delall();
	}
	
	/**
	*	更新记录
	*/
	public function postupdatezt(Request $request)
	{
		$sid 	= $request->input('sid');
		$lx 	= (int)$request->input('lx','0');
		$fid 	= $request->input('fid');
		AgenhModel::where('cid',$this->companyid)->whereIn('id', explode(',', $sid))->update([$fid=>$lx]);
		return returnsuccess();
	}
	
	/**
	*	读取主表字段
	*/
	public function getfield($request)
	{
		$agentid= (int)$request->input('agentid');
		$rows  	= AgentfieldsModel::where('agentid', $agentid)->where('iszb',0)->get();
		$arr 	= array();
		foreach($rows as $k=>$rs){
			$fieldstype	= $rs->fieldstype;
			if(in_array($fieldstype, array('changeuser','changeusercheck')) && !isempt($rs->data)){
				$arr[] = array(
					'name' => $rs->name.'('.$rs->data.')',
					'value' => $rs->data,
				);
			}
		}
		return returnsuccess($arr);
	}
	
	/**
	*	批量添加权限
	*/
	public function postaddqx($request)
	{
		$qxlx 		= explode(',', $request->input('qxlx'));
		$yyid 		= explode(',', $request->input('yyid'));
		$objectid 	= $request->input('sid');
		$objectname = $request->input('sna');
		
		foreach($qxlx as $atype){
			foreach($yyid as $agenhid){
				$data 			= new AuthoryModel();
				$data->cid 		= $this->companyid;
				$data->status 	= 1;
				$data->objectid 	= $objectid;
				$data->objectname 	= $objectname;
				$data->agenhid 	= $agenhid;
				$data->atype 	= $atype;
				$data->save();	
			}
		}
		return returnsuccess();
	}
	
	/**
	*	设置全部人都可以使用
	*/
	public function postsetalluser($request)
	{
		$yyid 		= explode(',', $request->input('yyid'));
		AgenhModel::where('cid', $this->companyid)->whereIn('id', $yyid)->update([
			'usablename' => '',
			'usableid' => '',
		]);
		return returnsuccess();
	}
	
	/**
	*	设置使用人
	*/
	public function postsetuser($request)
	{
		$yyid 		= explode(',', $request->input('yyid'));
		$usableid 	= $request->input('sid');
		$usablename = $request->input('sna');
		AgenhModel::where('cid', $this->companyid)->whereIn('id', $yyid)->update([
			'usablename' => $usablename,
			'usableid' => $usableid,
		]);
		return returnsuccess();
	}
}