@extends('manage.public')

@section('content')
<div class="container" align="center">
    <div align="left" style="max-width:600px">
	
		<div>
			<h3>{{ trans('manage/public.menu.wxqycog') }}</h3>
			<hr class="head-hr" />
		</div>
		<!-- <div>拉拉阿拉</div> -->
		<form name="myform" class="form-horizontal">
			
			<div class="form-group" inputname="wxqycorpid">
				<label for="input_wxqycorpid"  class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/wxqy.wxqycorpid') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqycorpid') }}" value="{{ $wxqycorpid }}" placeholder="{{ trans('table/wxqy.wxqycorpid_msg') }}" id="input_wxqycorpid" name="wxqycorpid">
				</div>
			</div>
			
			<div class="form-group" inputname="wxqysecret">
				<label for="input_wxqysecret"  class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/wxqy.wxqysecret') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqysecret') }}" placeholder="{{ trans('table/wxqy.wxqysecret_msg') }}" id="input_wxqysecret" name="wxqysecret">{{ $wxqysecret }}</textarea>
				  <div>{!! trans('table/wxqy.wxqysecret_tishi') !!}</div>
				</div>
			</div>
			
			<div class="form-group" inputname="wxqydepid">
				<label for="input_wxqydepid"  class="col-sm-3 control-label">{{ trans('table/wxqy.wxqydepid') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqydepid') }}" value="{{ $wxqydepid }}" placeholder="{{ trans('table/wxqy.wxqydepid_msg') }}" id="input_wxqydepid" name="wxqydepid">
				</div>
			</div>
			<div class="form-group" >
				<label for="input_wxqytodo"  class="col-sm-3 control-label">{{ trans('table/wxqy.wxqytodo') }}</label>
				<div class="col-sm-8">
				  <label><input type="checkbox" @if($wxqytodo=='1')checked @endif name="wxqytodo" id="input_wxqytodo" value="1">{{ trans('table/wxqy.wxqytodo_msg') }}</label>
				</div>
			</div>
			
			<div align="center" class="alert alert-info">{{ trans('table/wxqy.wxqyhuimsg') }}</div>
			
			<div class="form-group" inputname="wxqyhuiurl">
				<label for="input_wxqyhuiurl"  class="col-sm-3 control-label"> {{ trans('table/wxqy.wxqyhuiurl') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqyhuiurl') }}" value="{{ $wxqyhuiurl }}" readonly placeholder="{{ trans('table/wxqy.wxqyhuiurl_msg') }}" id="input_wxqyhuiurl" name="wxqyhuiurl">
				</div>
			</div>
			
			<div class="form-group" inputname="wxqyhuitoken">
				<label for="input_wxqyhuitoken"  class="col-sm-3 control-label">{{ trans('table/wxqy.wxqyhuitoken') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqyhuitoken') }}" value="{{ $wxqyhuitoken }}" placeholder="{{ trans('table/wxqy.wxqyhuitoken_msg') }}" id="input_wxqyhuitoken" name="wxqyhuitoken">
				</div>
			</div>
			
			<div class="form-group" inputname="wxqyaeskey">
				<label for="input_wxqyaeskey"  class="col-sm-3 control-label">{{ trans('table/wxqy.wxqyaeskey') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/wxqy.wxqyaeskey') }}"  placeholder="{{ trans('table/wxqy.wxqyaeskey_msg') }}" id="input_wxqyaeskey" name="wxqyaeskey">{{ $wxqyaeskey }}</textarea>
				</div>
			</div>
			
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ trans('base.savetext') }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
	
			
		</form>	
		
	</div>	
</div>
@endsection


@section('script')

<script>
function submitadd(o){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/wxqy_savecog',
		submitmsg:'{{ trans('base.savetext') }}',
		autoback:false
	});
}
</script>
@endsection