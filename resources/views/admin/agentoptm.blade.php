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
			<a href="{{ route('adminagentoptmedit', $agentid) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/flowmenu.addtext') }}</a>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/flowmenu.type') }}</th>
			
			<th>{{ trans('table/flowmenu.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.actname') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.statusname') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.statuscolor') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.statusvalue') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.wherestr') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.islog') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.issm') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.iszs') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/flowmenu.isup') }}</th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }} </td>
			<td>{{ trans('table/flowmenu.type'.$item->type.'') }}</td>
			<td edata-fields="num">{{ $item->num }}</td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="actname">{{ $item->actname }}</td>
			<td edata-fields="statusname">{{ $item->statusname }}</td>
			<td edata-fields="statuscolor">{{ $item->statuscolor }}</td>
			<td edata-fields="statusvalue">{{ $item->statusvalue }}</td>
			<td edata-fields="wherestr">{{ $item->wherestr }}</td>
	
			<td edata-fields="explain">{{ $item->explain }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}"><img src="/images/checkbox{{ $item->status }}.png" height="20"></td>
			<td edata-fields="islog" edata-value="{{ $item->islog }}"><img src="/images/checkbox{{ $item->islog }}.png" height="20"></td>
			<td edata-fields="issm" edata-value="{{ $item->issm }}"><img src="/images/checkbox{{ $item->issm }}.png" height="20"></td>
			<td edata-fields="iszs" edata-value="{{ $item->iszs }}"><img src="/images/checkbox{{ $item->iszs }}.png" height="20"></td>
			<td>{{ trans('table/flowmenu.isup'.$item->isup.'') }}</td>
			
			<td>
				<a href="{{ route('adminagentoptmedit', $agentid) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
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
		delurl:'{{ route('adminagentoptmdel') }}'
	});
}

function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};

		columns['num'] = {"name":"{{ trans('table/flowmenu.num') }}"};
		columns['name'] = {"name":"{{ trans('table/flowmenu.name') }}"};
		columns['actname'] = {"name":"{{ trans('table/flowmenu.actname') }}"};
		columns['statusname'] = {"name":"{{ trans('table/flowmenu.statusname') }}"};
		columns['statuscolor'] = {"name":"{{ trans('table/flowmenu.statuscolor') }}"};
		columns['statusvalue'] = {"name":"{{ trans('table/flowmenu.statusvalue') }}","type":"number"};
		columns['wherestr'] = {"name":"{{ trans('table/flowmenu.wherestr') }}","type":"textarea"};
		columns['explain'] = {"name":"{{ trans('table/flowmenu.explain') }}","type":"textarea"};
		columns['sort'] = {"name":"{{ trans('table/flowmenu.sort') }}({{ trans('table/flowmenu.sort_msg') }})","type":"number"};
		columns['status']  	 = {"name":"{{ trans('table/flowmenu.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['islog']  	 = {"name":"{{ trans('table/flowmenu.islog') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['iszs']  	 = {"name":"{{ trans('table/flowmenu.iszs') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['issm']  	 = {"name":"{{ trans('table/flowmenu.issm') }}","type":"checkbox",
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