@extends('manage.public')

@section('content')
<div class="container">
 
	<div>
		<h3>{{ trans('table/dept.pagetitle') }}</h3>

	</div>	

	<!-- <div>啦啦啦</div> -->
	<table style="margin-top:20px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>{{ trans('table/dept.name') }}</th>
			<th>{{ trans('table/dept.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/dept.headman') }}</th>
			<th>{{ trans('table/dept.pid') }}</th>
			<th>{{ trans('table/dept.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/dept.status') }}</th>
			<th>ID</th>
			<th></th>
		</tr>
		@foreach($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td>
			@for($i=0; $i<$item->level; $i++)
				<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i>
			@endfor
			<i class="glyphicon glyphicon-folder-close"></i>
			{{ $item->name }}
			</td>
			<td edata-fields="num">{{ $item->num }}</td>
			<td>{{ $item->headman }}</td>
			<td>{{ $item->pid }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/dept.status1') }}</span>
			@else
			<span class="label label-default">{{ trans('table/dept.status0') }}</span>
			@endif
			</td>
			<td>{{ $item->id }}</td>
			<td>
			<a href="{{ route('manage', [$cnum,'dept_edit']) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
			
			&nbsp;<a href="{{ route('manage', [$cnum,'dept_edit']) }}?pid={{ $item->id }}">{{ trans('table/dept.addtxt') }}</a>
			@if ($item->pid>0)
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
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
		delurl:'/api/unit/'+cnum+'/dept_delcheck'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/dept.sort') }}({{ trans('table/dept.sort_msg') }})","type":"number"};
		columns['num'] = {"name":"{{ trans('table/dept.num') }}"};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
}
</script>
@endsection