@extends('manage.public')

@section('content')
<div style="padding:0px 15px">
   
	<div>
		<h3>{{ trans('table/usera.pagetitles') }}</h3>
		<hr class="head-hr" />
	</div>	
   
	<div class="row">
		<div class="col-sm-2" style="cursor:pointer">
			<ul class="list-group">
			  <li class="list-group-item active">{{ trans('table/usera.depttitle') }}</li>
			  
				@foreach($deptdata as $item)
				<li onclick="tousertr({{ $item->id }}, {{ $item->pid }})" class="list-group-item @if($item->id==$did)list-group-item-info @endif ">
				@for($i=0; $i<$item->level; $i++)
					<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i>
				@endfor
				<i class="glyphicon glyphicon-folder-close"></i>
					{{ $item->name }}
				</li>
				@endforeach

			</ul>
		</div>
		
		<div class="col-sm-10">
		
			<div class="row">
				<div class="col-md-6">
					<form class="form-inline" role="form">
						<div class="form-group">
							<input class="form-control" type="text" name="keyword" value="{{ $key }}" placeholder="{{ trans('base.keyword') }}">
							<select class="form-control" style="width:100px" type="text" name="status">
							<option value="">-{{ trans('table/usera.status') }}-</option>
							<option value="0" @if($status=='0')selected @endif>{{ trans('table/usera.status0') }}</option>
							<option value="1" @if($status=='1')selected @endif>{{ trans('table/usera.status1') }}</option>
							<option value="2" @if($status=='2')selected @endif>{{ trans('table/usera.status2') }}	</option>
							</select>
						</div>
						<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
					</form>
				</div>
				<div class="col-md-6" align="right">
					<button type="button" onclick="reloaddata()" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> {{ trans('table/usera.reloads') }}</button>&nbsp;
					<button type="button" onclick="ondaoru()" class="btn btn-default">{{ trans('table/usera.daotext') }}</button>&nbsp;
					<a href="{{ route('manage',[$cnum,'usera_edit']) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/usera.addtext') }}</a>
				</div>
			</div>
				
			<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
				<tr>
					<th><input type="checkbox"></th>
					<th>ID</th>
					<th></th>
					<th>{{ trans('table/usera.name') }}</th>
					<th>{{ trans('table/usera.user') }}</th>
					<th>{{ trans('table/usera.deptname') }}</th>
					<th>{{ trans('table/usera.superman') }}</th>
					<th>{{ trans('table/usera.position') }}<i class="glyphicon glyphicon-pencil"></i></th>
					<th>{{ trans('table/usera.mobile') }}</th>
					<th>{{ trans('table/usera.email') }}<i class="glyphicon glyphicon-pencil"></i></th>
					
					<th>{{ trans('table/usera.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
					<th>{{ trans('table/usera.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
					<th>UID</th>
					<th>{{ trans('table/usera.istxl') }}<i class="glyphicon glyphicon-pencil"></i></th>
					<th></th>
				</tr>
				@foreach ($data as $item)
				<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
					<td><input value="{{ $item->id }}" type="checkbox"></td>
					<td>{{ $item->id }}</td>
					<td><img src="{{ $item->face }}" width="30"></td>
					<td>{{ $item->name }} </td>
					<td>{{ $item->user }} </td>
					<td>{{ $item->deptname }}</td>
					<td>{{ $item->superman }}</td>
					<td edata-fields="position">{{ $item->position }}</td>
					<td>{{ $item->mobilecode }}{{ substr($item->mobile,0,3) }}****{{ substr($item->mobile,-4) }}</td>
					<td edata-fields="email">{{ $item->email }}</td>
					<td edata-fields="sort">{{ $item->sort }}</td>
					<td @if($item->company->uid!=$item->uid)edata-fields="status" @endif edata-value="{{ $item->status }}">
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
					<td>{{ $item->uid }}</td>
					<td edata-fields="istxl" edata-value="{{ $item->istxl }}">
					<img src="/images/checkbox{{ $item->istxl }}.png" height="20">
					</td>
					<td>
					<a href="{{ route('manage',[$cnum,'usera_edit']) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
					@if ($item->company->uid!=$item->uid)
					<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
					@endif
					</td>
				</tr>
				@endforeach
			</table>
			
			@include('layouts.pager')
		</div>
	</div>
   
</div>
@endsection

@section('script')
<script>
function tousertr(id, pid){
	var url = '/manage/'+cnum+'/usera';
	if(pid==0)id=0;
	js.location(''+url+'?did='+id+'');
}
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delparams:{cid:{{ $cid }}},
		delurl:'/api/unit/'+cnum+'/usera_delcheck'
	});
}
function reloaddata(){
	js.loading();
	js.ajax('/api/unit/'+cnum+'/usera_reload', false, function(ret){
		js.msgok();
		js.reload();
	},'get');
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/usera.sort') }}({{ trans('table/usera.sort_msg') }})","type":"number"};
		columns['position']  = {"name":"{{ trans('table/usera.position') }}"};
		columns['email']  	 = {"name":"{{ trans('table/usera.email') }}","type":'email'};
		columns['status']  	 = {"name":"{{ trans('table/usera.status') }}","type":"select","store":[{"value":"0","name":"{{ trans('table/usera.status0') }}","ys":"danger"},{"value":"1","name":"{{ trans('table/usera.status1') }}","ys":"success"},{"value":"2","name":"{{ trans('table/usera.status2') }}","ys":"warning"}],
			onediterbefore:function(v){
				if(v=='0'){
					
				}
			},
			renderer:function(v, fa){
				var f = fa.store[parseInt(v)];
				return '<span class="label label-'+f.ys+'">'+f.name+'</span>';
			}
		};
		columns['istxl']  	 = {"name":"{{ trans('table/usera.istxl') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
}

function ondaoru(){
	var url = '/daoru/'+cnum+'/usera';
	$.rockmodeliframe('{{ trans('base.daorutext') }}', url);
}
</script>
@endsection