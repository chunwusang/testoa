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
				<input class="form-control" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('table/usera.keyword') }}">
			</div>
			<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
		</form>
	</div>
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th></th>
			<th>{{ trans('table/usera.name') }}</th>
			<th>{{ trans('table/usera.user') }}</th>
			<th>{{ trans('table/usera.position') }}</th>
			<th>{{ trans('table/usera.companyname') }}</th>
			<th>{{ trans('table/usera.uid') }}</th>
			<th>{{ trans('table/usera.mobile') }}</th>
			<th>{{ trans('table/usera.email') }}</th>
			<th>{{ trans('table/usera.created_at') }}</th>
			<th>{{ trans('table/usera.status') }}</th>
		</tr>
		@foreach ($data as $item)
		<tr @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }}</td>
			<td><img width="30" src="{{ $item->face }}"></td>
			<td>{{ $item->name }} </td>
			<td>{{ $item->user }} </td>
			<td>{{ $item->position }}</td>
			<td>{{ $item->cid }}.{{ $item->companyname }}</td>
			<td>{{ $item->uid }}</td>
			<td>{{ $item->mobilecode }}{{ substr($item->mobile,0,3) }}****{{ substr($item->mobile,-4) }}</td>
			<td>{{ $item->email }}</td>
			<td>{{ $item->created_at }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/usera.status1') }}</span>
			@endif
			@if ($item->status==0)
			<span class="label label-danger">{{ trans('table/usera.status0') }}</span>
			@endif
			@if ($item->status==2)
			<span class="label label-warning">{{ trans('table/usera.status2') }}</span>
			@endif
			</td>
		</tr>
		@endforeach
	</table>
	@include('layouts.pager')
</div>
@endsection
