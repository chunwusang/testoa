@extends('admin.public')

@section('content')

<div class="container" align="center">
	<div align="left" style="max-width:800px">
		<div>
			<h3>{{ trans('admin/public.menu.platcog') }}</h3>
			<div>{{ trans('admin/platcog.platcogdesc') }}{!! $helpstr !!}</div>

			<hr class="head-hr" />
		</div>
		<div>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">{{ trans('admin/platcog.infotit') }}</a></li>
				<li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">{{ trans('admin/platcog.logotit') }}</a></li>
				<li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">{{ trans('admin/platcog.smstit') }}</a></li>
				<li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">{{ trans('admin/platcog.uploadtit') }}</a></li>
				<li role="presentation"><a href="#tab5" aria-controls="tab5" role="tab" data-toggle="tab">{{ trans('admin/platcog.guantit') }}</a></li>
				
			
			</ul>
			<form name="myform" class="form-horizontal" style="padding:20px">
			<div class="tab-content">
				
				<div role="tabpanel" class="tab-pane active" id="tab1">
					
					<div class="form-group">
						<label for="input_name" class="col-sm-4 control-label"> {{ trans('admin/platcog.name') }}(APP_NAME)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.name_msg') }}" value="{{ env('APP_NAME') }}" onblur="this.value=strreplace(this.value)" id="input_name" name="APP_NAME">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_nameadmin" class="col-sm-4 control-label"> {{ trans('admin/platcog.nameadmin') }}(APP_NAMEADMIN)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.nameadmin_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('APP_NAMEADMIN') }}"  id="input_nameadmin" name="APP_NAMEADMIN">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_url" class="col-sm-4 control-label"> {{ trans('admin/platcog.url') }}(APP_URL)</label>
						<div class="col-sm-8">
						  <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.url_msg') }}" value="{{ env('APP_URL') }}"  id="input_url" name="APP_URL">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_urllocal" class="col-sm-4 control-label"> {{ trans('admin/platcog.urllocal') }}(APP_URLLOCAL)</label>
						<div class="col-sm-8">
						  <input class="form-control" placeholder="{{ trans('admin/platcog.urllocal_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('APP_URLLOCAL') }}"  id="input_urllocal" name="APP_URLLOCAL">
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_debug" class="col-sm-4 control-label"> {{ trans('admin/platcog.debug') }}(APP_DEBUG)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_debug" name="APP_DEBUG">
						  <option value="">{{ trans('admin/platcog.debug_false') }}</option>
						  <option @if(config('app.debug'))selected @endif value="true">{{ trans('admin/platcog.debug_true') }}</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_openreg" class="col-sm-4 control-label"> {{ trans('admin/platcog.openreg') }}(APP_OPENREG)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_openreg" name="APP_OPENREG">
						  <option value="false">{{ trans('admin/platcog.openreg_false') }}</option>
						  <option @if(config('app.openreg'))selected @endif value="">{{ trans('admin/platcog.openreg_true') }}</option>
						  </select>
						</div>
					</div>
					
					<div class="form-group">
						<label for="input_randkey" class="col-sm-4 control-label"> {{ trans('admin/platcog.randkey') }}(ROCK_RANDKEY)</label>
						<div class="col-sm-8">
						  
						  
							<div class="input-group">
							  <input class="form-control" placeholder="{{ trans('admin/platcog.randkey_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('ROCK_RANDKEY') }}"  id="input_randkey" name="ROCK_RANDKEY">
							  <span class="input-group-btn">
								<button class="btn btn-default" onclick="reaterandkey()" type="button">{{ trans('base.createtext') }}</button>
							  </span>
							</div>
						  
						</div>
					</div>
					
				</div>
				<div role="tabpanel" class="tab-pane" id="tab2">
					
					<div class="col-sm-offset-4">
						<input name="APP_LOGO" type="hidden" value="{{ env('APP_LOGO') }}">
						<div><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ config('app.logo') }}" id="faceimg" width="100"></div>
						<div style="margin-top:5px"><input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}..."></div>
						
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="tab3">
					<div class="form-group">
						<label for="input_smsprovider" class="col-sm-4 control-label"> {{ trans('admin/platcog.smsprovider') }}(ROCK_SMSPROVIDER)</label>
						<div class="col-sm-8">
						  <select class="form-control" id="input_smsprovider" name="ROCK_SMSPROVIDER">
						  <option value="">{{ trans('admin/platcog.smsprovider_xinhu') }}(smsxinhu)</option>
						  <option value="smsali" @if(config('rocksms.provider')=='smsali')selected @endif>{{ trans('admin/platcog.smsprovider_ali') }}(smsali)</option>
						  <option value="smsxinxi" @if(config('rocksms.provider')=='smsxinxi')selected @endif>{{ trans('admin/platcog.smsprovider_xinxi') }}(smsxinxi)</option>
						  <option value="smsyunpian" @if(config('rocksms.provider')=='smsyunpian')selected @endif>{{ trans('admin/platcog.smsprovider_yunpian') }}(smsyunpian)</option>
						  </select>
						</div>
					</div>
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab4">
					<div class="form-group">
						<label for="input_baseurl" class="col-sm-4 control-label"> {{ trans('admin/platcog.baseurl') }}(ROCK_BASEURL)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.baseurl_msg') }}" value="{{ env('ROCK_BASEURL') }}"  id="input_baseurl" name="ROCK_BASEURL">
						</div>
					</div>
					<div class="form-group">
						<label for="input_basekey" class="col-sm-4 control-label"> {{ trans('admin/platcog.basekey') }}(ROCK_BASEKEY)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.basekey_msg') }}" value="{{ env('ROCK_BASEKEY') }}"  id="input_basekey" name="ROCK_BASEKEY">
						</div>
					</div>
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab5">
					<div class="form-group">
						<label for="input_xinhu" class="col-sm-4 control-label"> {{ trans('admin/platcog.xinhu') }}(ROCK_XINHU)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.xinhu_msg') }}" value="{{ env('ROCK_XINHU') }}"  id="input_xinhu" name="ROCK_XINHU">
						</div>
					</div>
					<div class="form-group">
						<label for="input_urly" class="col-sm-4 control-label"> {{ trans('admin/platcog.urly') }}(ROCK_URLY)</label>
						<div class="col-sm-8">
						   <input class="form-control" onblur="this.value=strreplace(this.value)" placeholder="{{ trans('admin/platcog.urly_msg') }}" value="{{ env('ROCK_URLY') }}"  id="input_urly" name="ROCK_URLY">
						</div>
					</div>
					<div class="form-group">
						<label for="input_xinhukey" class="col-sm-4 control-label"> {{ trans('admin/platcog.xinhukey') }}(ROCK_XINHUKEY)</label>
						<div class="col-sm-8">
						   <input class="form-control" placeholder="{{ trans('admin/platcog.xinhukey_msg') }}" onblur="this.value=strreplace(this.value)" value="{{ env('ROCK_XINHUKEY') }}"  id="input_xinhukey" name="ROCK_XINHUKEY">
						   <div>{{ trans('admin/platcog.xinhukey_help') }} {!! c('help')->show('xhkey') !!}</div>
						</div>
					</div>
				</div>
				
				<div style="margin-top:20px" class="form-group">
					<div class="col-sm-offset-4">
					 <button class="btn btn-success" name="submitbtn" onclick="submitadd()" type="button">{{ trans('base.savetext') }}</button>
					</div>
				</div>	
			</div>
			</form>
		</div >
	</div>
</div>
@endsection

@section('script')
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script>
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('adminmanagesave','cog') }}',
		submitmsg:'{{ trans('base.savetext') }}',
		autoback:false,
		onsubmitsuccess:function(){
			//form('submitbtn').disabled=false;
			
		}
	});
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			get('faceimg').src = ret.viewpats;
			form('APP_LOGO').value = ret.viewpats;
		}
	});
	upbtn.changefile();
}
function reaterandkey(){
	$.get('/base/randkey', function(ret){
		form('ROCK_RANDKEY').value=ret;
	});
}
</script>
@endsection