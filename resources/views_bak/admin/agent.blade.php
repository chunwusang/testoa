@extends('admin.public')

@section('content')
<div style="padding:0px 15px">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	<div>
	<table width="100%">
	<tr>
		<td style="padding-right:10px">
			 <button type="button" onclick="onclickopt('beifen')" class="btn btn-default" >{{ trans('table/agent.benfen') }}</button>
		</td>
		<td style="padding-right:10px">
			 <button type="button" onclick="ondaoru()" class="btn btn-default" >{{ trans('table/agent.daoru') }}</button>
		</td>
		@if(config('rock.systype')=='dev')
		<td style="padding-right:10px">
			 <button type="button" onclick="onclickopt('creates')" class="btn btn-default" >{{ trans('table/agent.creates') }}</button>
		</td>
		@endif
	
		<td width="100%">
			<form  class="form-inline" role="form">
				<div class="form-group">
					<input class="form-control" style="width:130px" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('table/agent.keyword') }}">
				</div>
				<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
			</form>
		</td>
		<td align="right" nowrap>
			<a href="{{ route('adminanstall') }}" class="btn btn-success"><i class="glyphicon glyphicon-th-large"></i> {{ trans('table/agent.anstall') }}</a>&nbsp;
			<a href="{{ route('adminagentedit') }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agent.addtext') }}</a>
		</td>
	</tr>
	</table>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th><input onclick="js.selall(this,'boxname')" type="checkbox"></th>
			<th>ID</th>
			<th>{{ trans('table/agent.face') }}</th>
			<th>{{ trans('table/agent.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.num') }}</th>
			<th>{{ trans('table/agent.atype') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.atypes') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.yylx') }}</th>
			<th>{{ trans('table/agent.table') }}</th>
		
			<th>{{ trans('table/agent.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.type') }}</th>
			<th>{{ trans('table/agent.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			
			<th>{{ trans('table/agent.istxset') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.ispl') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.islu') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agent.issy') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
			<th>{{ trans('table/agent.bujuguan') }}</th>
		</tr>
		@foreach ($data as $item)
		@if($item->atype!=$atype)
		<tr><td colspan="30"><b>{{$item->atype}}</b></td></tr>
		@endif	
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td><input value="{{ $item->id }}" name="boxname" type="checkbox"></td>
			<td>{{ $item->id }}</td>
			<td><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($item->face) }}" width="50"></td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td>{{ $item->num }}</td>
			<td edata-fields="atype">{{ $item->atypeshow }}</td>
			<td edata-fields="atypes">{{ $item->atypes }}</td>
			<td>{{ trans('table/agent.yylx'.$item->yylx.'') }}</td>
			<td>{{ $item->table }}</td>
			
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td>
			@if ($item->type==1)
			<span class="label label-default">{{ trans('table/agent.type1') }}</span>
			@else
			<span class="label label-danger">{{ trans('table/agent.type0') }}</span>
			@endif
			</td>
			<td edata-fields="status" edata-value="{{ $item->status }}"><img src="/images/checkbox{{ $item->status }}.png" height="20"></td>
			<td edata-fields="istxset" edata-value="{{ $item->istxset }}"><img src="/images/checkbox{{ $item->istxset }}.png" height="20"></td>
			<td edata-fields="ispl" edata-value="{{ $item->ispl }}"><img src="/images/checkbox{{ $item->ispl }}.png" height="20"></td>
			<td edata-fields="islu" edata-value="{{ $item->islu }}"><img src="/images/checkbox{{ $item->islu }}.png" height="20"></td>
			<td edata-fields="issy" edata-value="{{ $item->issy }}"><img src="/images/checkbox{{ $item->issy }}.png" height="20"></td>

			<td>
				<a href="{{ route('adminagentedit', $item->id) }}">{{ trans('table/agent.edittxt') }}</a>
				<a href="{{ route('adminagentfields', $item->id) }}">{{ trans('table/agent.fieldstxt') }}({{ $item->fieldstotal }})</a><br>
				<a href="{{ route('adminagentmenu', $item->id) }}">{{ trans('table/agent.listmenu') }}({{ $item->menutotal }})</a><br>
				<a href="{{ route('adminagentoptm', $item->id) }}">{{ trans('table/agent.optmenu') }}({{ $item->optmtotal }})</a>
				<br>
				<a href="{{ route('adminagenttodo', $item->id) }}">{{ trans('table/agent.todotxt') }}({{ $item->todototal }})</a><br>
			</td>
			<td>
				@if($item->table)
				<a href="javascript:;" onclick="bujushez({{ $item->id }},0)">{{ trans('table/agent.lurubuju0') }}</a><br>
				<a href="javascript:;" onclick="bujushez({{ $item->id }},1)">{{ trans('table/agent.lurubuju1') }}</a>
				@endif
			</td>
		</tr>
		<?php $atype=$item->atype;?>
		@endforeach
	</table>
	
	@include('layouts.pager')
	
</div>
@endsection

@section('script')
<script>
function ondaoru(){
	$.rockmodelconfirm('{{ trans('table/agent.daorumsg') }}', function(jg){
		if(jg=='yes')onclickopt('daoru');
	});
}

function onclickopt(lx){
	var url = '/webapi/admin/agent_'+lx+'';
	js.loading();
	js.ajax(url,{},function(ret){
		js.msgok(ret.data);
		setTimeout(function(){
			if(lx=='daoru')js.reload();
		},1000);
	},'get', function(msg){
		js.msgerror(msg);
	});
}


function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/agent.sort') }}({{ trans('table/agent.sort_msg') }})","type":"number"};
		columns['name'] = {"name":"{{ trans('table/agent.name') }}"};
		columns['atype'] = {"name":"{{ trans('table/agent.atype') }}"};
		columns['atypes'] = {"name":"{{ trans('table/agent.atypes') }}"};
		
		columns['status']  	 = {"name":"{{ trans('table/agent.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['istxset']  	 = {"name":"{{ trans('table/agent.istxset') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['ispl']  	 = {"name":"{{ trans('table/agent.ispl') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['islu']  	 = {"name":"{{ trans('table/agent.islu') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['issy']  	 = {"name":"{{ trans('table/agent.issy') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'saveurl':'1',
			'mtable':'{{ $mtable }}'
		});
	});
}
function bujushez(id, lx){
	var url = '{{ route('adminagentbuju','') }}/'+id+'?lx='+lx+'';
	js.open(url, 970,550);
}
</script>
@endsection