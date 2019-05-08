@extends('admin.public')

@section('content')

<div class="container">
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{{ trans('table/queue.queuedesc') }}{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	<table width="100%"><tr>
		<td style="padding-right:10px"><button type="button" onclick="onpotstr('queuechange')" class="btn btn-success">{{ trans('table/queue.queuechange') }}</button></td>
		<td style="padding-right:10px"><button type="button" onclick="openhelp()" class="btn btn-info btn-sm">{{ trans('table/queue.startqueue') }}</button></td>
		
		<td width="100%">
		{{ trans('table/queue.reimclint') }}{{ config('rockreim.reimclient') }}，<button type="button" onclick="changress('{{ config('rockreim.reimclient') }}')" class="btn btn-info btn-xs">检测是否可用</button>
		&nbsp;如何配置?请看<a href="javascript:;" onclick="openhelp()">{{ trans('base.help') }}</a>
		</td>
		<td align="right">
			<button type="button" onclick="onpotstr('queueclear')" class="btn btn-danger">{{ trans('table/queue.clearlist') }}</button>
		</td>
	</tr></table>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			
			
			<th>{{ trans('table/queue.atype') }}</th>
			<th>{{ trans('table/queue.cid') }}</th>
			<th>{{ trans('table/queue.title') }}</th>
			<th>{{ trans('table/queue.url') }}</th>
			<th>{{ trans('table/queue.status') }}</th>
			<th>{{ trans('table/queue.rundt') }}</th>
			<th>{{ trans('table/queue.optdt') }}</th>
			
			<th>{{ trans('table/queue.runcont') }}</th>
			<th>{{ trans('table/queue.lastdt') }}</th>
			<th></th>
		</tr>
		
	@foreach ($data as $item)
		<tr id="row_{{ $item->id }}">
			<td>{{ $item->id }}</td>
			<td>{{ $item->atype }}</td>
			<td>{{ $item->cid }}</td>
			<td>{{ $item->title }}</td>
			<td>{{ $item->url }}</td>
			<td>
			@if ($item->status==1)
			<span class="label label-success">{{ trans('table/queue.status1') }}</span>
			@endif
			@if ($item->status==0)
			<span class="label label-danger">{{ trans('table/queue.status0') }}</span>
			@endif
			@if ($item->status==2)
			<span class="label label-warning">{{ trans('table/queue.status2') }}</span>
			@endif
			</td>
			<td>{{ $item->rundt }}</td>
			<td>{{ $item->optdt }}</td>
			
			<td>{{ $item->runcont }}</td>
			<td>{{ $item->lastdt }}</td>
			<td>
				<a href="javascript:;" onclick="onpotstr('queuerun', {{ $item->id }})">{{ trans('table/queue.yunxing') }}</a>
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
function onpotstr(act,id){
	$.rockmodelmsg('wait');
	var da = false;
	if(id)da={'id':id};
	js.ajaxbase('{{ route('adminmanage','') }}/'+act+'',da, function(msg){
		$.rockmodelmsg('ok', msg);
		if(act=='queueclear' || act=='queuerun')js.reload();
	},'get', function(msg){
		$.rockmodelmsg('msg', msg);
	});
}

function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'{{ route('adminmanagesave','delqueue') }}'
	});
}

function openhelp(){
	window.open('{{ config('rock.urly') }}/view_reimcloud.html');
}

function changress(dz){
	var url = 'http'+dz.substr(2);
	js.confirm('即将打开地址：'+url+'，跟进如下判断是否可用：<br>打开页面出现<font color=green>“Upgrade Required”</font>说明可用，如打不开就是不能使用。',function(jg){
		if(jg=='yes')js.open(url, 400,200);
	});
}

</script>
@endsection
