@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	<div class="tbl-top">
		<form class="form-inline" role="form">
			<div class="form-group">
				<input class="form-control" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('table/dept.keyword') }}">
			</div>
			<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
		</form>
	</div>
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th>{{ trans('table/dept.name') }}</th>
			<th>{{ trans('table/dept.pid') }}</th>
			<th>{{ trans('table/dept.cid') }}</th>
			<th>{{ trans('table/dept.headman') }}</th>
			
			<th>{{ trans('table/dept.sort') }}</th>
			<th>{{ trans('table/dept.created_at') }}</th>
			<th>{{ trans('table/dept.status') }}</th>
		</tr>
		@foreach ($data as $item)
		<tr @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }}</td>
			<td>{{ $item->name }} </td>
			<td>{{ $item->pid }}</td>
			<td>{{ $item->cid }}</td>
			<td>{{ $item->headman }}</td>
			<td>{{ $item->sort }}</td>
			<td>{{ $item->created_at }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/dept.status1') }}</span>
			@else
			<span class="label label-danger">{{ trans('table/dept.status0') }}</span>
			@endif
			</td>
		</tr>
		@endforeach
	</table>
	@include('layouts.pager')
</div>
@endsection
