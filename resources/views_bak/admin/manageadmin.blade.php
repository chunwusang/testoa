@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	<div align="right">
		<button type="button" onclick="addguanly()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/admin.addtext') }}</button>
	</div>
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th>{{ trans('table/admin.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/admin.user') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/admin.email') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/admin.bootstyle') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/admin.password') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/admin.created_at') }}</th>
			<th>{{ trans('table/admin.updated_at') }}</th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}">
			<td>{{ $item->id }}</td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="user">{{ $item->user }}</td>
			<td edata-fields="email">{{ $item->email }}</td>
			<td edata-fields="bootstyle">{{ $item->bootstyle }}</td>
			<td edata-fields="password"></td>
			<td>{{ $item->created_at }}</td>
			<td>{{ $item->updated_at }}</td>
			<td>
				@if(Auth::user()->id !=$item->id)
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
function initbody(){
	var nowuid = {{ Auth::user()->id }};
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['email'] = {"name":"{{ trans('table/admin.email') }}","type":"email"};
		columns['name'] = {"name":"{{ trans('table/admin.name') }}"};
		columns['user'] = {"name":"{{ trans('table/admin.user') }}"};
		columns['password'] = {"name":"{{ trans('table/admin.password') }}({{ trans('table/admin.password_msg') }})"};
		columns['bootstyle']  	 = {"name":"{{ trans('table/admin.bootstyle') }}","type":"select","store":{!! json_encode($stylearr) !!},
			renderer:function(v, fa, me){
				if(me.id==nowuid){
					if(v)get('bootstyle').href=v;
					js.savecookie('bootadminstyle', v);
				}
				return v;
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
function addguanly(){
	$.rockmodelmsg('wait');
	$.get('{{ route('adminmanage','addadmin') }}', function(ret){
		if(ret!='ok'){
			$.rockmodelmsg('msg', ret);
		}else{
			location.reload();
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