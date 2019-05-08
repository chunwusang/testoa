@extends('manage.public')

@section('content')
<div class="container">
	
	<div>
		<h3>{{ $pagetitles }}</h3>
		<hr class="head-hr" />
	</div>	
	
	
	<div class="row">
		<div class="col-md-8">
			<button type="button" onclick="js.back()" class="btn btn-default"><i class="glyphicon glyphicon-chevron-left"></i> {{ trans('base.back') }}</button>
		</div>
		<div class="col-md-4" align="right">
			<a href="{{ route('manage',[$cnum,'agenh_courseedit']) }}?agenhid={{ $agenhid }}&pid={{ $pid }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/agenhcourse.addtext') }}</a>
		</div>
	</div>
	
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/agenhcourse.name') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenhcourse.num') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenhcourse.recename') }}</th>
			<th>{{ trans('table/agenhcourse.checkwhere') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenhcourse.checktype') }}</th>
			<th>{{ trans('table/agenhcourse.checktypename') }}</th>
			<th>{{ trans('table/agenhcourse.sort') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/agenhcourse.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
	@foreach($dapar as $k=>$data)	
		<tr><td colspan="30"><b>{{ trans('table/agenhcourse.flowtitle') }}{{ $k+1 }}</b>，<a href="{{ route('manage',[$cnum,'agenh_courseedit']) }}?agenhid={{ $agenhid }}&pid={{ $k }}">{{ trans('table/agenhcourse.editpext') }}</a></td></tr>
		@foreach ($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
			<td>{{ $item->id }} </td>
			<td edata-fields="name">{{ $item->name }}</td>
			<td edata-fields="num">{{ $item->num }} </td>
			<td>{{ $item->recename }} </td>
			<td edata-fields="checkwhere">{{ $item->checkwhere }} </td>
			<td>{{ trans('table/agenhcourse.checktype_'.$item->checktype.'') }}({{ $item->checktype }}) </td>
			<td>{{ $item->checktypename }} </td>
			<td edata-fields="sort">{{ $item->sort }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
				<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			<td>
				<a href="{{ route('manage',[$cnum,'agenh_courseedit']) }}?agenhid={{ $agenhid }}&id={{ $item->id }}">{{ trans('base.edittext') }}</a>
				<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
			</td>
		</tr>
		@endforeach
	@endforeach	
	</table>
	
</div>
@endsection

@section('script')
<script>
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'/api/unit/'+cnum+'/agenh_delcoursecheck'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"{{ trans('table/agenhcourse.sort') }}({{ trans('table/agenhcourse.sort_msg') }})","type":"number"};
		columns['status']  	 = {"name":"{{ trans('table/agenh.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['checkwhere'] = {"name":"{{ trans('table/agenhcourse.checkwhere') }}","type":"textarea"};
		columns['name'] = {"name":"{{ trans('table/agenhcourse.name') }}"};
		columns['num'] = {"name":"{{ trans('table/agenhcourse.num') }}"};
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