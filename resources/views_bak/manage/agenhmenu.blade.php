@extends('manage.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitles }}</h3>
		<hr class="head-hr" />
	</div>	
	
	
	<div class="row">
		<div class="col-md-8">
			<button type="button" onclick="js.back()" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i> {{ trans('base.back') }}</button>
		</div>
		<div class="col-md-4" align="right">
			<a href="{{ route('manageagenhmenuedit',[$cid,$agenhid]) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agentmenu.adttext') }}</a>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/agentmenu.name') }}</th>
			<th>{{ trans('table/agentmenu.recename') }}</th>
			<th>{{ trans('table/agentmenu.num') }}</th>
			<th>{{ trans('table/agentmenu.type') }}</th>
			<th>{{ trans('table/agentmenu.url') }}</th>
			<th>{{ trans('table/agentmenu.color') }}</th>
			<th><a href="">{{ trans('table/agentmenu.sort') }}</a></th>
			<th>{{ trans('table/agentmenu.status') }}</th>
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
			<td>{{ $item->recename }} </td>
			<td>{{ $item->num }} </td>
			<td>{{ trans('table/agentmenu.type_'.$item->type.'') }}({{ $item->type }}) </td>
	
			<td>{{ $item->url }}</td>
			<td>{{ $item->color }}</td>
			<td>{{ $item->sort }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/agentmenu.status1') }}</span>
			@else
			<span class="label label-default">{{ trans('table/agentmenu.status0') }}</span>
			@endif
			</td>
			<td>
				<a href="{{ route('manageagenhmenuedit', [$cid,$agenhid]) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
				@if($item->pid==0)
				<a href="{{ route('manageagenhmenuedit', [$cid,$agenhid]) }}?pid={{ $item->id }}">{{ trans('table/agentmenu.adddown') }}</a>
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
		delparams:{cid:{{ $cid }}},
		delurl:'/api/unit/'+cnum+'/agenh_delmenucheck'
	});
}
</script>
@endsection