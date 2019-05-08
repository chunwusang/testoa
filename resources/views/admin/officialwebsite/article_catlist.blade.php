@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	

	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>

			<th>栏目名称</th>

			<th>操作</th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" >
			<td>{{ $item->id }}</td>
			<td>{{ $item->cat_name }}</td>


			</td>
			
			<td>
				<a href="{{ route('jctadmin_website_articlecatedit', $item->id) }}">{{ trans('base.edittext') }}</a>
				<!--
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
				-->
				<a href="{{ route('jctadmin_website_articlelist', $item->id) }}">查看列表</a>
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