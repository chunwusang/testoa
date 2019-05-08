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
			
			<input type="hidden" value="{{ $data->face }}" name="face">
			
		
			<div align="center" style="padding:20px">
			<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($data->facesrc) }}" id="face" width="100"><br>
			<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}...">
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('table/users.id') }}</label>
				<div class="col-sm-8" style="line-height:40px">{{ $data->id }}</div>
			</div>
			
			<div class="form-group" inputname="mobile">
				<label for="input_mobile" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.mobile') }}</label>
				<div class="col-sm-3">
					<select @if($data->iseditmobile==0)readonly @endif name="mobilecode" class="form-control"><option value="">+86</option></select>
				</div>
				<div style="padding-left:0;"  class="col-sm-5">
				  <input class="form-control" @if($data->iseditmobile==0)readonly @endif data-fields="{{ trans('table/users.mobile') }}" required placeholder="{{ trans('table/users.mobile_msg') }}" value="{{ $data->mobile }}" maxlength="11" id="input_mobile" type="mobile" name="mobile">
				</div>
			</div>
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.name') }}" required placeholder="{{ trans('table/users.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>
			
			<div class="form-group" inputname="userid">
				<label for="input_userid" class="col-sm-3 control-label">{{ trans('table/users.userid') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.userid') }}" placeholder="{{ trans('table/users.userid_msg') }}" value="{{ $data->userid }}" maxlength="50" type="onlyen" id="input_userid" name="userid">
				</div>
			</div>
			
			<div class="form-group" inputname="nickname">
				<label for="input_nickname" class="col-sm-3 control-label">{{ trans('table/users.nickname') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->nickname }}" maxlength="50" id="input_nickname" name="nickname" type="text">
				</div>
			</div>
			
			<div class="form-group" inputname="email">
				<label for="input_email" class="col-sm-3 control-label">{{ trans('table/users.email') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.email') }}" placeholder="{{ trans('table/users.email_msg') }}" value="{{ $data->email }}" maxlength="100" id="input_email" name="email" type="email">
				</div>
			</div>
			
			<div class="form-group" inputname="flaskm">
				<label for="input_flaskm" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.flaskm') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/users.flaskm') }}" required  type="number" maxlength="3" value="{{ $data->flaskm }}" id="input_flaskm" name="flaskm">
				</div>
			</div>
			
			<div class="form-group" inputname="password">
				<label for="input_password" class="col-sm-3 control-label">{{ trans('table/users.password') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.password') }}" placeholder="{{ trans('table/users.password_msg') }}" value="" maxlength="100" id="input_password" name="password" type="onlyen">
				</div>
			</div>
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/users.status') }}{{ trans('table/users.status1') }}</label>&nbsp;
					
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitle }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
	
			
		</form>	
	
	</div>
</div>
@endsection

@section('script')
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script>
function initbody(){
	upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			get('face').src = ret.viewpats;
			form('face').value = ret.imgpath;
		}
	});
}
function xuantuan(){
	upbtn.changefile();
}
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('apiadmin','users') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
</script>
@endsection