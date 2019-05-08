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
		</div>
		<div class="col-md-4" align="right">
			<a href="{{ route('adminagenttodoedit', $agentid) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agenttodo.addtext') }}</a>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/agenttodo.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenttodo.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenttodo.changelx') }}</th>
			<th>{{ trans('table/agenttodo.todolx') }}</th>
			<th>{{ trans('table/agenttodo.todofields') }}</th>
			<th>{{ trans('table/agenttodo.wherestr') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenttodo.summary') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenttodo.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenttodo.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }} </td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="num">{{ $item->num }}</td>
			<td>{{ $item->changelx }} </td>
			<td>{{ $item->todolx }}</td>
			<td edata-fields="todofields">{{ $item->todofields }}</td>
			<td edata-fields="wherestr">{{ $item->wherestr }}</td>
			<td edata-fields="summary">{{ $item->summary }}</td>
			<td edata-fields="explain">{{ $item->explain }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
			<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			<td>
				<a href="{{ route('adminagenttodoedit', $agentid) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
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
		delurl:'{{ route('adminagenttododel') }}'
	});
}

function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};

		columns['num'] = {"name":"{{ trans('table/agenttodo.num') }}"};
		columns['name'] = {"name":"{{ trans('table/agenttodo.name') }}"};
		columns['todofields'] = {"name":"{{ trans('table/agenttodo.todofields') }}"};
		columns['wherestr'] = {"name":"{{ trans('table/agenttodo.wherestr') }}","type":"textarea"};
		columns['explain'] = {"name":"{{ trans('table/agenttodo.explain') }}","type":"textarea"};
		columns['summary'] = {"name":"{{ trans('table/agenttodo.summary') }}","type":"textarea"};
		
		columns['status']  	 = {"name":"{{ trans('table/agenttodo.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isbag']  	 = {"name":"{{ trans('table/agentmenu.isbag') }}","type":"checkbox",
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

</script>
@endsection