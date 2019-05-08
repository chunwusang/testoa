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
			<input type="hidden" value="{{ $data->logo }}" name="logo">
			
		
			<div align="center" style="padding:20px">
			<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ $data->logo }}" id="face" width="100"><br>
			<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}...">
			</div>
		
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('table/company.id') }}</label>
				<div class="col-sm-8" style="line-height:40px">{{ $data->id }}</div>
			</div>
			
			
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.name') }}" required placeholder="{{ trans('table/company.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>
			
			<div class="form-group" inputname="shortname">
				<label for="input_shortname" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.shortname') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.shortname') }}" required placeholder="{{ trans('table/company.shortname_msg') }}" value="{{ $data->shortname }}" maxlength="20" id="input_shortname" name="shortname">
				</div>
			</div>
			
			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.num') }}" required placeholder="{{ trans('table/company.num_msg') }}" value="{{ $data->num }}" maxlength="6" id="input_num" name="num">
				</div>
			</div>
			
			<div class="form-group" inputname="contacts">
				<label for="input_contacts" class="col-sm-3 control-label">{{ trans('table/company.contacts') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/company.contacts') }}" placeholder="{{ trans('table/company.contacts_msg') }}" value="{{ $data->contacts }}" maxlength="200" id="input_contacts" name="contacts" type="text">
				</div>
			</div>
			
			<div class="form-group" inputname="tel">
				<label for="input_tel" class="col-sm-3 control-label">{{ trans('table/company.tel') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/company.tel') }}" placeholder="{{ trans('table/company.tel_msg') }}" value="{{ $data->tel }}" maxlength="50" id="input_tel" name="tel" type="text">
				</div>
			</div>
			
			<div class="form-group" inputname="flaskm">
				<label for="input_flaskm" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/company.flaskm') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/company.flaskm') }}" required  type="number" maxlength="3" value="{{ $data->flaskm }}" id="input_flaskm" name="flaskm">
				</div>
			</div>
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/company.status') }}{{ trans('table/company.status1') }}</label>&nbsp;
					
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
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		'thumbnail':'200x200',
		onsuccess:function(ret){
			get('face').src = ret.viewpats;
			form('logo').value = ret.imgpath;
		}
	});
	upbtn.changefile();
}
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('admincompanysave') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
</script>
@endsection