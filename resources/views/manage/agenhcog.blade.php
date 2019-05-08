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
			<button type="button" onclick="save(0)" class="btn btn-success"><i class="glyphicon glyphicon-floppy-disk"></i> {{ trans('base.savetext') }}</button>&nbsp;
			<button type="button" onclick="save(1)" class="btn btn-default"><i class="glyphicon glyphicon-repeat"></i> {{ trans('table/agentfields.cog_huifu') }}</button>
		</div>
	</div>
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
		
			<th>ID</th>
			<th>{{ trans('table/agentfields.name') }}</th>
			<th>{{ trans('table/agentfields.fields') }}</th>
			<th>{{ trans('table/agentfields.fieldstype') }}</th>
			<th><label><input type="checkbox" onclick="js.selall(this,'fields_islu')">{{ trans('table/agentfields.cog_islu') }}</label></th>
			<th><label><input type="checkbox" onclick="js.selall(this,'fields_islb')">{{ trans('table/agentfields.cog_islb') }}</label></th>
			<th><label><input type="checkbox" onclick="js.selall(this,'fields_ispx')">{{ trans('table/agentfields.cog_ispx') }}</label></th>
			<th><label><input type="checkbox" onclick="js.selall(this,'fields_isss')">{{ trans('table/agentfields.cog_isss') }}</label></th>
		</tr>
		@foreach ($data as $item)
		@if($item->iszb==0)
		<tr >
			<td>{{ $item->id }} </td>
			<td>{{ $item->name }} </td>
			<td>{{ $item->fields }} </td>
			<td>{{ trans('table/agentfields.fieldstype_'.$item->fieldstype.'') }}({{ $item->fieldstype }}) </td>
			<td><input type="checkbox" @if($item->islu==1)checked @endif name="fields_islu" value="{{ $item->fields }}"></td>
			<td><input type="checkbox" @if($item->islb==1)checked @endif name="fields_islb" value="{{ $item->fields }}"></td>
			<td><input type="checkbox" @if($item->ispx==1)checked @endif name="fields_ispx" value="{{ $item->fields }}"></td>
			<td><input type="checkbox" @if($item->isss==1)checked @endif name="fields_isss" value="{{ $item->fields }}"></td>
			
		</tr>
		@endif
		@endforeach
	</table>
	
	
	
</div>
@endsection

@section('script')
<script>
function save(lx){
	var da = {
		"fields_islu":js.getchecked('fields_islu'),
		"fields_islb":js.getchecked('fields_islb'),
		"fields_ispx":js.getchecked('fields_ispx'),
		"fields_isss":js.getchecked('fields_isss')
	};
	if(lx==1)da={};
	da.cid = companyid;
	da.id = {{ $agenhid }};
	js.ajax('/api/unit/'+cnum+'/agenh_cogsave', da, function(ret){
		if(lx==1)location.reload();
	},'post',false,'{{ trans('base.chultext') }}');
}
</script>
@endsection