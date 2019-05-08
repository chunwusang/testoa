@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	
    
	<table class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th><div  align="center">{{ trans('table/company.logo') }}</div></th>
			<th>{{ trans('table/company.name') }}</th>
			<th>{{ trans('table/company.shortname') }}</th>
			<th>{{ trans('table/company.num') }}</th>
			<th>{{ trans('table/company.contacts') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/company.tel') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/company.flaskm') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/company.flask') }}</th>
			<th>{{ trans('table/company.uid') }}</th>
			<th>{{ trans('table/company.created_at') }}</th>
			<th>{{ trans('table/company.status') }}</th>
			
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }}</td>
			<td align="center"><img src="{{ $item->logo }}" height="30"></td>
			<td>{{ $item->name }} </td>
			
			<td>{{ $item->shortname }}</td>
			<td>{{ $item->num }} </td>
			<td edata-fields="contacts">{{ $item->contacts }}</td>
			<td edata-fields="tel">{{ $item->tel }}</td>
			<td edata-fields="flaskm">{{ $item->flaskm }}</td>
			<td>{{ $item->flaskm }}/{{ $item->flasks }}</td>
			<td>{{ $item->uid }}</td>
			<td>{{ $item->created_at }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/company.status1') }}</span>
			@else
			<span class="label label-danger">{{ trans('table/company.status0') }}</span>
			@endif
			</td>
			
			<td>
				<a href="{{ route('admincompanyedit', $item->id) }}">{{ trans('base.edittext') }}</a>
				<a href="{{ route('adminusera') }}?cid={{ $item->id }}">{{ trans('table/company.viewusera') }}</a>
				<a href="{{ route('admindept') }}?cid={{ $item->id }}">{{ trans('table/company.viewdept') }}</a>
			</td>
		</tr>
		@endforeach
	</table>
	
	@include('layouts.pager')
</div>
@endsection

@section('script')
<script>
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['contacts'] = {"name":"{{ trans('table/company.contacts') }}"};
		columns['tel'] = {"name":"{{ trans('table/company.tel') }}"};
		columns['flaskm'] = {"name":"{{ trans('table/company.flaskm') }}","type":"number"};
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