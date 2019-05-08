@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	<div class="tbl-top">
		<table width="100%"><tr>
		<td>
		<form class="form-inline" role="form">
			<div class="form-group">
				<input class="form-control" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('table/users.keyword') }}">
			</div>
			<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
		</form>
		</td>
		<td align="right">
			<a href="{{ route('adminusersedit', 0) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/users.addtext') }}</a>
		</td>
		</tr></table>
	</div>
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th>{{ trans('table/users.face') }}</th>
			<th>{{ trans('table/users.name') }}</th>
			<th>{{ trans('table/users.userid') }}</th>
			<th>{{ trans('table/users.nickname') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/users.mobile') }}</th>
			
			<th>{{ trans('table/users.email') }}</th>
			<th>{{ trans('table/users.created_at') }}</th>
			<th>{{ trans('table/users.flaskm') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/users.flask') }}</th>
			<th>{{ trans('table/users.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }}</td>
			<td><img width="30" src="{{ $item->face }}"></td>
			<td>{{ $item->name }}</td>
			<td>{{ $item->userid }}</td>
			<td edata-fields="nickname">{{ $item->nickname }}</td>
			<td>{{ $item->mobilecode }}{{ substr($item->mobile,0,3) }}****{{ substr($item->mobile,-4) }}</td>
			<td>{{ $item->email }}</td>
			<td>{{ $item->created_at }}</td>
			<td edata-fields="flaskm">{{ $item->flaskm }}</td>
			<td>{{ $item->flaskm }}/{{ $item->flasks }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
			<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			
			<td>
				<a href="{{ route('adminusersedit', $item->id) }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
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
		columns['email'] = {"name":"{{ trans('table/users.email') }}","type":"email"};
		columns['name'] = {"name":"{{ trans('table/users.name') }}"};
		columns['nickname'] = {"name":"{{ trans('table/users.nickname') }}"};
		columns['flaskm'] = {"name":"{{ trans('table/users.flaskm') }}","type":"number"};
		
		columns['status']  	 = {"name":"{{ trans('table/users.status') }}","type":"checkbox",
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
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delmsg:'{{ trans('table/users.delmsg') }}',
		delurl:'{{ route('apiadmin','users_delcheck') }}'
	});
}

</script>
@endsection