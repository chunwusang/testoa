@extends('manage.public')

@section('content')
<div style="padding:0px 15px">
    <div>
		<h3>{{ trans('table/agenh.pagetitle') }}</h3>
		<div>{{ trans('table/agenh.pagedesc') }}</div>
		
		<hr class="head-hr" />

	</div>
	
	<div style="margin-top:10px">
		<table width="100%">
		<tr>
			<td style="padding-right:10px">

				<div class="btn-group">	
				<button type="button" id="dropMenuid8" data-toggle="dropdown" class="btn btn-default dropdown-toggle ">选中的应用操作 <span class="caret"></span></button>
				<ul class="dropdown-menu" aria-labelledby="dropMenuid8">
				
				<li><a href="javascript:;" onclick="addquanxian()">添加权限</a></li>
				<li><a href="javascript:;" onclick="setquanti()">设置可使用对象..</a></li>
				<li><a href="javascript:;" onclick="editquanti()">让全体人员都可以用..</a></li>
				@foreach($fielpda as $sid)
				@if($sid!='status')<li role="separator" class="divider"></li>@endif
				<li><a href="javascript:;" onclick="caozpot('{{ $sid }}',1)">{{ trans('table/agenh.'.$sid.'') }}({{ trans('table/agenh.'.$sid.'1') }})</a></li>
				<li><a href="javascript:;" onclick="caozpot('{{ $sid }}',0)">{{ trans('table/agenh.'.$sid.'') }}({{ trans('table/agenh.'.$sid.'0') }})</a></li>
				
				@endforeach
				
				
			
				</ul>	
				</div>
				
			</td>

			<td nowrap>
			<form  class="form-inline" role="form">
				<div class="form-group">
					<input class="form-control" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('table/agenh.keyword') }}">
				</div>
				<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
			</form>
			</td>
			<td width="100%"></td>
			<td nowrap>
			<a href="javascript:;" onclick="addsysagent()" class="btn btn-info">{{ trans('table/agenh.adstext') }}</a>&nbsp; 
			<a href="{{ route('manage',[$cnum,'agenh_edit']) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agenh.addtext') }}</a>
			</td>
		</tr>
		</table>
		
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th><input type="checkbox" onclick="js.selall(this, 'selid')"></th>
			<th>ID</th>
			
			<th>{{ trans('table/agenh.face') }}</th>
			<th>{{ trans('table/agenh.name') }}</th>
			<th>{{ trans('table/agenh.num') }}</th>
			<th>{{ trans('table/agenh.atype') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenh.atypes') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenh.yylx') }}</th>
			<th>{{ trans('table/agenh.issy') }}</th>
			<th>{{ trans('table/agenh.usablename') }}</th>
			<th>{{ trans('table/agenh.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenh.leixing') }}</th>

			@foreach($fielpda as $sid)
			<th>{{ trans('table/agenh.'.$sid.'') }}<i class="glyphicon glyphicon-pencil"></i></th>
			@endforeach
			<th>{{ trans('table/agenh.isflow') }}</th>
			<th></th>
		</tr>
		@foreach($bdata as $atype=>$data)
		<tr>
			<td colspan="30"><b>{{ $atype }}</b>({{ count($data) }})</td>
		</tr>
		@foreach($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td><input type="checkbox" name="selid" value="{{ $item->id }}"></td>
			<td>{{ $item->id }}</td>
			<td><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($item->face) }}" width="50" height="50"></td>
			<td>{{ $item->name }}</td>
			<td>{{ $item->num }}</td>
			<td edata-fields="atype">{{ $item->atypeshow }}</td>
			<td edata-fields="atypes">{{ $item->atypes }}</td>
			<td>{{ trans('table/agenh.yylx'.$item->yylx.'') }}</td>
			<td>{{ trans('table/agenh.issy'.$item->issy.'') }}</td>
			<td>{{ $item->usablename }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td>
			@if ($item->agentid>0)
			<span class="label label-danger">{{ trans('table/agenh.leixing1') }}</span>
			@else
			<span class="label label-default">{{ trans('table/agenh.leixing0') }}</span>
			@endif
			</td>
			
			@foreach($fielpda as $sid)
			<td edata-fields="{{ $sid }}" edata-value="{{ $item->$sid }}">
			<img src="/images/checkbox{{ $item->$sid }}.png" height="20">
			</td>
			@endforeach
			<td>@if($item->isflow>0){{ trans('table/agenh.isflow'.$item->isflow.'') }}@endif</td>
			<td>
				<a href="{{ route('manage',[$cnum,'agenh_edit']) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
				@if($item->agentid>0)
					@if($item->isflow>0)
				<a href="{{ route('manage',[$cnum,'agenh_course']) }}?agenhid={{ $item->id }}">{{ trans('table/agenh.flowtext') }}({{ $item->coursetotal }})</a>
					@endif
				<a href="">{{ trans('table/agenh.menutext') }}({{ $item->menutotal }})</a>
				@endif
				<a href="{{ $item->agenhurlm }}" target="_blank">{{ trans('table/agenh.yulatext') }}</a>
				@if($item->agentid>0)
					<br>
					<a href="{{ route('list',[$companyinfo->num, $item->num]) }}" target="_blank">{{ trans('base.viewtext') }}</a>
				
					@if(!isempt($item->sysAgent->table) && $item->sysAgent->num!='flow')
					<a href="javascript:;" onclick="onclearall({{ $item->id }})">{{ trans('table/agenh.clearall') }}
					@endif
					<a title="{{ trans('table/agenh.cogtitle') }}" href="{{ route('manage',[$cnum,'agenh_cog']) }}?agenhid={{ $item->id }}"><i class="glyphicon glyphicon-cog"></i></a>
				@endif
				@if($iswxqy==1)
					<br><a href="javascript:;" onclick="createxiyy('{{ $item->id }}','{{ $item->name }}')">{{ trans('table/agenh.creaewxyingy') }}</a>
				@endif
			</td>
		</tr>
		@endforeach
		@endforeach
	</table>
	
	@include('layouts.pager')	
	
</div>
@endsection

@section('script')
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delparams:{cid:{{ $cid }}},
		delurl:'/api/unit/'+cnum+'/agenh_delcheck'
	});
}
function createxiyy(id,name){
	var url = '/manage/'+cnum+'/wxqy_agent';
	js.setoption('createwxagent',''+id+','+name+'');
	js.location(url);
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/agenh.sort') }}({{ trans('table/agenh.sort_msg') }})","type":"number"};
		columns['atype'] = {"name":"{{ trans('table/agenh.atype') }}"};
		columns['atypes'] = {"name":"{{ trans('table/agenh.atypes') }}"};
		@foreach($fielpda as $sid)
		columns['{{ $sid }}']  	 = {"name":"{{ trans('table/agenh.'.$sid.'') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		@endforeach
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
}
function caozpot(fid,lx){
	var sid = js.getchecked('selid');
	if(sid==''){
		js.msgerror('没有选择记录');
		return;
	}
	js.loading();
	js.ajax('/api/unit/'+cnum+'/agenh_updatezt', {sid:sid,fid:fid,lx:lx}, function(ret){
		js.msgok();
		js.reload();
	},'post');
}

function editquanti(){
	var yyid = js.getchecked('selid');
	if(yyid==''){
		js.msgerror('没有选择记录');
		return;
	}
	js.confirm('确定要将选中的应用的可使用对象改为全体人员吗？', function(jg){
		if(jg=='yes'){
			js.loading();
			js.ajax('/api/unit/'+cnum+'/agenh_setalluser', {yyid:yyid}, function(ret){
				js.msgok();
				js.reload();
			},'post');
		}
	});
}

function setquanti(){
	var yyid = js.getchecked('selid');
	if(yyid==''){
		js.msgerror('没有选择记录');
		return;
	}
	
	$.rockmodeuser({
		changetype:'checkdeptusercheck',
		title:'要给谁使用',
		onselect:function(sna,sid){
			if(sid){
				js.loading();
				js.ajax('/api/unit/'+cnum+'/agenh_setuser', {sid:sid,sna:sna,yyid:yyid}, function(ret){
					js.msgok();
					js.reload();
				},'post');
			}
		}
	});
}

function addquanxian(){
	var yyid = js.getchecked('selid');
	if(yyid==''){
		js.msgerror('没有选择记录');
		return;
	}
	js.confirm('权限类型：<label><input type="checkbox" name="qxtype" value="2">查看全部数据</label>&nbsp;<label><input type="checkbox" name="qxtype" value="3">新增</label>&nbsp;<label><input type="checkbox" name="qxtype" value="4">编辑</label>&nbsp;<label><input type="checkbox" name="qxtype" value="5">删除</label>&nbsp;<label><input type="checkbox" name="qxtype" value="6">导入</label>&nbsp;<label><input type="checkbox" name="qxtype" value="7">导出</label>',function(jg){
		if(jg=='yes'){
			var qxlx = js.getchecked('qxtype');
			if(qxlx==''){
				js.msgerror('没有选择权限类型');
				return;
			}
			
			setTimeout(function(){
				
				$.rockmodeuser({
					changetype:'checkdeptusercheck',
					title:'要给谁添加权限',
					onselect:function(sna,sid){
						if(sid){
							js.loading();
							js.ajax('/api/unit/'+cnum+'/agenh_addqx', {sid:sid,sna:sna,qxlx:qxlx,yyid:yyid}, function(ret){
								js.msgok();
							},'post');
						}
					}
				});
				
			}, 500)
		}
	});
}

function addsysagent(){
	var objs = $.rockmodel({
		'loadurl':'/api/unit/'+cnum+'/agenh_getsysagent',
		'title':'{{ trans('table/agenh.adstext') }}',
		'type':2,
		'width':'800px','bodyheight':'450px',
		'onloadsuccess':function(data){
			var atl = '';
			var s = '';
			for(var i=0;i<data.length;i++){
				if(atl!=data[i].atype){
					atl=data[i].atype;
					if(i>0)s+='</div>';
					s+='<div style="padding-left:20px"><b>'+atl+'</b></div><div class="row">';
				}
				s+='<div class="col-md-2" align="center">';
				s+='<div><img width="50" style="background:white;border:1px #dddddd solid;border-radius:10px" src="'+data[i].face+'"></div>';
				s+='<div><label><input type="checkbox" value="'+data[i].id+'|'+data[i].pnum+'" name="changeagent">'+data[i].name+'</label></div>';
				s+='</div>';
			}
			s+='</div>';
			if(data.length==0)s='{{ trans('table/agenh.notsys') }}';
			this.setbody(s);
		},
		'onok':function(){
			var sid = js.getchecked('changeagent');
			if(sid){
				js.ajax('/api/unit/'+cnum+'/agenh_addsave', {agentids:sid}, function(ret){
					location.reload();
				},'post',false,'{{ trans('base.chultext') }}');
			}
			return true;
		}
	});
}

function onclearall(hid){
	$.rockmodelprompt('{{ trans('table/agenh.clearall') }}','{{ trans('table/agenh.clearall_msg') }}', function(jg,txt){
		if(jg=='yes' && txt=='CLEAE'){
			$.rockmodelmsg('wait');
			js.ajax('/api/unit/'+cnum+'/agenh_cleardata', {agenhid:hid,mingling:txt}, function(ret){
				$.rockmodelmsg('ok');
			},'post',function(msg){
				$.rockmodelmsg('msg', msg);
			});
		}
	});
}
</script>
@endsection