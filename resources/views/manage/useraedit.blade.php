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
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/usera.name') }}" required placeholder="{{ trans('table/usera.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>
			
			<div class="form-group" inputname="gender">
				<label for="input_gender" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.gender') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" data-fields="{{ trans('table/usera.gender') }}" required id="input_gender" name="gender">
				  <option value="">{{ trans('table/usera.gender0') }}</option>
				  <option value="1" @if($data->gender==1)selected @endif>{{ trans('table/usera.gender1') }}</option>
				  <option value="2" @if($data->gender==2)selected @endif>{{ trans('table/usera.gender2') }}</option>
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="user">
				<label for="input_user" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.user') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/usera.user') }}" required placeholder="{{ trans('table/usera.user_msg') }}" value="{{ $data->user }}" maxlength="100" type="onlyen" onblur="this.value=strreplace(this.value)" id="input_user" name="user">
				</div>
			</div>

			
			<div class="form-group" inputname="mobile">
				<label for="input_mobile" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.mobile') }}</label>
				<div class="col-sm-3">
					<select @if($data->iseditmobile==0)readonly @endif name="mobilecode" class="form-control"><option value="">+86</option></select>
				</div>
				<div style="padding-left:0;"  class="col-sm-5">
				  <input class="form-control" @if($data->iseditmobile==0)readonly @endif data-fields="{{ trans('table/usera.mobile') }}" required placeholder="{{ trans('table/usera.mobile_msg') }}" value="{{ $data->mobile }}" maxlength="11" id="input_mobile" type="mobile" name="mobile">
				</div>
			</div>
			
			<div class="form-group" inputname="email">
				<label for="input_email" class="col-sm-3 control-label">{{ trans('table/usera.email') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/usera.email') }}" placeholder="{{ trans('table/usera.email_msg') }}" value="{{ $data->email }}" maxlength="200" id="input_email" name="email" type="email">
				</div>
			</div>
			
			<hr class="head-hr" />
			
			<div class="form-group" inputname="deptid">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.deptname') }}</label>
				<div class="col-sm-8">
					<select onchange="changesel(this)" class="form-control" required data-fields="{{ trans('table/usera.deptname') }}" name="deptid">
					<option value="">{{ trans('table/usera.deptname_msg') }}</option>
					@foreach($deptdata as $item)
					<option selname="{{ $item->name }}" value="{{ $item->id }}" @if($data->deptid==$item->id) selected @endif >
					@for($i=0; $i<$item->level; $i++)
					&nbsp;&nbsp;&nbsp;
					@endfor
					@if ($item->pid>0)├ @endif
					{{ $item->name }}
					</option>
					@endforeach
					</select>
					<input type="hidden" name="deptname" value="{{ $data->deptname }}">
				</div>
			</div>
			
			
			
			<div class="form-group" inputname="superman">
				<label for="input_superman" class="col-sm-3 control-label">{{ trans('table/usera.superman') }}</label>
				<div class="col-sm-8">
				 <div class="input-group">
				  <input type="text" name="superman"  data-fields="{{ trans('table/usera.superman') }}" value="{{ $data->superman }}" readonly class="form-control" placeholder="{{ trans('table/usera.superman_msg') }}">
				  <input type="hidden" value="{{ $data->superid }}" name="superid">
				  <span class="input-group-btn">
					<button class="btn btn-default" onclick="clearxuan1()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
					<button class="btn btn-default" onclick="searchxuan1()" type="button"><i class="glyphicon glyphicon-search"></i></button>
				  </span>
				</div>
					<span id="myform_superman_errview"></span>
				</div>
			</div>
			
			<div class="form-group" inputname="position">
				<label for="input_position" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.position') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/usera.position') }}" required placeholder="{{ trans('table/usera.position_msg') }}" value="{{ $data->position }}" maxlength="100" id="input_position" name="position">
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/usera.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/usera.sort') }}" required placeholder="{{ trans('table/usera.sort_msg') }}" type="number" maxlength="10" value="{{ $data->sort }}" id="input_sort" name="sort">
				</div>
			</div>
			
			<hr class="head-hr" />
			
			<div class="form-group" inputname="type">
				<label for="input_type" class="col-sm-3 control-label">{{ trans('table/usera.type') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" @if ($companyinfo->uid==$data->uid) disabled @endif id="input_type" name="type">
				  <option value="0">{{ trans('table/usera.type0') }}</option>
				  <option value="1" @if($data->type==1)selected @endif>{{ trans('table/usera.type1') }}</option>
				  </select>
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
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function submitadd(){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/usera',
		submitmsg:'{{ $pagetitles }}',
		backurl: '/manage/'+cnum+'/usera'
	});
}
function changesel(o){
	var sel = $(o.options[o.selectedIndex]).attr('selname');
	form('deptname').value=sel;
}
function clearxuan1(){
	form('superman').value='';
	form('superid').value='';
}

function searchxuan1(){
	$.rockmodeuser({
		title:'{{ trans('table/usera.superman_msg') }}',
		changetype:'usercheck',
		onselect:function(sna,sid){
			form('superman').value=sna;
			form('superid').value=sid;
		}
	});
}
</script>
@endsection