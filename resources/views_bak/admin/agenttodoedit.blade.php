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
		
			
			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label">{{ trans('table/agenttodo.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenttodo.num') }}" placeholder="{{ trans('table/agenttodo.num_msg') }}" value="{{ $data->num }}" maxlength="50" id="input_num" type="onlyen" name="num">
				</div>
			</div>
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label">{{ trans('table/agenttodo.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" required onblur="js.str(this)" data-fields="{{ trans('table/agenttodo.name') }}" placeholder="{{ trans('table/agenttodo.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" type="text" name="name">
				</div>
			</div>
		
	
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenttodo.changelx') }}</label>
				<div class="col-sm-8">
					@foreach($changearr as $lxs)
					<label><input type="checkbox" @if ($data->$lxs==1)checked @endif value="1" name="{{ $lxs }}">{{ trans('table/agenttodo.changelx_'.$lxs.'') }}</label>&nbsp;
					@endforeach
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('table/agenttodo.tasktype') }}</label>
				<div class="col-sm-3">
					<select name="tasktype" data-fields="{{ trans('table/agenttodo.tasktype') }}" class="form-control">
					<option value="">-{{ trans('table/agenttodo.tasktype') }}-</option>
					@foreach($tasktypea as $lxs)
					<option @if($data->tasktype==$lxs)selected @endif value="{{ $lxs }}" >{{ trans('table/agenttodo.tasktype_'.$lxs.'') }}</option>
					@endforeach
					</select>
				</div>
				<div class="col-sm-5">
					<input class="form-control" data-fields="{{ trans('table/agenttodo.tasktime') }}" placeholder="{{ trans('table/agenttodo.tasktime_msg') }}" onclick="js.datechange(this,'datetime')" readonly value="{{ $data->tasktime }}" type="text" name="tasktime">
				</div>
			</div>
			
			<div class="form-group" inputname="wherestr">
				<label for="input_wherestr" class="col-sm-3 control-label">{{ trans('table/agenttodo.wherestr') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agenttodo.wherestr') }}" placeholder="{{ trans('table/agenttodo.wherestr_msg') }}" id="input_wherestr" name="wherestr">{{ $data->wherestr }}</textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label  class="col-sm-3 control-label">{{ trans('table/agenttodo.todolx') }}</label>
				<div class="col-sm-8">
					@foreach($todoarr as $lxs)
					<label><input type="checkbox" @if ($data->$lxs==1)checked @endif value="1" name="{{ $lxs }}">{{ trans('table/agenttodo.todolx_'.$lxs.'') }}</label>&nbsp;
					@endforeach
				</div>
			</div>
			
			<div class="form-group" inputname="todofields">
				<label for="input_todofields" class="col-sm-3 control-label">{{ trans('table/agenttodo.todofields') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenttodo.todofields') }}" placeholder="{{ trans('table/agenttodo.todofields_msg') }}" value="{{ $data->todofields }}"id="input_todofields" type="onlyen" name="todofields">
				</div>
			</div>
			
			
			
			<div class="form-group" inputname="summary">
				<label for="input_summary" class="col-sm-3 control-label">{{ trans('table/agenttodo.summary') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agenttodo.summary') }}" placeholder="{{ trans('table/agenttodo.summary_msg') }}" id="input_summary" name="summary">{{ $data->summary }}</textarea>
				</div>
			</div>
			
			<div class="form-group" inputname="explain">
				<label for="input_explain" class="col-sm-3 control-label">{{ trans('table/agenttodo.explain') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agenttodo.explain') }}" placeholder="{{ trans('table/agenttodo.explain_msg') }}" id="input_explain" name="explain">{{ $data->explain }}</textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/agenttodo.status') }}{{ trans('table/agenttodo.status1') }}</label>&nbsp;
			
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
<link rel="stylesheet" href="/res/plugin/jquery-rockdatepicker.css"/>
<script src="/res/plugin/jquery-rockdatepicker.js"></script>
<script>
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('adminagenttodosave') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
</script>
@endsection