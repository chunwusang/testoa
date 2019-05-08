@extends('admin.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{{ trans('table/task.taskdesc') }}{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	<table width="100%"><tr>
		<td style="padding-right:10px"><button type="button" onclick="onpotstr('taskstart')" class="btn btn-success">{{ trans('table/task.starttask') }}</button></td>
		<td style="padding-right:10px"><button type="button" onclick="onpotstr('taskclear')" class="btn btn-default btn-sm">{{ trans('table/task.clearzt') }}</button></td>
		<td style="padding-right:10px"><button type="button" onclick="onpotstr('taskbeifen')" class="btn btn-default btn-sm">{{ trans('table/task.beifen') }}</button></td>
		<td style="padding-right:10px"><button type="button" onclick="onpotstr('taskdaoru')" class="btn btn-default btn-sm">{{ trans('table/task.daoru') }}</button></td>
		<td width="100%"><a href="javascript:;" onclick="openhelp()">{{ trans('base.help') }}</a></td>
		<td align="right">
			<button type="button" onclick="addguanly()" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/task.addtext') }}</button>
		</td>
	</tr></table>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th>ID</th>
			
			<th>{{ trans('table/task.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.fenlei') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.url') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.type') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.time') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.ratecont') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.state') }}</th>
			<th>{{ trans('table/task.lastdt') }}</th>
			<th>{{ trans('table/task.lastcont') }}</th>
			<th>{{ trans('table/task.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/task.cid') }}</th>
			<th></th>
		</tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }}</td>
			
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="fenlei">{{ $item->fenlei }}</td>
			<td edata-fields="url">{{ $item->url }}</td>
			<td edata-fields="type">{{ $item->type }}</td>
			<td edata-fields="time">{{ $item->time }}</td>
			<td edata-fields="ratecont">{{ $item->ratecont }}</td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
			<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			<td><font color="{{ trans('table/task.color'.$item->state.'') }}">{{ trans('table/task.state'.$item->state.'') }}</font></td>
			<td>{{ $item->lastdt }}</td>
			<td>{{ $item->lastcont }}</td>
			<td edata-fields="explain">{{ $item->explain }}</td>
			<td>{{ $item->cid }}</td>
			<td>
				<a href="javascript:;" onclick="onpotstr('taskrun', {{ $item->id }})">{{ trans('table/task.yunxing') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
			</td>
		</tr>
		@endforeach
	</table>
	
</div>
@endsection

@section('script')
<script>
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['name'] = {"name":"{{ trans('table/task.name') }}"};
		columns['fenlei'] = {"name":"{{ trans('table/task.fenlei') }}"};
		columns['type'] = {"name":"{{ trans('table/task.type') }}"};
		columns['url'] = {"name":"{{ trans('table/task.url') }}"};
		columns['time'] = {"name":"{{ trans('table/task.time') }}"};
		columns['ratecont'] = {"name":"{{ trans('table/task.ratecont') }}"};
		columns['explain'] = {"name":"{{ trans('table/task.explain') }}","type":"textarea"};
		columns['sort'] = {"name":"{{ trans('table/task.sort') }}({{ trans('table/task.sort_msg') }})","type":"number"};
		columns['status']  	 = {"name":"{{ trans('table/task.status') }}","type":"checkbox",
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
function addguanly(){
	$.rockmodelmsg('wait');
	$.get('{{ route('adminmanage','addtask') }}', function(ret){
		if(ret!='ok'){
			$.rockmodelmsg('msg', ret);
		}else{
			js.reload();
		}
	});
}

function onpotstr(act,id){
	$.rockmodelmsg('wait');
	var da = false;
	if(id)da={'id':id};
	js.ajaxbase('{{ route('adminmanage','') }}/'+act+'',da, function(msg){
		$.rockmodelmsg('ok', msg);
		if(act=='taskclear' || act=='taskdaoru' || act=='taskrun')js.reload();
	},'get', function(msg){
		$.rockmodelmsg('msg', msg);
	});
}

function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'{{ route('adminmanagesave','deltask') }}'
	});
}

function openhelp(){
	var url = '{{ route('adminmanage','') }}/taskhelp';
	js.open(url, 600,400);
}

</script>
@endsection