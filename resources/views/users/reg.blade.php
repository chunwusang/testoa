<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ trans('users/reg.title') }}</title>
<link href="{{ $bootstyle }}" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<style>
	.control-label{padding-right:0px}
</style>
</head>
<body>

<div class="container" align="center">
	<div align="left" style="max-width:450px;">
		<div align="center">
			<h3>{{ config('app.name') }}_{{ trans('users/reg.title') }}</h3>
			<hr class="head-hr" />
		</div>	
	
		<form name="myform" class="form-horizontal">
			
			<div class="form-group" inputname="mobile">
				<label class="col-sm-3 col-xs-3 control-label"><font color=red>*</font> {{ trans('users/reg.mobile') }}</label>
				<div class="col-sm-3 col-xs-5" style="padding-right:0;">
					<select name="mobilecode" class="form-control"><option value="">+86</option></select>
				</div>
				<div  class="col-sm-5 col-xs-12">
					 <input class="form-control" data-fields="{{ trans('users/reg.mobile') }}" required name="mobile" placeholder="{{ trans('users/reg.mobile_msg') }}" maxlength="11" type="mobile" />
				</div>
			</div>
			
			<div class="form-group" inputname="captcha">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('users/reg.captcha') }}</label>
				<div class="col-sm-8">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('users/reg.captcha') }}" name="captcha" type="number" placeholder="{{ trans('users/reg.captcha_msg') }}" maxlength="2" />
					  <span style="padding:0 2px" class="input-group-addon">
						<img style="margin-top:5px" onclick="clickcaptcha()" id="imgcaptcha" src="{{ route('base','captcha') }}">
					  </span>
					</div>
					<span id="myform_captcha_errview"></span> 
				</div>
			</div>
			
			<div class="form-group" inputname="mobileyzm">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('users/reg.mobileyzm') }}</label>
				<div class="col-sm-8">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('users/reg.mobileyzm') }}" maxlength="6" required name="mobileyzm" placeholder="{{ trans('users/reg.mobileyzm_msg') }}" type="text" />
					  <span class="input-group-btn">
						<input class="btn btn-default" onclick="getcode(this)" value="{{ trans('users/reg.mobileyzm_get') }}" type="button">
					  </span>
					</div>
					<span id="myform_mobileyzm_errview"></span>
				</div>
			</div>
			
			<div class="form-group" inputname="name">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('users/reg.name') }}</label>
				<div class="col-sm-8"><input class="form-control" data-fields="{{ trans('users/reg.name') }}" maxlength="50" required name="name" placeholder="{{ trans('users/reg.name_msg') }}" type="text" /></div>
			</div>
			
			<div class="form-group" inputname="pass">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('users/reg.pass') }}</label>
				<div class="col-sm-8"><input class="form-control" data-fields="{{ trans('users/reg.pass') }}" maxlength="20" required name="pass" placeholder="{{ trans('users/reg.pass_msg') }}" type="password" /></div>
			</div>
			
			<div class="form-group" inputname="pass1">
				<label class="col-sm-3 control-label"><font color=red>*</font> {{ trans('users/reg.pass1') }}</label>
				<div class="col-sm-8"><input class="form-control" data-fields="{{ trans('users/reg.pass1') }}" required  maxlength="20" name="pass1"  placeholder="{{ trans('users/reg.pass1_msg') }}" type="password" /></div>
			</div>
			
			
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<input value="{{ trans('users/reg.regbtn') }}" name="submitbtn" type="button" onclick="submitreg(this)" class="btn btn-primary" />
					&nbsp;<span id="msgview">{{ trans('users/reg.reg_msg') }}<a href="{{ route('userslogin') }}">{{ trans('users/reg.logintxt') }}</a></span>
				</div>
			</div>
			
		</form>	
	
	</div>
</div>

<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>

<script>
function clickcaptcha(){
	var o = get('imgcaptcha');
	o.src=o.src+'?'+Math.random()
}

function submitreg(o1){
	$.rockvalidate({
		url:'{{ route('apiregcheck') }}',
		submitmsg:'{{ trans('users/reg.title') }}',
		submitparams:{'device':device},
		oncheck:function(na,val,da){
			if(na=='pass'){
				if(val.length<6)return false;
			}
			if(na=='pass1'){
				if(val!=da.pass)return '{{ trans('users/reg.pass1_err1') }}';
			}
		},
		onvaliderror:function(na){
			if(na=='captcha')clickcaptcha();
		},
		backurl: '{{ route('userslogin') }}',
		okbtn:'{{ trans('users/reg.logintxt') }}'
	});
}

function getcode(o1){
	var sj = form('mobile').value,yzm=form('captcha').value;
	if(sj.length!=11){
		js.msg('msg','{{ trans('users/reg.mobile_msg0') }}');
		form('mobile').focus();
		return;
	}
	if(yzm==''){
		js.msg('msg','{{ trans('users/reg.captcha_msg') }}');
		form('captcha').focus();
		return;
	}
	o1.disabled = true;
	js.ajax('{{ route('base','getcode') }}',{
		'mobile':sj,
		'mobilecode':form('mobilecode').value,
		'device':device,
		'captcha':yzm,
		'gtype':'reg'
	},function(ret){
		js.msg('success', '{{ trans('users/reg.mobileyzm_get1') }}');
		dshitime(60, o1);
	},'get',function(){
		o1.disabled = false;
		clickcaptcha();
	});
}
function dshitime(sj,o1){
	if(sj==0){
		o1.disabled=false;
		o1.value='{{ trans('users/reg.mobileyzm_get2') }}';
		return;
	}
	o1.disabled=true;
	o1.value=''+sj+'';
	setTimeout(function(){dshitime(sj-1, o1)},1000);
}
</script>
	
</body>
</html>
