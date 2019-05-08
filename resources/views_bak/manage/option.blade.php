@extends('manage.public')

@section('content')
<div class="container">
    <div>
		<h3>{{ trans('table/option.pagetitle') }}</h3>
		<div>{{ trans('table/option.pagedesc') }}</div>
		<hr class="head-hr" />
		
	</div>
	
	<div style="margin-top:20px">
		<table width="100%">
		<tr>
		@if($pid>0)
		<td style="padding-right:10px"><button onclick="js.back()" type="button" class="btn btn-default">&lt;&lt; {{ trans('base.back') }}</button></td>
		@endif
		<td style="padding-right:10px"><button onclick="ondaoru()" type="button" class="btn btn-default">导入默认选项</button></td>
		<!--
		<td>
			<input class="form-control" style="width:150px" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('base.keyword') }}">
		</td>
		
		<td style="padding-left:5px">
			<button type="button" class="btn btn-default">{{ trans('base.searchbtn') }}</button>
		</td>-->
		<td width="100%" align="right">
			<button type="button" onclick="addOption()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/option.addtext') }}</button>
		</td>
		</tr>
		</table>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			<th>{{ trans('table/option.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/option.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/option.pid') }}</th>
			<th>{{ trans('table/option.value') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/option.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/option.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/option.stotal') }}</th>
			<th>{{ trans('table/option.optdt') }}</th>
			<th></th>
		</tr>
		@foreach($data as $item)
		<tr id="row_{{ $item->id }}">
			<td>{{ $item->id }}</td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="num">{{ $item->num }}</td>
			<td>{{ $item->pid }}</td>
			<td edata-fields="value">{{ $item->value }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="explain">{{ $item->explain }}</td>
			<td>{{ $item->stotal }}</td>
			<td>{{ $item->optdt }}</td>
			<td>
			<a href="{{ route('manage',[$cnum, 'option']) }}?pid={{ $item->id }}">{{ trans('table/option.zhantext') }}</a>
			@if($item->stotal==0)
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
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delparams:{cid:{{ $cid }}},
		delurl:'/api/unit/'+cnum+'/option_delcheck'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/option.sort') }}({{ trans('table/option.sort_msg') }})","type":"number"};
		columns['name'] = {"name":"{{ trans('table/option.name') }}"};
		columns['num'] = {"name":"{{ trans('table/option.num') }}"};
		columns['value'] = {"name":"{{ trans('table/option.value') }}"};
		columns['explain'] = {"name":"{{ trans('table/option.explain') }}","type":"textarea"};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
}
function addOption(){
	var pid = '{{ $pid }}';
	js.ajax('/api/unit/'+cnum+'/option', {pid:pid}, function(ret){
		js.reload();
	},'post',false,'{{ trans('base.chultext') }}');
}

function ondaoru(){
	js.loading();
	js.ajax('/api/unit/'+cnum+'/option_importxuan', {}, function(ret){
		js.msgok(ret.data);
		js.reload();
	},'get');
}
</script>
@endsection