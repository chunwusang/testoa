<?php
/**
*	系统后台应用管理
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Model\Base\AgentModel;
use App\Model\Base\AgentfieldsModel;
use App\Model\Base\AgentmenuModel;
use App\Model\Base\AgenttodoModel;
use App\Model\Base\FlowmenuModel;
use App\RainRock\Systems\Databeifen;


class AgentController extends AdminController
{
  
	/**
	*	应用列表
	*/
    public function index(Request $request)
    {
		// $bbb = 2222;
		// return $bbb;
		$this->getLimit();
		$obj 	= AgentModel::select();
		
		$key 	= trim($request->get('keyword'));
		if(!isempt($key)){
			$obj->where(function($query)use($key){
				$query->where('name','like',"%$key%");
				$query->oRwhere('atype','like',"%$key%");
				$query->oRwhere('num',$key);
				$query->oRwhere('table',$key);
			});
		}
		
		$total 	= $obj->count();
		$data 	= $obj->orderby('sort','asc')->simplePaginate($this->limit)->getCollection();
		
		foreach($data as $k=>$rs){
			$rs->atypeshow = $rs->atype;
			if(!isempt($rs->atypes))$rs->atype.='('.$rs->atypes.')';
			
			$data[$k]->menutotal 	= $rs->getMenu()->count();
			$data[$k]->fieldstotal 	= $rs->getFields()->count();
			$data[$k]->todototal 	= AgenttodoModel::where('agentid', $rs->id)->where('cid',0)->count();
			$data[$k]->optmtotal 	= FlowmenuModel::where('agentid', $rs->id)->where('cid',0)->count();
		}
		// $bb = [
		// 	'pagetitle' => trans('table/agent.pagetitle'),
		// 	'data' 		=> $data,
		// 	'atype' 	=> '',
		// 	'kzq'		=> 'Admin/Agent',
		// 	'lang'		=> 'table/agent',
		// 	'pager'		=> $this->getPager('adminagent', $total),
		// 	'mtable' 	=> c('rockjm')->encrypt('agent'),
		// ];
		// return $bb;die;
        return $this->getShowView('admin/agent', [
			'pagetitle' => trans('table/agent.pagetitle'),
			'data' 		=> $data,
			'atype' 	=> '',
			'kzq'		=> 'Admin/Agent',
			'lang'		=> 'table/agent',
			'pager'		=> $this->getPager('adminagent', $total),
			'mtable' 	=> c('rockjm')->encrypt('agent'),
		]);
    }
	
	/**
	*	应用编辑新增
	*/
	public function getForm($id=0,Request $request)
	{
	
		
		$formfields['face'] 	= ['required'=>'','type'=>'text'];//required   验证中的必填项
		
		$formfields['name'] 	= ['required'=>'','type'=>'text'];
		$formfields['num'] 		= ['required'=>'','type'=>'text'];
		$formfields['atype'] 	= ['required'=>'','type'=>'text'];
		$formfields['atypes'] 	= ['type'=>'text'];
		$formfields['description'] = ['type'=>'textarea'];
		
		$formfields['yylx'] 	= ['type'=>'select','value'=>0,'store'=>[[0,trans('table/agent.yylx0')],[1,trans('table/agent.yylx1')],[2,trans('table/agent.yylx2')],[3,trans('table/agent.yylx3')]]];
		
		$formfields['table'] 	= ['type'=>'text'];
		$formfields['sericnum'] = ['type'=>'text'];
		$formfields['mwhere'] 	= ['type'=>'text'];
		
		
		
		$formfields['urlm'] 	= ['type'=>'text'];
		$formfields['urlpc'] 	= ['type'=>'text'];
		$formfields['tables'] 	= ['type'=>'text'];
		$formfields['names'] 	= ['type'=>'text'];

		
		$formfields['summary'] 	= ['type'=>'textarea'];
		//$formfields['summarx'] 	= ['type'=>'textarea','attr'=>'style="height:100px"'];
		$formfields['pmenustr'] = ['type'=>'textarea','attr'=>'style="height:150px"'];
		
		$formfields['iscs'] 	= ['type'=>'select','value'=>0,'store'=>[[0,trans('table/agent.iscs0')],[1,trans('table/agent.iscs1')],[2,trans('table/agent.iscs2')]]];
		
		$formfields['zfeitime'] = ['type'=>'number','value'=>'0','tishi'=>trans('table/agent.zfeitime_msg')];
		
		$formfields['isup'] 	= ['type'=>'select','value'=>0,'store'=>[[0,trans('table/agent.isup0')],[1,trans('table/agent.isup1')],[2,trans('table/agent.isup2')]]];
		$formfields['uptype'] 	= ['type'=>'text'];
		
		$formfields['sort'] 	= ['type'=>'number','value'=>'0','tishi'=>trans('table/agent.sort_msg')];
		
		$data = AgentModel::find($id);
		
		foreach($formfields as $fid=>$rs){
			$formfields[$fid]['attr']	= arrvalue($rs,'attr');
			$formfields[$fid]['tishi']	= arrvalue($rs,'tishi');
		}
		
		if(!$data){
			$data	= new \StdClass();
			foreach($formfields as $fid=>$rs){
				$data->$fid 	= arrvalue($rs, 'value');
			}
			$data->facesrc = '/images/nologo.png';
			$data->id 	= 0;
			$data->status 	= 1;
			$data->istxset 	= 0;
			$data->ispl 	= 0;
			$data->islu 	= 1;
			$data->issy 	= 0;
		}else{
			$data->facesrc = $data->face;
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		return $this->getShowView('admin/agentedit', [
			'pagetitle' 	=> trans('table/agent.'.$ebts.''),
			'formfields'	=> $formfields,
			'data'			=> $data,
		]);
	}
	
	/**
	*	数据保存
	*/
	public function saveData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		$vis 	= ($id==0) ? '|unique:agent' : '';
		
		$this->validate($request, [
            'name' 		=> 'required',
			'num' 		=> 'required'.$vis.'',
			'atype' 	=> 'required',
			'face' 		=> 'required',
        ],[
			'num.unique' => trans('table/agent.num_unique')
		]);
		
		
		$data 	= ($id > 0) ? AgentModel::find($id) : new AgentModel();
		
		$data->name 	= $request->name;
		$data->num 		= $request->num;
		$data->atype 	= $request->atype;
		$data->atypes 	= nulltoempty($request->atypes);
		$data->description = nulltoempty($request->description);
		$data->table 	= nulltoempty($request->table);
		$data->face 	= $request->face;
		$data->urlm 	= nulltoempty($request->urlm);
		$data->urlpc 	= nulltoempty($request->urlpc);
		$data->tables 	= nulltoempty($request->tables);
		$data->names 	= nulltoempty($request->names);
		$data->sericnum = nulltoempty($request->sericnum);
		$data->sort 	= (int)$request->sort;
		$data->status 	= (int)$request->status;
		$data->mwhere 	= nulltoempty($request->mwhere);
		$data->yylx 	= (int)$request->yylx;
		$data->iscs 	= (int)$request->input('iscs');
		$data->zfeitime = (int)$request->input('zfeitime');
		$data->istxset  = (int)$request->input('istxset');
		$data->ispl 	= (int)$request->input('ispl');
		$data->islu 	= (int)$request->input('islu');
		$data->issy 	= (int)$request->input('issy');
		$data->isup 	= (int)$request->input('isup');
		$data->uptype 	= nulltoempty($request->uptype);
		$data->summary 	= nulltoempty($request->summary);
		$data->summarx 	= nulltoempty($request->summarx);
		$data->pmenustr	= nulltoempty($request->pmenustr);
		
		if($id==0)$data->type = 0;//系统应用
		
		$data->save();
		
		
		//创建模型
		$path = base_path('app/Model/Agent/Rockagent_'.$request->num.'.php');
		if(!file_exists($path) && !isempt($request->table)){
$str	= '<?php
/**
*	应用.'.$request->name.'
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：'.nowdt('date').'
*/

namespace App\Model\Agent;


class Rockagent_'.$request->num.'  extends Rockagent
{
	protected $table = \''.$request->table.'\';
}';			
			$bo = @file_put_contents($path, $str);
			if(!$bo)return '无法创建应用主表的Model';
		}
		
		//多行子表的
		$tables = $request->tables;
		if(!isempt($tables) && !isempt($request->table)){
			$tablesa = explode(',', $tables);
			$namesaa = explode(',', $request->names);
			foreach($tablesa as $k=>$tabs){
				$this->creaetaobsd($request->num, $tabs, $request->table, $request->name,arrvalue($namesaa, $k));
			}
		}
	}
	private function creaetaobsd($num, $tabs,$mtable, $name, $names)
	{
		$path = base_path('app/Model/Agent/Rockagents_'.$num.'_'.$tabs.'.php');
		if(!file_exists($path)){
$str	= '<?php
/**
*	应用.'.$name.'的'.$names.'子表
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：'.nowdt('date').'
*/

namespace App\Model\Agent;

class Rockagents_'.$num.'_'.$tabs.'  extends Rockagents
{
	protected $table = \''.$tabs.'\';
	
	protected $mtablename = \''.$mtable.'\';
}';		
			$bo = @file_put_contents($path, $str);
			if(!$bo)return '无法创建应用子表的Model';
		}
	}
	
	/**
	*	元素字段管理
	*/
	public function fieldsList($agentid)
	{
		$agent= AgentModel::find($agentid);
		$data = $agent->getFields()->orderBy('iszb')->orderBy('sort')->get();
		
		return $this->getShowView('admin/agentfields', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agentfields.pagetitle'),
			'data'			=> $data,
			'agentid'		=> $agentid,
			'mtable' 		=> c('rockjm')->encrypt('agentfields'),
		]);
	}
	
	/**
	*	元素录入 
	*/
	public function getFieldsForm($agentid, Request $request)
	{
		$id 	= (int)$request->get('id','0');
		$agent	= AgentModel::find($agentid);
		$agentd	= $agent->getFields()->get();
		
		$data 	= AgentfieldsModel::find($id);
		$tables = $agent->tables;
		$names  = $agent->names;
		$iszbarr[] = trans('table/agentfields.iszb0').'('.$agent->table.')';
		if(!isempt($tables)){
			$tablesa = explode(',', $tables);
			$namesa = explode(',', $names);
			foreach($tablesa as $k=>$tabs){
				$iszbarr[] = '第'.($k+1).'个子表['.arrvalue($namesa, $k).']('.$tabs.')';
			}
		}
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 	= 0;
			$data->agentid 	= $agentid;
			$data->name 	= '';
			$data->fields 	= '';
			$data->fieldstype 	= '';
			$data->fieldstext 	= '';
			$data->lengs 	= 0;
			$data->data 	= '';
			$data->placeholder 	= '';
			$data->dev 	= '';
			$data->sort 	= 0;
			$data->isbt 	= 0;
			$data->islu 	= 1;
			$data->iszs 	= 1;
			$data->islb 	= 1;
			$data->ispx 	= 1;
			$data->isss 	= 1;
			$data->isdr 	= 0;
			$data->status 	= 1;
			$data->explain 	= '';
			$data->attr 	= '';
			$data->iszb 	= 0; //0主表
			$data->width 	= '';
			$data->height 	= '';
			$data->gongsi 	= '';
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		$obj 	= new AgentfieldsModel();
		
		return $this->getShowView('admin/agentfieldsedit', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agentfields.'.$ebts.''),
			'data'			=> $data,
			'iszbarr'		=> $iszbarr,
			'fieldstype'	=> $obj->fieldstypeArr($data->fieldstype)
		]);
	}
	
	/**
	*	保存字段
	*/
	public function saveFieldsData(Request $request)
	{
		$id 	= (int)$request->input('id','0');//转换为int类型
		$autocj = (int)$request->input('autocj','0');
		
		$this->validate($request, [
            'name' 			=> 'required',
			'fields' 		=> 'required',
			'fieldstype' 	=> 'required',
			'agentid' 		=> 'required',
        ]);
		
		$data 	= ($id > 0) ? AgentfieldsModel::find($id) : new AgentfieldsModel();
		$agentid		= $request->input('agentid');
		$ofilesr		= false;
		if($id > 0)$ofilesr	= clone $data;//复制一个
		$iszb			= (int)$request->input('iszb');
		$fieldstype		= $request->input('fieldstype');
		
		$data->name 	= $request->input('name');
		$data->agentid 	= $agentid;
		$data->fields 	= $request->input('fields');
		$data->fieldstype 	= $fieldstype;
		$data->fieldstext 	= nulltoempty($request->input('fieldstext'));
		$data->placeholder 	= nulltoempty($request->input('placeholder'));
		$data->data 	= nulltoempty($request->input('data'));
		$data->dev 		= nulltoempty($request->input('dev'));
		$data->explain 	= nulltoempty($request->input('explain'));
		$data->attr 	= nulltoempty($request->input('attr'));
		$data->width 	= nulltoempty($request->input('width'));
		$data->height 	= nulltoempty($request->input('height'));
		$data->gongsi 	= nulltoempty($request->input('gongsi'));
		$data->lengs 	= (int)$request->input('lengs');
		$data->islu 	= (int)$request->input('islu');
		$data->isbt 	= (int)$request->input('isbt');
		$data->iszs 	= (int)$request->input('iszs');
		$data->iszb 	= $iszb;
		$data->islb 	= (int)$request->input('islb');
		$data->ispx 	= (int)$request->input('ispx');
		$data->isss 	= (int)$request->input('isss');
		$data->isdr 	= (int)$request->input('isdr');
		$data->status 	= (int)$request->input('status');
		$data->sort 	= (int)$request->input('sort');

		$data->save();
		
		if($autocj==1 && $fieldstype!='subtable'){
			$obj = new AgentfieldsModel();
			$obj->createFields($agentid, $data->id, $ofilesr, $iszb);
		}
	}
	
	/**
	*	删除字段
	*/
	public function delFieldsChange(Request $request)
	{
		$id 	 = (int)$request->input('id','0');
		$agentid = (int)$request->input('agentid','0');
		$data 	 = AgentfieldsModel::find($id);
		$data->delete();
	}
	
	
	
	
	
	
	
	
	/**
	*	菜单管理
	*/
	public function menuList($agentid)
	{
		$agent= AgentModel::find($agentid);
		$data = $agent->getMenuArr($agentid);
		// $bb = 22;
		// return $bb;
		return $this->getShowView('admin/agentmenu', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agentmenu.pagetitle'),
			'data'			=> $data,
			'agentid'		=> $agentid,
			'mtable' 		=> c('rockjm')->encrypt('agentmenu'),
		]);
	}
	
	/**
	*	菜单录入 
	*/
	public function getMenuForm($agentid, Request $request)
	{
		$id 	= (int)$request->get('id','0');
		$agent	= AgentModel::find($agentid);
		
		$data 	= AgentmenuModel::find($id);
		
		if(!$data){
			$data	= new \StdClass();
			$data->id 		= 0;
			$data->agentid 	= $agentid;
			$data->name 	= '';
			$data->dev 		= '';
			$data->sort 	= 0;
			$data->isbag 	= 0;
			$data->status 	= 1;
			$data->type 	= 'auto';
			$data->num 		= '';
			$data->pnum 	= '';
			$data->url 		= '';
			$data->color 	= '';
			$data->wherestr = '';
			$data->explain 	= '';
			$data->isturn 	= 0;
			$data->iswzf 	= 0;
			$data->pid 		= (int)$request->get('pid',0);
			
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		$obj 	= new AgentmenuModel();
		
		return $this->getShowView('admin/agentmenuedit', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agentmenu.'.$ebts.''),
			'data'			=> $data,
			'fieldstype'	=> $obj->typeArr($data->type),
			'pnumstore'		=> c('array')->strtoarray($agent->pmenustr)
		]);
	}
	
	/**
	*	保存菜单
	*/
	public function saveMenuData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		$this->validate($request, [
            'name' 			=> 'required',
			'agentid' 		=> 'required',
        ]);
		
		$data 	= ($id > 0) ? AgentmenuModel::find($id) : new AgentmenuModel();
		$agentid		= $request->input('agentid');
		$data->name 	= $request->input('name');
		$data->pid 		= (int)$request->input('pid','0');
		$data->agentid 	= $agentid;
		
		$data->num 		= nulltoempty($request->input('num'));
		$data->pnum 	= nulltoempty($request->input('pnum'));
		
		$data->type 	= $request->input('type');
		$data->url 		= nulltoempty($request->input('url'));
		$data->color 	= nulltoempty($request->input('color'));
		$data->wherestr = nulltoempty($request->input('wherestr'));
		$data->explain  = nulltoempty($request->input('explain'));
		$data->status 	= (int)$request->input('status');
		$data->sort 	= (int)$request->input('sort');
		$data->isbag 	= (int)$request->input('isbag');
		$data->isturn 	= (int)$request->input('isturn');
		$data->iswzf 	= (int)$request->input('iswzf');

		$data->save();
	}
	
	/**
	*	删除菜单
	*/
	public function delMenuChange(Request $request)
	{
		$id 	 = (int)$request->input('id','0');
		$agentid = (int)$request->input('agentid','0');
		if(AgentmenuModel::select()->where('pid', $id)->count()>0)return $this->returnerror(trans('table/agentmenu.delmsg'));//表明有下级菜单不能删除
		$data 	 = AgentmenuModel::find($id);
		$data->delete();
	}
	
	
	
	
	
	
	
	/**
	*	单据通知管理
	*/
	private $changearr	= ['boturn','boedit','bodel','bozuofei','boping','botong','bobutong','bofinish','botask'];
	private $todoarr	= ['toturn','toapply','tocourse','tosuper','tosuperall'];
	private $tasktypea	= ['h','d','m'];
	public function todoList($agentid)
	{
		$agent= AgentModel::find($agentid);
		$data = AgenttodoModel::where('agentid', $agentid)->where('cid', 0)->get();
		foreach($data as $k=>$rs){
			$changelx 	= '';
			$todolx  	= '';
			foreach($this->changearr as $vv){
				if($rs->$vv==1)$changelx.=''.trans('table/agenttodo.changelx_'.$vv.'').';';
			}
			foreach($this->todoarr as $vv){
				if($rs->$vv==1)$todolx.=''.trans('table/agenttodo.todolx_'.$vv.'').';';
			}
			$data[$k]->changelx = $changelx;
			$data[$k]->todolx   = $todolx;
		}
		
		return $this->getShowView('admin/agenttodo', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agenttodo.pagetitle'),
			'data'			=> $data,
			'agentid'		=> $agentid,
			'mtable' 		=> c('rockjm')->encrypt('agenttodo'),
		]);
	}
	
	/**
	*	菜单录入 
	*/
	public function getTodoForm($agentid, Request $request)
	{
		$id 	= (int)$request->get('id','0');
		$agent	= AgentModel::find($agentid);
		
		$data 	= AgenttodoModel::find($id);
		
		$changearr= $this->changearr;
		
		if(!$data){
			$data	= new \StdClass();//
			$data->id 		= 0;
			$data->agentid 	= $agentid;
			$data->name 	= '';
			$data->status 	= 1;
			$data->num 		= '';
			$data->wherestr = '';
			$data->summary = '';
			$data->explain = '';
			$data->todofields = '';
			$data->tasktype = '';
			$data->tasktime = '';

			
			foreach($changearr as $vv)$data->$vv = 0;
			
			foreach($this->todoarr as $vv)$data->$vv = 0;
			
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		
		$obj 	= new AgenttodoModel();
		
		return $this->getShowView('admin/agenttodoedit', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agenttodo.'.$ebts.''),
			'data'			=> $data,
			'changearr'		=> $changearr,
			'todoarr'		=> $this->todoarr,
			'tasktypea'		=> $this->tasktypea,
		]);
	}
	
	/**
	*	保存菜单
	*/
	public function saveTodoData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		$this->validate($request, [
			'agentid' 		=> 'required',
        ]);
		
		$data 	= ($id > 0) ? AgenttodoModel::find($id) : new AgenttodoModel();
		$agentid		= $request->input('agentid');
		$data->name 	= $request->input('name');
		$data->agentid 	= $agentid;
		
		$data->num 		= nulltoempty($request->input('num'));
		$data->wherestr = nulltoempty($request->input('wherestr'));
		$data->summary 	= nulltoempty($request->input('summary'));
		$data->explain  = nulltoempty($request->input('explain'));
		$data->todofields  = nulltoempty($request->input('todofields'));
		$data->status 	= (int)$request->input('status');

		$changearr= $this->changearr;
		foreach($changearr as $vv)$data->$vv = (int)$request->input($vv);
		
		foreach($this->todoarr as $vv)$data->$vv = (int)$request->input($vv);
		
		if($data->botask==1){
			$data->tasktype = nulltoempty($request->input('tasktype'));
			$data->tasktime = nulltoempty($request->input('tasktime'));
		}else{
			$data->tasktype = '';
			$data->tasktime = '';
		}

		$data->save();
	}
	
	/**
	*	删除字段
	*/
	public function delTodoChange(Request $request)
	{
		$id 	 = (int)$request->input('id','0');
		$data 	 = AgenttodoModel::find($id);
		$data->delete();
	}
	
	
	public function optmenuList($agentid)
	{
		$agent= AgentModel::find($agentid);
		$data = FlowmenuModel::where('agentid', $agentid)->orderBy('sort')->get();
		
		return $this->getShowView('admin/agentoptm', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/flowmenu.pagetitle'),
			'data'			=> $data,
			'agentid'		=> $agentid,
			'mtable' 		=> c('rockjm')->encrypt('flowmenu'),
		]);
	}
	
	public function getoptMenuForm($agentid, Request $request)
	{
		$id 	= (int)$request->get('id','0');
		$agent	= AgentModel::find($agentid);
		
		
		$formfields['type'] 	= ['type'=>'select','value'=>0,'store'=>[[0,trans('table/flowmenu.type0')],[1,trans('table/flowmenu.type1')],[2,trans('table/flowmenu.type2')],[3,trans('table/flowmenu.type3')],[4,trans('table/flowmenu.type4')]]];
	
		
		$formfields['name'] 		= ['required'=>'','type'=>'text'];
		$formfields['actname'] 		= ['type'=>'text'];
		$formfields['num'] 			= ['type'=>'text'];
		$formfields['statusname'] 		= ['type'=>'text'];
		$formfields['statuscolor'] 		= ['type'=>'text'];
		$formfields['statusvalue'] 		= ['type'=>'number','value'=>'0','tishi'=>trans('table/flowmenu.statusvalue_msg')];
		
		$formfields['wherestr']  = ['type'=>'textarea'];
		$formfields['upgcont'] 	 = ['type'=>'textarea','attr'=>'style="height:100px"','tishi'=>trans('table/flowmenu.upgcont_tishi')];
		$formfields['explain']   = ['type'=>'textarea'];
		
		
		$formfields['isup'] 	 = ['type'=>'select','value'=>0,'store'=>[[0,trans('table/flowmenu.isup0')],[1,trans('table/flowmenu.isup1')],[2,trans('table/flowmenu.isup2')]]];
		
		$formfields['sort'] 	 = ['type'=>'number','value'=>'0','tishi'=>trans('table/flowmenu.sort_msg')];
		
		$data = FlowmenuModel::find($id);
		
		foreach($formfields as $fid=>$rs){
			$formfields[$fid]['attr']	= arrvalue($rs,'attr');
			$formfields[$fid]['tishi']	= arrvalue($rs,'tishi');
		}
		
		if(!$data){
			$data	= new \StdClass();
			foreach($formfields as $fid=>$rs){
				$data->$fid 	= arrvalue($rs, 'value');
			}
			$data->id 	= 0;
			$data->status 	= 1;
			$data->issm 	= 1;
			$data->islog 	= 1;
			$data->iszs 	= 0;
			$data->agentid 	= $agentid;
		}else{
			
		}
		
		$ebts	= ($data->id==0) ? 'addtext' : 'edittext';
		return $this->getShowView('admin/agentoptmedit', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/flowmenu.'.$ebts.''),
			'formfields'	=> $formfields,
			'data'			=> $data,
		]);
	}
	
	/**
	*	保存操作菜单
	*/
	public function saveOptmenuData(Request $request)
	{
		$id 	= (int)$request->input('id','0');
		
		$this->validate($request, [
            'name' 			=> 'required',
			'agentid' 		=> 'required',
        ]);
		
		$data 	= ($id > 0) ? FlowmenuModel::find($id) : new FlowmenuModel();
		$agentid		= $request->input('agentid');
		$data->type 	= (int)$request->input('type');
		$data->name 	= $request->input('name');
		$data->actname 	= nulltoempty($request->input('actname'));
		$data->agentid 	= $request->input('agentid');
		
		$data->num 		= nulltoempty($request->input('num'));
		$data->statusname 		= nulltoempty($request->input('statusname'));
		$data->statuscolor 		= nulltoempty($request->input('statuscolor'));
		$data->statusvalue 		= (int)$request->input('statusvalue');

		$data->upgcont  = nulltoempty($request->input('upgcont'));
		$data->wherestr = nulltoempty($request->input('wherestr'));
		$data->explain  = nulltoempty($request->input('explain'));
		$data->status 	= (int)$request->input('status');
		$data->sort 	= (int)$request->input('sort');
		$data->issm 	= (int)$request->input('issm');
		$data->isup 	= (int)$request->input('isup');
		$data->iszs 	= (int)$request->input('iszs');
		$data->islog 	= (int)$request->input('islog');

		$data->save();
	}
	
	

	public function getBujuForm($agentid, Request $request)//布局
	{
		$lx = (int)$request->get('lx','0');
		$agent		= AgentModel::find($agentid);
		$content	= '';
		$patsh		= 'resources/views/web/detail/'.$agent->num.'_input.blade.php';
		$jspath		= '/res/agent/userinfo_input.js';
		if($lx==1){
			$jspath		= '/res/agent/userinfo_detail.js';
			$patsh = 'resources/views/web/detail/'.$agent->num.'.blade.php';
		}
		
		
		$path		= base_path($patsh);
		if(file_exists($path))$content = file_get_contents($path);
		
		$fieldsarr 	= AgentModel::find($agentid)
					->getFields()
					->where('iszb', 0)
					->orderBy('iszb')
					->orderBy('sort')
					->get();
		
		return $this->getShowView('admin/agentbuju', [
			'pagetitle' 	=> '['.$agent->name.']'.trans('table/agent.lurubuju'.$lx.'').trans('table/agent.bujuguan'),
			'fieldsarr'	=> $fieldsarr,
			'agentinfo'	=> $agent,
			'jspath'		=> $jspath,
			'patsh'		=> $patsh,
			'lx'		=> $lx,
			'content'		=> [$content],
		]);
	}
}
