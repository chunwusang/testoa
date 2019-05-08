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


			<input type="hidden" value="{{ $data->appCode_url }}" name="appCode_url">

			<div align="center" style="padding:20px">
				<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($data->appCode_url) }}" id="appCode_url" width="100"><br>
				<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="app下载二维码">
			</div>


			<div class="form-group" inputname="url">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> 官网域名</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.name') }}" required placeholder="{{ trans('table/users.name_msg') }}" value="{{ $data->url }}" maxlength="100" id="input_url" name="url">
				</div>
			</div>

			<div class="form-group" inputname="email">
				<label for="input_email" class="col-sm-3 control-label">联系邮箱</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.email') }}" placeholder="{{ trans('table/users.email_msg') }}" value="{{ $data->email }}" maxlength="100" id="input_email" name="email" type="email">
				</div>
			</div>


			<div class="form-group" inputname="workTime">
				<label for="input_nickname" class="col-sm-3 control-label">工作时间</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->workTime }}" maxlength="50" id="input_nickname" name="workTime" type="text">
				</div>
			</div>


			<div class="form-group" inputname="project_num">
				<label for="input_nickname" class="col-sm-3 control-label">项目数量</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)"   value="{{ $data->project_num }}" maxlength="50" id="input_nickname" name="project_num" type="text">
				</div>
			</div>

			<div class="form-group" inputname="influence_num">
				<label for="input_nickname" class="col-sm-3 control-label">影响力数量</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)"   value="{{ $data->influence_num }}" maxlength="50" id="input_nickname" name="influence_num" type="text">
				</div>
			</div>

			<div class="form-group" inputname="winning_num">
				<label for="input_nickname" class="col-sm-3 control-label">获奖项目数量</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)"   value="{{ $data->winning_num }}" maxlength="50" id="input_nickname" name="winning_num" type="text">
				</div>
			</div>

			<div class="form-group" inputname="distribution_num">
				<label for="input_nickname" class="col-sm-3 control-label">分布数量</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)"   value="{{ $data->distribution_num }}" maxlength="50" id="input_nickname" name="distribution_num" type="text">
				</div>
			</div>

			<div class="form-group" inputname="cerocd">
				<label for="input_nickname" class="col-sm-3 control-label">备案号</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->cerocd }}" maxlength="50" id="input_cerocd" name="cerocd" type="text">
				</div>
			</div>

			<div class="form-group" inputname="shop_url">
				<label for="input_nickname" class="col-sm-3 control-label">商城域名</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->shop_url }}" maxlength="50" id="input_shop_url" name="shop_url" type="text">
				</div>
			</div>

			<div class="form-group" inputname="oay_url">
				<label for="input_nickname" class="col-sm-3 control-label">OA云域名</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="this.value=strreplace(this.value)" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/users.nickname') }}" placeholder="{{ trans('table/users.nickname_msg') }}" value="{{ $data->oay_url }}" maxlength="50" id="input_nickname" name="oay_url" type="text">
				</div>
			</div>

			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">更新配置</button>
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
			get('appCode_url').src = ret.viewpats;
			form('appCode_url').value = ret.imgpath;
		}
	});
}
function xuantuan(){
	upbtn.changefile();
}
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('jctadmin_website_postconfig') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
</script>
@endsection