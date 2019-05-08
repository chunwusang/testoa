@extends('admin.public')

@section('content')
<div style="padding:0px 15px">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	
	<div class="row">
		<div class="col-md-8">
			<button type="button" onclick="js.back()" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i> {{ trans('base.back') }}</button>
			&nbsp;
			<button type="button" onclick="reloadxuhao()" class="btn btn-default">{{ trans('table/agentfields.reloadxu') }}</button>
		</div>
		<div class="col-md-4" align="right">
			<a href="{{ route('adminagentfieldsedit', $agentid) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('base.addtext') }}</a>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th></th>
			<th>ID</th>
			<th>{{ trans('table/agentfields.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.fields') }}</th>
			<th>{{ trans('table/agentfields.iszb') }}</th>
			<th>{{ trans('table/agentfields.fieldstype') }}</th>
			<th>{{ trans('table/agentfields.lengs') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.dev') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.placeholder') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.data') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.attr') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.islu') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.isbt') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.iszs') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.islb') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.ispx') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.isss') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.isdr') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.isml') }}<i class="glyphicon glyphicon-pencil"></i></th>
			
			<th>{{ trans('table/agentfields.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentfields.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>@if($item->iszb==0)<input value="{{ $item->id }}" checked name="boxname" type="checkbox">@endif</td>
			<td>{{ $item->id }} </td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td>{{ $item->fields }}</td>
			<td>@if($item->iszb>0){{ trans('table/agentfields.iszb'.$item->iszb.'') }}@endif</td>
			<td>{{ trans('table/agentfields.fieldstype_'.$item->fieldstype.'') }}({{ $item->fieldstype }})</td>
			
			<td edata-fields="lengs">{{ $item->lengs }}</td>
			<td edata-fields="dev">{{ $item->dev }}</td>
			<td edata-fields="placeholder">{{ $item->placeholder }}</td>
			<td edata-fields="data">{{ $item->data }}</td>
			<td edata-fields="attr">{{ $item->attr }}</td>
			
			<td edata-fields="islu" edata-value="{{ $item->islu }}"><img src="/images/checkbox{{ $item->islu }}.png" height="20"></td>
			<td edata-fields="isbt" edata-value="{{ $item->isbt }}"><img src="/images/checkbox{{ $item->isbt }}.png" height="20"></td>
			<td edata-fields="iszs" edata-value="{{ $item->iszs }}"><img src="/images/checkbox{{ $item->iszs }}.png" height="20"></td>
			<td edata-fields="islb" edata-value="{{ $item->islb }}"><img src="/images/checkbox{{ $item->islb }}.png" height="20"></td>
			<td edata-fields="ispx" edata-value="{{ $item->ispx }}"><img src="/images/checkbox{{ $item->ispx }}.png" height="20"></td>
			<td edata-fields="isss" edata-value="{{ $item->isss }}"><img src="/images/checkbox{{ $item->isss }}.png" height="20"></td>
			<td edata-fields="isdr" edata-value="{{ $item->isdr }}"><img src="/images/checkbox{{ $item->isdr }}.png" height="20"></td>
			<td edata-fields="isml" edata-value="{{ $item->isml }}"><img src="/images/checkbox{{ $item->isml }}.png" height="20"></td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}"><img src="/images/checkbox{{ $item->status }}.png" height="20"></td>
			<td>
				<a href="{{ route('adminagentfieldsedit', $agentid) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
			</td>
		</tr>
		@endforeach
	</table>
	
	
	
</div>
@endsection

@section('script')
<script>
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delparams:{agentid:{{ $agentid }}},
		delurl:'{{ route('adminagentfieldsdel') }}'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/agentfields.sort') }}({{ trans('table/agentfields.sort_msg') }})","type":"number"};
		columns['name'] = {"name":"{{ trans('table/agentfields.name') }}"};
		columns['dev'] = {"name":"{{ trans('table/agentfields.dev') }}"};
		columns['data'] = {"name":"{{ trans('table/agentfields.data') }}"};
		columns['placeholder'] = {"name":"{{ trans('table/agentfields.placeholder') }}"};
		columns['attr'] = {"name":"{{ trans('table/agentfields.attr') }}"};
		columns['lengs'] = {"name":"{{ trans('table/agentfields.lengs') }}","type":"number"};
		
		columns['status']  	 = {"name":"{{ trans('table/agentfields.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['islu']  	 = {"name":"{{ trans('table/agentfields.islu') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isbt']  	 = {"name":"{{ trans('table/agentfields.isbt') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['iszs']  	 = {"name":"{{ trans('table/agentfields.iszs') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isdr']  	 = {"name":"{{ trans('table/agentfields.isdr') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['islb']  	 = {"name":"{{ trans('table/agentfields.islb') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isss']  	 = {"name":"{{ trans('table/agentfields.isss') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['ispx']  	 = {"name":"{{ trans('table/agentfields.ispx') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isml']  	 = {"name":"{{ trans('table/agentfields.isml') }}","type":"checkbox",
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

function reloadxuhao(){
	var sid = js.getchecked('boxname');
	if(sid=='')return;
	var url = '/webapi/admin/agent_upxuhao';
	js.loading();
	js.ajax(url,{sid:sid},function(ret){
		js.msgok();
		js.reload();
	},'post', function(msg){
		js.msgerror(msg);
	});
}
</script>
@endsection