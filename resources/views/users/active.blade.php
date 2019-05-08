@extends('users.public')

@section('content')


<div class="container" align="center">
	<div align="left" style="max-width:550px">
		<div>
			<h3>{{ $pagetitle }}</h3>
			<hr class="head-hr" />
		</div>

		
		<form name="myform" class="form-horizontal">
			<input type="hidden" name="aid" value="{{ $ars->id }}">
			<div class="form-group">
				<label class="col-sm-3 control-label"> {{ trans('users/manage.joindanwei') }}</label>
				<div class="col-sm-8">
				{{ $ars->companyname }}
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"> {{ trans('users/reg.mobile') }}</label>
				<div class="col-sm-8">
				{{ substr($ars->mobile,0,3) }}****{{ substr($ars->mobile,-4) }}
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
				<div class="col-sm-9">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitle }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
		
			
			
		</form>
		
		
	</div>
</div>
@endsection

@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>
<script>
function submitadd(){
	
	$.rockvalidate({
		url:'{{ route('apiwepost','company_joinactive') }}',
		submitmsg:'{{ $pagetitle }}',
		submitparams:{'device':device},
		backurl:'{{ route('usersmanage') }}'
	});
}

function getcode(o1){
	o1.disabled = true;
	js.ajax('/api/we/base_getcode',{
		'aid':'{{ $ars->id }}',
		'mobilecode':'{{ $ars->mobilecode }}',
		'gtype':'join',
		'device':device
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