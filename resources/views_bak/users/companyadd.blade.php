@extends('users.public')

@section('content')


<div class="container" align="center">
	<div align="left" style="max-width:500px">
		<div>
			<h3>{{ $pagetitle }}</h3>
			<hr class="head-hr" />
		</div>

		@if(Auth::user()->flaskm<=Auth::user()->flasks)
			@if (Auth::user()->flaskm==0)
			<div align="center" style="padding:30px"><h4><i class="glyphicon glyphicon-remove-circle"></i> {{ trans('table/company.extnot') }}</h4></div>
			@else
			<div align="center" style="padding:30px"><h4><i class="glyphicon glyphicon-remove-circle"></i> {{ sprintf(trans('table/company.extcjs'), Auth::user()->flasks) }}</h4></div>	
			@endif
		@else
		<form name="myform" class="form-horizontal">
			<div class="form-group" >
				<label  class="col-sm-3 control-label"></label>
				<div align="center" class="col-sm-8">
					<img src="/images/nologo.png" id="logoview" width="100">
					<div style="margin-top:5px"><input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}..."></div>
				</div>
			</div>
			
			<div class="form-group" inputname="logo">
				<label for="input_logo" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.logo') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="loginblur(this)" required data-fields="{{ trans('table/company.logo') }}" id="input_logo" maxlength="200" placeholder="{{ trans('table/company.logo_msg') }}" name="logo">
				</div>
			</div>
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.name') }}" required placeholder="{{ trans('table/company.name_msg') }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>		
			
			<div class="form-group" inputname="shortname">
				<label for="input_shortname" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.shortname') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.shortname') }}" required id="input_shortname" maxlength="50" placeholder="{{ trans('table/company.shortname_msg') }}" name="shortname">
				</div>
			</div>
			
			
			
			<div class="form-group" inputname="tel">
				<label for="input_tel" class="col-sm-3 control-label">{{ trans('table/company.tel') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" maxlength="50" data-fields="{{ trans('table/company.tel') }}" id="input_tel" name="tel">
				</div>
			</div>
			
			<div class="form-group" inputname="contacts">
				<label for="input_contacts" class="col-sm-3 control-label">{{ trans('table/company.contacts') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.contacts') }}" id="input_contacts" maxlength="50" name="contacts">
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-9">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitle }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
		
			
			
		</form>
		
		@endif
	</div>
</div>
@endsection

@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script>
function submitadd(){
	$.rockvalidate({
		url:'/api/we/company_create',
		submitmsg:'{{ $pagetitle }}',
		backurl:'{{ route('usersmanage') }}'
	});
}
function loginblur(o1){
	var val = o1.value;
	if(!val)val='/images/nologo.png';
	get('logoview').src = val;
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			form('logo').value = ret.imgpath;
			get('logoview').src = ret.viewpats;
		}
	});
	upbtn.changefile();
}
</script>
@endsection