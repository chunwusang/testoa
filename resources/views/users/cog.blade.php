@extends('users.public')

@section('content')
<div class="container" align="center">
	<div align="left" style="max-width:800px">
	
		<div>
			<h3>{{ trans('users/cog.title') }}</h3>
			<hr class="head-hr" />
		</div>	

		<div >
			
				
			  <ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">{{ trans('users/cog.basetab') }}</a></li>
				<li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">{{ trans('users/cog.passedit') }}</a></li>
				<li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">{{ trans('users/cog.faceedit') }}</a></li>
				<li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">{{ trans('users/cog.mobileedit') }}</a></li>
				<!--
				<li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">{{ trans('users/cog.emailedit') }}</a></li>-->
				<li role="presentation"><a href="#tab6" aria-controls="tab6" role="tab" data-toggle="tab">{{ trans('users/cog.styleedit') }}</a></li>
			  </ul>

			 
			  <div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="tab1">
				
				<div style="margin:20px;max-width:400px">
					<form name="myform" class="form-horizontal">
					<div class="form-group" inputname="name">
						<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.name') }}</label>
						<div class="col-sm-8">
						  <input class="form-control" data-fields="{{ trans('table/users.name') }}" required placeholder="{{ trans('table/users.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
						</div>
					</div>
					<div class="form-group" inputname="nickname">
						<label for="input_nickname" class="col-sm-3 control-label">{{ trans('table/users.nickname') }}</label>
						<div class="col-sm-8">
						  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->nickname }}" maxlength="200" id="input_nickname" name="nickname" type="text">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-8">
							<button type="button" name="submitbtn" onclick="savebase('myform','base')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
						</div>
					</div>
					</form>
				</div>
				
				
				</div>
				<div role="tabpanel" class="tab-pane" id="tab2">
				
				<div style="margin:20px;max-width:400px">
					<form name="myformpass" class="form-horizontal">
					<div class="form-group" inputname="oldpass">
						<label for="input_oldpass" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.oldpass') }}</label>
						<div class="col-sm-8">
						  <input class="form-control" data-fields="{{ trans('table/users.oldpass') }}" required placeholder="{{ trans('table/users.oldpass_msg') }}" value="" maxlength="30" id="input_oldpass" name="oldpass" type="password">
						</div>
					</div>
					<div class="form-group" inputname="newpass">
						<label for="input_newpass" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.newpass') }}</label>
						<div class="col-sm-8">
						  <input class="form-control" required data-fields="{{ trans('table/users.newpass') }}" placeholder="{{ trans('table/users.newpass_msg') }}" value="" maxlength="30" id="input_newpass" name="newpass" type="password">
						</div>
					</div>
					<div class="form-group" inputname="newpass1">
						<label for="input_newpass1" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/users.newpass1') }}</label>
						<div class="col-sm-8">
						  <input class="form-control" required data-fields="{{ trans('table/users.newpass1') }}" placeholder="{{ trans('table/users.newpass1_msg') }}" value="" maxlength="30" id="input_newpass1" name="newpass1" type="password">
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-8">
							<button type="button" name="submitbtn" onclick="savebase('myformpass','pass')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
						</div>
					</div>
					</form>
				</div>
				
				</div>
				<div role="tabpanel" class="tab-pane" id="tab3">
					
					<div style="margin:20px;max-width:400px">
						<form name="myformface">
						<input name="face" type="hidden" value="{{ $data->face }}">
						<div><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ $data->face }}" id="face" width="100"></div>
						<div style="margin-top:5px"><input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}..."></div>
						<div style="margin-top:5px"><button type="button" name="submitbtn" onclick="savebase('myformface','face')" class="btn btn-primary">{{ trans('base.savetext') }}</button></div>
						</form>
					</div>
					
				</div>
				<div role="tabpanel" class="tab-pane" id="tab4">
					<div style="margin:20px;max-width:500px">
					<div style="padding:20px" align="center">{{ trans('table/users.bangdmobile') }}{{ $data->mobilecode }} {{ substr($data->mobile,0,3) }}****{{ substr($data->mobile,-4) }}，<input type="button" class="btn btn-default btn-xs" onclick="$('#editmobile').toggle()" value="{{ trans('base.xiugtext') }}"></div>
					<form name="myformmobile" id="editmobile" style="display:none" class="form-horizontal">
					<div class="form-group" inputname="mobile">
						<label class="col-sm-3 col-xs-3 control-label"><font color=red>*</font> {{ trans('users/reg.mobile') }}</label>
						<div class="col-sm-3 col-xs-5" style="padding-right:0;">
							<select name="mobilecode" class="form-control"><option value="">+86</option></select>
						</div>
						<div  class="col-sm-5 col-xs-12">
							 <input class="form-control" data-fields="{{ trans('users/reg.mobile') }}" required name="mobile" placeholder="{{ trans('users/reg.mobile_msg') }}" maxlength="11" type="mobile" />
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
					<div class="form-group">
						<div class="col-sm-3"></div>
						<div class="col-sm-8">
							<button type="button" name="submitbtn" onclick="savebase('myformmobile','mobile')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
						</div>
					</div>
					</form>
				</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab5">
				
				</div>
				<div role="tabpanel" class="tab-pane" id="tab6">
					<form name="myformstyle" class="form-horizontal">
					<div class="row" style="margin:20px">
						@foreach($stylearr as $rs)
						<div class="col-md-3 col-xs-6">
							<div class="thumbnail">
							<div><label><input {{ $rs['checked'] }} value="{{ $rs['value'] }}" onclick="changestyle(this)" name="stylename" type="radio">{{ $rs['name'] }}</label></div>
							</div>
						</div>
						@endforeach
					</div>
	
					<div style="margin-left:40px">
						<button type="button" name="submitbtn" onclick="savebase('myformstyle','style')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
					</div>
					</form>
				</div>
				
				
			  </div>
		
		</div>

	</div>
</div>
@endsection

@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script>
function initbody(){
	
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			get('face').src = ret.viewpats;
			form('face','myformface').value = ret.imgpath;
		}
	});
	upbtn.changefile();
}
var styss = '';
function changestyle(o){
	var val = o.value;
	if(val=='')val = '{{ config('rock.usersstyle') }}';
	get('bootstyle').href = val;
	styss = o.value;
}


function savebase(na, lx){
	var can = {
		url:'/api/we/users_'+lx+'',
		autoback:false,
		formname:na,
		submitparams:{'device':device},
		submitmsg:'{{ trans('base.savetext') }}'
	};
	if(lx=='pass')can.oncheck=function(na,val,da){
		if(na=='newpass'){
			if(val.length<6)return false;
			if(!/[a-zA-Z]{1,}/.test(val) || !/[0-9]{1,}/.test(val)){
				return '{{ trans('table/users.newpass1_err0') }}';
			}
		}
		if(na=='newpass1'){
			if(val!=da.newpass)return '{{ trans('table/users.newpass1_err1') }}';
		}
	};
	if(lx=='style'){
		js.savecookie('bootstyle', styss);
	}
	$.rockvalidate(can);
}

function getcode(o1){
	var sj = form('mobile','myformmobile').value;
	if(!sj){
		js.msg('msg','{{ trans('users/reg.mobile_msg') }}');return;
	}
	o1.disabled = true;
	js.ajax('/api/we/base_getcode',{
		'mobile':sj,
		'mobilecode':form('mobilecode','myformmobile').value,
		'device':device,
		'gtype':'bind'
	},function(ret){
		js.msg('success', '{{ trans('users/reg.mobileyzm_get1') }}');
		dshitime(60, o1);
	},'get',function(){
		o1.disabled = false;
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
@endsection