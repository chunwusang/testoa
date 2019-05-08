@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	<div>
		<button type="button" onclick="clearguanly()" class="btn btn-danger"><i class="glyphicon glyphicon-trash"></i> {{ trans('table/log.clearlog') }}</button>
	</div>
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th>{{ trans('table/log.ltype') }}</th>
			<th>{{ trans('table/log.optname') }}</th>
			<th>{{ trans('table/log.remark') }}</th>
			<th>{{ trans('table/log.cid') }}</th>
			<th>{{ trans('table/log.uid') }}</th>
			<th>{{ trans('table/log.ip') }}</th>
			<th>{{ trans('table/log.web') }}</th>
			<th>{{ trans('table/log.optdt') }}</th>
			<th>{{ trans('table/log.level') }}</th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if($item->level==2)style="color:red"@endif>
			<td>{{ $item->id }}</td>
			<td>{{ $item->ltype }}</td>
			<td>{{ $item->optname }}</td>
			<td>{!! $item->remark !!}</td>
			<td>{{ $item->cid }}</td>
			<td>{{ $item->uid }}</td>
			<td>{{ $item->ip }}</td>
			<td>{{ $item->web }}</td>
			<td>{{ $item->optdt }}</td>
			<td>{{ $item->level }}</td>
			
		</tr>
		@endforeach
	</table>
	
	@include('layouts.pager')
</div>
@endsection

@section('script')
<script>
function clearguanly(){
	$.rockmodelconfirm('{{ trans('table/log.clearmsg') }}', function(jg){
		if(jg=='yes'){
			$.rockmodelmsg('wait');
			$.get('{{ route('adminmanage','clearlog') }}', function(){
				js.reload();
			});
		}
	});
}

function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'{{ route('adminmanagesave','deladmin') }}'
	});
}
</script>
@endsection