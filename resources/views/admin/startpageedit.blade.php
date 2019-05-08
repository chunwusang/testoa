@extends('admin.public')

@section('content')
	<style>
		.input_date, .subtable .input_date {
			background: url(/images/date.png) no-repeat right;
			cursor: pointer;
		}
	</style>
<div class="container" align="center">
	<div align="left" style="max-width:550px">
		<div>
			<h3>{{ $pagetitle }}</h3>
			<div>{!! $helpstr !!}</div>
			<hr class="head-hr" />
		</div>	
	
		<form name="myform" class="form-horizontal">
			<input type="hidden" value="{{ $data->id }}" name="id">
			<input type="hidden" value="{{ $data->page_img }}" name="page_img">

			<div align="center" style="padding:20px">
			<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($data->page_img) }}" id="page_img" width="100"><br>
			<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}...">
			</div>
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 开始时间</label>
				<div class="col-sm-8">
					<input class="inputs input_date form-control" readonly="" name="startdt" type="text" value="{{ $data->startdt }}" placeholder="" onclick="js.datechange(this,'datetime')">
				</div>
			</div>
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 结束时间</label>
				<div class="col-sm-8">
					<input class="inputs input_date form-control" readonly="" name="enddt" type="text" value="{{ $data->enddt }}" placeholder="" onclick="js.datechange(this,'datetime')">
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

<script type="text/javascript" src="{{ config('app.url') }}/js/jsm.js"></script>
<link rel="stylesheet" href="{{ config('app.url') }}/res/plugin/jquery-rockdatepicker.css"/>
<script src="{{ config('app.url') }}/res/plugin/jquery-rockdatepicker.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script>
function initbody(){
	upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			get('page_img').src = ret.viewpats;
			form('page_img').value = ret.imgpath;
		}
	});
}
function xuantuan(){
	upbtn.changefile();
}
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('jctadmin_startpageedit') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
</script>
@endsection