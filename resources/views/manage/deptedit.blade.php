@extends('manage.public')

@section('content')
<div class="container" align="center">
	<div align="left" style="max-width:550px">
		<div>
			<h3>{{ $pagetitles }}</h3>
			<hr class="head-hr" />
		</div>

	
			
		<form name="myform" class="form-horizontal">
		
			<input type="hidden" value="{{ $data->id }}" name="id">
			<input type="hidden" value="{{ $cid }}" name="cid">
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/dept.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/dept.name') }}" required placeholder="{{ trans('table/dept.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>

			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label">{{ trans('table/dept.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/dept.num') }}" placeholder="{{ trans('table/dept.num_msg') }}" value="{{ $data->num }}" maxlength="30" type="onlyen" id="input_num" name="num">
				</div>
			</div>
				
			<div class="form-group" inputname="pid">
				<label for="input_pid" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/dept.pid') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" @if($data->pid==0) readonly @endif data-fields="{{ trans('table/dept.pid') }}" required placeholder="{{ trans('table/dept.pid_msg') }}" maxlength="100" value="{{ $data->pid }}" id="input_pid" name="pid">
				</div>
			</div>
			
			<div class="form-group" inputname="headman">
				<label for="input_headman" class="col-sm-3 control-label">{{ trans('table/dept.headman') }}</label>
				<div class="col-sm-8">
				 <div class="input-group">
				  <input type="text" name="headman"  data-fields="{{ trans('table/dept.headman') }}" value="{{ $data->headman }}" readonly class="form-control" placeholder="{{ trans('table/dept.headman_msg') }}">
				  <input type="hidden" value="{{ $data->headid }}" name="headid">
				  <span class="input-group-btn">
					<button class="btn btn-default" onclick="clearxuan1()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
					<button class="btn btn-default" onclick="searchxuan1()" type="button"><i class="glyphicon glyphicon-search"></i></button>
				  </span>
				</div>
					<span id="myform_headman_errview"></span>
				</div>
			</div>
			
			<div class="form-group" inputname="status">
				<label for="input_status" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/dept.status') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_status" name="status">
				  <option value="1">{{ trans('table/dept.status1') }}</option>
				  <option value="0" @if($data->status==0)selected @endif>{{ trans('table/dept.status0') }}</option>
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/dept.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/dept.sort') }}" required placeholder="{{ trans('table/dept.sort_msg') }}" type="number" maxlength="10" value="{{ $data->sort }}" id="input_sort" name="sort">
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-9">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitles }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
		
			
			
		</form>

	</div>
</div>

@endsection

@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function submitadd(){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/dept',
		submitmsg:'{{ $pagetitles }}',
		backurl: '/manage/'+cnum+'/dept'
	});
}
function changesel(o){
	var sel = $(o.options[o.selectedIndex]).attr('selname');
	form('deptname').value=sel;
}
function clearxuan1(){
	form('headman').value='';
	form('headid').value='';
}

function searchxuan1(){
	$.rockmodeuser({
		title:'{{ trans('table/dept.headman_msg') }}',
		changetype:'usercheck',
		onselect:function(sna,sid){
			form('headman').value=sna;
			form('headid').value=sid;
		}
	});
}
</script>
@endsection