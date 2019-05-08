@extends('manage.public')

@section('content')
<div class="container">
   
	<div>
		<h3>{{ trans('table/group.pagetitles') }}</h3>
		<hr class="head-hr" />
	</div>	
   
	<div class="row">
		<div class="col-sm-5">
			
			
			<div>
				<a href="{{ route('manage',[$cnum,'group_edit']) }}" class="btn btn-default"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/group.addtext') }}</a>
			</div>
			<div style="margin-top:10px">
				<table class="table table-striped table-bordered table-hover">
					<tr>
						<th>ID</th>
						<th>{{ trans('table/group.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
						<th>{{ trans('table/group.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
						<th>{{ trans('table/group.usershu') }}</th>
						<th></th>
					</tr>
					@foreach($groupdata as $item)
					<tr id="row_{{ $item->id }}">
						<td>{{ $item->id }}</td>
						<td edata-fields="name">{{ $item->name }}</td>
						<td edata-fields="sort">{{ $item->sort }}</td>
						<td>{{ $item->usershu }} </td>
						
						<td>
						<a href="{{ route('manage',[$cnum,'group_edit']) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
						<a href="{{ route('manage',[$cnum,'group']) }}?gid={{ $item->id }}">{{ trans('table/group.downuser') }}</a>
						<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
						</td>
					</tr>
					@endforeach
				</table>
			</div>
		</div>
		
		<div class="col-sm-7">
		
			<div class="row">
				<div class="col-md-4">
					
				</div>
				<div class="col-md-8" align="right">
					<button @if($gid==0)disabled @endif onclick="addguser()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/group.adduses') }}</button>
				</div>
			</div>
				
			<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
				<tr>
					<th>ID</th>
					<th>{{ trans('table/usera.name') }}</th>
					<th>{{ trans('table/usera.user') }}</th>
					<th>{{ trans('table/usera.position') }}</th>
					<th>{{ trans('table/usera.deptname') }}</th>
					<th>{{ trans('table/usera.status') }}</th>
					<th></th>
				</tr>
				@foreach ($data as $item)
				<tr id="rowlist_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
					<td>{{ $item->id }}</td>
					<td>{{ $item->name }} </td>
					<td>{{ $item->user }} </td>
					<td>{{ $item->position }}</td>
					<td>{{ $item->deptname }}</td>
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
					
					<td>
					<a href="javascript:;" onclick="delmovesid({{ $item->id }})">{{ trans('table/group.moveuser') }}</a>
					</td>
				</tr>
				@endforeach
			</table>

		</div>
	</div>
   
</div>
@endsection

@section('script')
<script src="/res/js/jquery-changeuser.js"></script>
<script>
var gid  = {{ $gid }};
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'/api/unit/'+cnum+'/group_delcheck'
	});
}
function delmovesid(id){
	$.rockmodeldel({
		delid:id,
		delrow:'rowlist',
		delparams:{cid:companyid,mid:gid,type:'gu',sid:id},
		delurl:'/api/unit/'+cnum+'/group_deluser'
	});
}
function addguser(){
	$.rockmodeuser({
		changetype:'checkuser',
		title:'{{ trans('table/group.adduses') }}',
		onselect:function(sna,sid){
			if(sid){
				js.ajax('/api/unit/'+cnum+'/group_saveuser', {sid:sid,mid:gid}, function(ret){
					location.reload();
				},'post',false,'{{ trans('base.chultext') }}');
			}
		}
	});
}

function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/group.sort') }}({{ trans('table/group.sort_msg') }})","type":"number"};
		columns['name'] = {"name":"{{ trans('table/group.name') }}"};
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