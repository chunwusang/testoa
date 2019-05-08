<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ trans('base.logintext') }}</title>
<link href="{{ $bootstyle }}" rel="stylesheet">
</head>
<body  style="background:#f1f1f1">
   
<div class="container" align="center" style="margin-top:5%">
	<div style="max-width:500px" align="left">
		<div align="center"><h3>{{ config('app.name') }}</h3></div>
		<div class="panel panel-default">
			
			<div align="center">
				<img id="myface" style="margin:20px;width:100px;height:100px;border-radius:50%" src="/images/logo.png">
			</div>
			
			
			<div class="panel-body">
				<form class="form-horizontal" method="post" action="" name="myform">
					<input type="hidden" name="_token" value="{{ csrf_token() }}" />
					<div class="form-group" inputname="user">
						<label for="user" class="col-md-3 col-sm-3 control-label">{{ trans('users/login.user') }}</label>

						<div class="col-md-7 col-sm-7">
							<input id="user" maxlength="100" data-fields="{{ trans('users/login.user') }}" placeholder="{{ trans('users/login.user_msg') }}" onkeyup="if(event.keyCode==13)getpassobj().focus()" class="form-control" name="user" required autofocus>
						</div>
					</div>
					
					<!--
					<div class="form-group" inputname="captcha">
						<label class="col-md-3 col-sm-3 control-label">{{ trans('users/reg.captcha') }}</label>
						<div class="col-md-7 col-sm-7">
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
						<label class="col-md-3 col-sm-3 control-label">{{ trans('users/reg.mobileyzm') }}</label>
						<div class="col-md-7 col-sm-7">
							<div class="input-group">
							  <input class="form-control" data-fields="{{ trans('users/reg.mobileyzm') }}" maxlength="6" name="mobileyzm" placeholder="{{ trans('users/reg.mobileyzm_msg') }}" type="text" />
							  <span class="input-group-btn">
								<input class="btn btn-default" onclick="getcode(this)" value="{{ trans('users/reg.mobileyzm_get') }}" type="button">
							  </span>
							</div>
							<span id="myform_mobileyzm_errview"></span>
						</div>
					</div>
					-->
					
					<div class="form-group" inputname="pass">
						<label for="pass" class="col-md-3  col-sm-3 control-label">{{ trans('users/login.pass') }}</label>

						<div class="col-md-5 col-sm-5">
							<input name="pass" id="pass" type="hidden">
							<input maxlength="30" onblur="get('pass').value=this.value" data-fields="{{ trans('users/login.pass') }}" placeholder="{{ trans('users/login.pass_msg') }}" onkeyup="if(event.keyCode==13)submitadd()" onkeydown="get('pass').value=this.value" type="password" class="form-control" required>
						</div>
						<div class="col-md-3 col-sm-3" style="line-height:34px"><a href="{{ route('usersfind') }}">{{ trans('users/login.wjpass') }}</a></div>
					</div>

				   

					<div class="form-group">
						<div class="col-md-8  col-sm-offset-3 col-md-offset-3">
							<input type="button" name="submitbtn" onclick="submitadd()"  value="{{ trans('base.logintext') }}" class="btn btn-primary" />
							&nbsp;<span id="msgview">
							@if(config('app.openreg'))
							{{ trans('users/login.myzhanh') }}
							<a href="{{ route('usersreg') }}">{{ trans('users/login.regs') }}</a>
							@endif
							</span>
						  
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
		
	@include('layouts.footer')
</div>	

<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/base64-min.js"></script>
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script>
function submitadd(){
	// console.log('{{ route('apilogincheck') }}');
	// return;
	js.setoption('userszhang', get('user').value);
	js.setoption('userspass', get('pass').value);
	$.rockvalidate({
		url:'{{ route('apilogincheck') }}',
		
		submitmsg:'{{ trans('base.logintext') }}',
		onsubmitsuccess:function(ret){
			js.setoption(TOKENKEY, ret.data.token);
			js.setoption('face', ret.data.face);
			get('myface').src = ret.data.face;
			js.savecookie('bootstyle', ret.data.bootstyle);
			js.setmsg('{{ trans('users/login.loginsucc') }}','green');
			var burk = '{{ route('usersindex') }}',burl=js.request('backurl');
			if(burl){
				if(burl=='reim'){
					burk = '/reim/index.html';
				}else if(burl=='back'){
					js.back();
					return;
				}else{
					burk = jm.base64decode(burl);
				}
			}

			if(burk)js.location(burk);//跳转到主页,如果没有这句话 就会一直卡在登录页面 虽然成功了,只有刷新才能重新跳转,重定向
		}
	});
}

function initbody(){
	var face = js.getoption('face');
	if(face)get('myface').src = face;
	
	get('user').value=js.getoption('userszhang');
	get('pass').value=js.getoption('userspass');
	getpassobj().val(get('pass').value);
}

function getpassobj(){
	return $('input[type=password]');
}
</script>	
	
</body>
</html>
