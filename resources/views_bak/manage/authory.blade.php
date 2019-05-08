@extends('manage.public')

@section('content')
<div class="container">
    <div>
		<h3>{{ trans('table/authory.pagetitle') }}</h3>
		<div>{{ trans('table/authory.pagedesc') }}</div>
		<hr class="head-hr" />

		
	</div>
	
	<div style="margin-top:20px">
		<table width="100%">
		<tr>
		<td>
			<a href="{{ route('manage',[$cnum,'authory_edit']) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ trans('table/authory.addtext') }}</a>
		</td>
		<td style="padding-left:10px">
		<select style="width:150px" class="form-control" id="souatype">
		 <option value="" >{{ trans('table/authory.atype') }}</option>
		  @for($i=0;$i<=7;$i++)
		  <option  @if(''.$i.''==$atype) selected @endif value="{{ $i }}">{{ $i }}.{{ trans('table/authory.atype'.$i.'') }}</option>
		  @endfor
		</select>
		</td>
		<td style="padding-left:10px">
		<select style="width:250px" class="form-control" id="souagenh">
		 <option value="" >{{ trans('table/authory.agenh') }}</option>
		  @foreach($agenharr as $xxtype=>$itemarr)
		  <optgroup label="{{ $xxtype }}">
		  @foreach($itemarr as $k=>$item)
		  <option value="{{ $item->id }}" {{ $item->sel }}>{{ $item->name }}({{ $item->num }})</option>
		  @endforeach
		 </optgroup>
		  @endforeach
		</select>
		</td>
		<td style="padding-left:10px">
			<button type="button" onclick="onsearchsss()" class="btn btn-default">{{ trans('base.searchbtn') }}</button>
		</td>
		<td width="100%"></td>
		<td align="right">
			
			<button type="button" onclick="plliangdel()" class="btn btn-danger">选中{{ trans('base.deltext') }}</button>
		</td>
		</tr>
		</table>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th><input type="checkbox" onclick="js.selall(this, 'selid')"></th>
			<th>ID</th>
			<th>{{ trans('table/authory.objectname') }}</th>
			<th>{{ trans('table/authory.atype') }}</th>
			<th>{{ trans('table/authory.agenhid') }}</th>
			<th>{{ trans('table/authory.recename') }}</th>
			<th>{{ trans('table/authory.wherestr') }}</th>
			<th>{{ trans('table/authory.status') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th>{{ trans('table/authory.explain') }}<i class="glyphicon glyphicon-pencil"></i></th>
			<th></th>
		</tr>
		@foreach($data as $item)
		<tr id="row_{{ $item->id }}" @if ($item->status==0)style="color:#aaaaaa" @endif>
			<td><input type="checkbox" name="selid" value="{{ $item->id }}"></td>
			<td>{{ $item->id }}</td>
			<td>{{ $item->objectname }}</td>
			<td>{{ trans('table/authory.atype'.$item->atype.'') }}</td>
			<td>{{ $item->agenhid }}</td>
			<td>{{ $item->recename }}</td>
			<td>{{ $item->wherestr }}</td>
			<td edata-fields="status" edata-value="{{ $item->status }}">
				<img src="/images/checkbox{{ $item->status }}.png" height="20">
			</td>
			<td edata-fields="explain" >{{ $item->explain }}</td>
			<td>
			<a href="{{ route('manage',[$cnum,'authory_edit']) }}?id={{ $item->id }}">{{ trans('base.edittext') }}</a>
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
function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'/api/unit/'+cnum+'/authory_delcheck'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['status']  	 = {"name":"{{ trans('table/authory.status') }}","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['explain'] = {"name":"{{ trans('table/authory.explain') }}","type":"textarea"};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
}
function onsearchsss(){
	var slx= get('souatype').value,sl1= get('souagenh').value;
	var url = '/manage/'+cnum+'/authory';
	var das = {atype:slx,agenhid:sl1};
	var s = '';
	for(i in das)if(das[i])s+='&'+i+'='+das[i]+'';
	if(s!='')url+='?'+s.substr(1)+'';
	js.location(url);
}
function plliangdel(){
	var sid = js.getchecked('selid');
	if(sid==''){
		js.msgerror('没有选择记录');
		return;
	}
	js.confirm('确定要删除选中的权限记录吗？', function(jg){
		if(jg!='yes')return;
		js.loading();
		js.ajax('/api/unit/'+cnum+'/authory_pldel', {sid:sid}, function(ret){
			js.msgok();
			js.reload();
		},'post');	
	});
}
</script>
@endsection