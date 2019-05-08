@extends('admin.public')

@section('content')
<div class="container" align="center">
	<div align="left" style="max-width:550px">
		<div>
			<h3>{{ $pagetitle }}</h3>
			<div>{!! $helpstr !!}</div>
			<hr class="head-hr" />
		</div>	
	
		<form name="myform" class="form-horizontal">
			
			<input type="hidden" value="{{ $data->id }}" name="id">
			<input type="hidden" value="{{ $data->agentid }}" name="agentid">
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentfields.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agentfields.name') }}" required placeholder="{{ trans('table/agentfields.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" type="text" name="name">
				</div>
			</div>
			
			<div class="form-group">
				<label for="input_iszb" class="col-sm-3 control-label">{{ trans('table/agentfields.iszb') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" id="input_iszb" name="iszb">
				  @foreach($iszbarr as $k=>$v)
				  <option @if($k==$data->iszb) selected @endif value="{{ $k }}">{{ $v }}</option>
				  @endforeach
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="fields">
				<label for="input_fields" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentfields.fields') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-pattern="" onblur="js.str(this)" data-fields="{{ trans('table/agentfields.fields') }}" required placeholder="{{ trans('table/agentfields.fields_msg') }}" value="{{ $data->fields }}" maxlength="100" id="input_fields" type="onlyen" name="fields">
				</div>
			</div>
			
			<div class="form-group" inputname="fieldstype">
				<label for="input_fieldstype" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentfields.fieldstype') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" data-fields="{{ trans('table/agentfields.fields') }}" required id="input_fieldstype" name="fieldstype">
				  @foreach($fieldstype as $rs) <option value="{{ $rs['value'] }}" {{ $rs['selected'] }}>{{ $rs['name'] }}</option> @endforeach
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="data">
				<label for="input_data" class="col-sm-3 control-label">{{ trans('table/agentfields.data') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agentfields.data') }}"  placeholder="{{ trans('table/agentfields.data_msg') }}"  id="input_data" type="text" name="data">{{ $data->data }}</textarea>
				</div>
			</div>
			
			<div class="form-group" inputname="fieldstext">
				<label for="input_fieldstext" class="col-sm-3 control-label">{{ trans('table/agentfields.fieldstext') }}</label>
				<div class="col-sm-8">
				  <input class="form-control"  onblur="js.str(this)" data-fields="{{ trans('table/agentfields.fieldstext') }}" placeholder="{{ trans('table/agentfields.fieldstext_msg') }}" value="{{ $data->fieldstext }}" maxlength="30" id="input_fieldstext" type="onlyen" name="fieldstext">
				</div>
			</div>
			
			<div class="form-group" inputname="lengs">
				<label for="input_lengs" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentfields.lengs') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.lengs') }}" required placeholder="{{ trans('table/agentfields.lengs_msg') }}" value="{{ $data->lengs }}" maxlength="10" id="input_lengs" type="number" name="lengs">
				</div>
			</div>
			
			<div class="form-group" inputname="dev">
				<label for="input_dev" class="col-sm-3 control-label">{{ trans('table/agentfields.dev') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.dev') }}" placeholder="{{ trans('table/agentfields.dev_msg') }}" value="{{ $data->dev }}" maxlength="100" id="input_dev" type="text" name="dev">
				</div>
			</div>
			
			<div class="form-group" inputname="placeholder">
				<label for="input_placeholder" class="col-sm-3 control-label">{{ trans('table/agentfields.placeholder') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.placeholder') }}" placeholder="{{ trans('table/agentfields.placeholder_msg') }}" value="{{ $data->placeholder }}" maxlength="100" id="input_placeholder" type="text" name="placeholder">
				</div>
			</div>
			
			<div class="form-group" inputname="attr">
				<label for="input_attr" class="col-sm-3 control-label">{{ trans('table/agentfields.attr') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agentfields.attr') }}"  placeholder="{{ trans('table/agentfields.attr_msg') }}"  id="input_explain" type="text" name="attr">{{ $data->attr }}</textarea>
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentfields.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.sort') }}" required placeholder="{{ trans('table/agentfields.sort_msg') }}" value="{{ $data->sort }}" maxlength="6" id="input_sort" type="number" name="sort">
				</div>
			</div>
			
			<div class="form-group" inputname="width">
				<label for="input_width" class="col-sm-3 control-label">{{ trans('table/agentfields.width') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.width') }}" placeholder="{{ trans('table/agentfields.width_msg') }}" value="{{ $data->width }}" maxlength="20" id="input_width" type="text" name="width">
				</div>
			</div>
			
			<div class="form-group" inputname="height">
				<label for="input_height" class="col-sm-3 control-label">{{ trans('table/agentfields.height') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentfields.height') }}" placeholder="{{ trans('table/agentfields.height_msg') }}" value="{{ $data->height }}" maxlength="20" id="input_height" type="text" name="height">
				</div>
			</div>
			
			<div class="form-group" inputname="gongsi">
				<label for="input_gongsi" class="col-sm-3 control-label">{{ trans('table/agentfields.gongsi') }}<br>{!! c('help')->show('gongsi') !!}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agentfields.gongsi') }}"  placeholder="{{ trans('table/agentfields.gongsi_msg') }}"  id="input_gongsi" type="text" name="gongsi">{{ $data->gongsi }}</textarea>
				</div>
			</div>
			
			<div class="form-group" inputname="explain">
				<label for="input_explain" class="col-sm-3 control-label">{{ trans('table/agentfields.explain') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agentfields.explain') }}"  placeholder="{{ trans('table/agentfields.explain_msg') }}"  id="input_explain" type="text" name="explain">{{ $data->explain }}</textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/agentfields.status') }}{{ trans('table/agentfields.status1') }}</label>&nbsp;
					<label><input type="checkbox" @if ($data->islu==1)checked @endif value="1" name="islu">{{ trans('table/agentfields.islu') }}</label>&nbsp;
					
					<label><input type="checkbox" @if ($data->isbt==1)checked @endif value="1" name="isbt">{{ trans('table/agentfields.isbt') }}</label>&nbsp;
					
					<label><input type="checkbox" @if ($data->iszs==1)checked @endif value="1" name="iszs">{{ trans('table/agentfields.iszs') }}</label>
					<label><input type="checkbox" @if ($data->islb==1)checked @endif value="1" name="islb">{{ trans('table/agentfields.islb') }}</label>
					<label><input type="checkbox" @if ($data->ispx==1)checked @endif value="1" name="ispx">{{ trans('table/agentfields.ispx') }}</label>
					<label><input type="checkbox" @if ($data->isss==1)checked @endif value="1" name="isss">{{ trans('table/agentfields.isss') }}</label>
					<label><input type="checkbox" @if ($data->isdr==1)checked @endif value="1" name="isdr">{{ trans('table/agentfields.isdr') }}</label>
					
					<label><input type="checkbox" @if($data->id==0) checked @endif value="1" name="autocj">{{ trans('table/agentfields.autocj') }}</label>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" id="btn1" onclick="submitadd()" class="btn btn-primary">{{ $pagetitle }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
	
			
		</form>	
	
	</div>
</div>
@endsection

@section('script')
<script>
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('adminagentfieldssave') }}',
		submitmsg:'{{ $pagetitle }}',
		autoback:{{ $data->id }}!=0,
		onsubmitsuccess:function(){
			get('btn1').disabled=false;
			var msg = '{{ trans('table/agentfields.jixutext') }}'
			js.msg('success', msg);
		},
		oncheck:function(na,val,d){
			if(na=='data'){
				if(d.fieldstype.indexOf('change')==0){
					if(d.data=='')return '{{ trans('table/agentfields.savetishi1') }}'+d.fields+'id';
				}
			}
		}
	});
}
</script>
@endsection