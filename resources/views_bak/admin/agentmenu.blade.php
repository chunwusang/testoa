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
			<a href="{{ route('adminagentmenuedit', $agentid) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agentmenu.adttext') }}</a>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/agentmenu.name') }}</th>
			<th>{{ trans('table/agentmenu.pnum') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.pid') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.type') }}</th>
			<th>{{ trans('table/agentmenu.url') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.wherestr') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.isturn') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.iswzf') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.color') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.isbag') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agentmenu.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }} </td>
			<td>
			@if($item->pid>0 )<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i> @endif
			<i class="glyphicon glyphicon-folder-close"></i>
			{{ $item->name }}
			</td>
			<td edata-fields="pnum">{{ $item->pnum }}</td>
			<td edata-fields="num">{{ $item->num }}</td>
			<td edata-fields="pid">{{ $item->pid }}</td>
			<td>{{ trans('table/agentmenu.type_'.$item->type.'') }}({{ $item->type }}) </td>
	
			<td edata-fields="url">{{ $item->url }}</td>
			<td edata-fields="wherestr">{{ $item->wherestr }}</td>
			<td edata-fields="isturn" edata-value="{{ $item->isturn }}">
			<img src="/images/checkbox{{ $item->isturn }}.png" height="20">
			</td>
			<td edata-fields="iswzf" edata-value="{{ $item->iswzf }}">
			<img src="/images/checkbox{{ $item->iswzf }}.png" height="20">
			</td>
			<td edata-fields="wherestr">{{ $item->color }}</td>
			<td edata-fields="explain">{{ $item->explain }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="isbag" edata-value="{{ $item->isbag }}"><img src="/images/checkbox{{ $item->isbag }}.png" height="20"></td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
			<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			<td>
				<a href="{{ route('adminagentmenuedit', $agentid) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
				@if($item->pid==0)
				<br><a href="{{ route('adminagentmenuedit', $agentid) }}?pid={{ $item->id }}">{{ trans('table/agentmenu.adddown') }}</a>
				@endif
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
		delurl:'{{ route('adminagentmenudel') }}'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/agentmenu.sort') }}({{ trans('table/agentmenu.sort_msg') }})","type":"number"};
		columns['pid'] = {"name":"{{ trans('table/agentmenu.pid') }}({{ trans('table/agentmenu.pid_msg') }})","type":"number"};
		columns['num'] = {"name":"{{ trans('table/agentmenu.num') }}"};
		columns['pnum'] = {"name":"{{ trans('table/agentmenu.pnum') }}"};
		columns['url'] = {"name":"{{ trans('table/agentmenu.url') }}"};
		columns['color'] = {"name":"{{ trans('table/agentmenu.color') }}"};
		columns['wherestr'] = {"name":"{{ trans('table/agentmenu.wherestr') }}","type":"textarea"};
		columns['explain'] = {"name":"{{ trans('table/agentmenu.explain') }}","type":"textarea"};
		
		columns['status']  	 = {"name":"{{ trans('table/agentmenu.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isbag']  	 = {"name":"{{ trans('table/agentmenu.isbag') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['iswzf']  	 = {"name":"{{ trans('table/agentmenu.iswzf') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isturn']  	 = {"name":"{{ trans('table/agentmenu.isturn') }}","type":"checkbox",
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