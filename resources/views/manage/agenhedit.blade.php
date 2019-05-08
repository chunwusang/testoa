@extends('manage.public')

@section('content')
<div class="container" align="center">
	<div align="left" style="max-width:550px">
		<div>
			<h3>{{ $pagetitles }}</h3>
			<hr class="head-hr" />
		</div>	
	
		<form name="myform" class="form-horizontal">
			
			<input type="hidden" value="{{ $data->id }}" name="id">
			<input type="hidden" value="{{ $cid }}" name="cid">
			
			<div align="center" style="padding:20px">
			<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($data->facesrc) }}" id="faceimg" width="50"><br>
			<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}...">
			</div>
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agenh.name') }}" required placeholder="{{ trans('table/agenh.name_msg') }}" value="{{ $data->name }}" maxlength="50" id="input_name" onblur="js.str(this)" name="name">
				</div>
			</div>
			
			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" @if($data->agentid>0) readonly @endif data-fields="{{ trans('table/agenh.num') }}" required placeholder="{{ trans('table/agenh.num_msg') }}" value="{{ $data->num }}" maxlength="30" id="input_num" onblur="js.str(this)" type="onlyen" name="num">
				</div>
			</div>
			
			<div class="form-group" inputname="face">
				<label for="input_face" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.face') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenh.face') }}" required placeholder="{{ trans('table/agenh.face_msg') }}" value="{{ $data->face }}" maxlength="255" id="input_face" name="face">
				</div>
			</div>
			
			<div class="form-group" inputname="atype">
				<label for="input_atype" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.atype') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenh.atype') }}" required placeholder="{{ trans('table/agenh.atype_msg') }}" value="{{ $data->atype }}" maxlength="20" id="input_atype" name="atype">
				</div>
			</div>
			
			<div class="form-group" inputname="usablename">
				<label class="col-sm-3 control-label">{{ trans('table/agenh.usablename') }}</label>
				<div class="col-sm-8">
					<input type="hidden" value="{{ $data->usableid }}" name="usableid">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('table/agenh.usablename') }}" placeholder="{{ trans('table/agenh.usablename_msg') }}" value="{{ $data->usablename }}" readonly name="usablename">
					  <span class="input-group-btn">
						<button class="btn btn-default" onclick="clearxuan()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
						<button class="btn btn-default" onclick="searchxuan()" type="button"><i class="glyphicon glyphicon-search"></i></button>
					  </span>
					</div>
				</div>
			</div>
			
			<div class="form-group" inputname="description">
				<label for="input_description"  class="col-sm-3 control-label">{{ trans('table/agenh.description') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/agenh.description') }}" placeholder="{{ trans('table/agenh.description_msg') }}" id="input_description" name="description">{{ $data->description }}</textarea>
				</div>
			</div>
			
			<div class="form-group" inputname="urlm">
				<label for="input_urlm" onblur="js.str(this)" class="col-sm-3 control-label">{{ trans('table/agenh.urlm') }}</label>
				<div class="col-sm-8">
				  <input @if($data->agentid>0) disabled @endif class="form-control" data-fields="{{ trans('table/agenh.urlm') }}" placeholder="{{ trans('table/agenh.urlm_msg') }}" value="{{ $data->urlm }}" maxlength="200" id="input_urlm" name="urlm">
				</div>
			</div>
			
			<div class="form-group" inputname="urlpc">
				<label for="input_urlpc" onblur="js.str(this)" class="col-sm-3 control-label">{{ trans('table/agenh.urlpc') }}</label>
				<div class="col-sm-8">
				  <input @if($data->agentid>0) disabled @endif class="form-control" data-fields="{{ trans('table/agenh.urlpc') }}" placeholder="{{ trans('table/agenh.urlpc_msg') }}" value="{{ $data->urlpc }}" maxlength="200" id="input_urlpc" name="urlpc">
				</div>
			</div>
			
			<div class="form-group" inputname="yylx">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.yylx') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_yylx" name="yylx">
				  <option value="0">{{ trans('table/agenh.yylx0') }}</option>
				  <option value="1" @if($data->yylx==1)selected @endif>{{ trans('table/agenh.yylx1') }}</option>
				  <option value="2" @if($data->yylx==2)selected @endif>{{ trans('table/agenh.yylx2') }}</option>
				  @if($data->agentid>0)
				  <option value="5" @if($data->yylx==5)selected @endif>{{ trans('table/agenh.yylx5') }}</option>
				  @endif
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="issy">
				<label for="input_issy" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.issy') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_issy" name="issy">
				  <option value="0">{{ trans('table/agenh.issy0') }}</option>
				  <option value="1" @if($data->issy==1)selected @endif>{{ trans('table/agenh.issy1') }}</option>
				  @if($data->agentid>0)
				  <option value="5" @if($data->issy==5)selected @endif>{{ trans('table/agenh.issy5') }}</option>
				  @endif
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenh.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agenh.sort') }}" required placeholder="{{ trans('table/agenh.sort_msg') }}" type="number" maxlength="10" value="{{ $data->sort }}" id="input_sort" name="sort">
				</div>
			</div>
			
			 @if($data->agentid>0 && $data->sysAgent->table)
			<div class="form-group" inputname="isflow">
				<label for="input_isflow" class="col-sm-3 control-label">{{ trans('table/agenh.isflow') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_isflow" name="isflow">
				  <option value="0">{{ trans('table/agenh.isflow0') }}</option>
				  <option value="1" @if($data->isflow==1)selected @endif>{{ trans('table/agenh.isflow1') }}({{ trans('table/agenh.isflow1_msg') }})</option> 
				  
				  </select>
				</div>
			</div>
			@endif
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
				  <label><input @if($data->status==1) checked @endif name="status" value="1" type="checkbox">{{ trans('table/agenh.status1') }}</label>&nbsp;
				  
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitles }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
	
			
		</form>	
	
	</div>
</div>
@endsection

@section('script')
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function submitadd(o){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/agenh_save',
		submitmsg:'{{ $pagetitles }}',
		backurl: '/manage/'+cnum+'/agenh'
	});
}
function clearxuan(){
	form('usablename').value='';
	form('usableid').value='';
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			get('faceimg').src = ret.viewpats;
			form('face').value = ret.imgpath;
		}
	});
	upbtn.changefile();
}
function searchxuan(){
	$.rockmodeuser({
		title:'{{ trans('table/agenh.usablename_msg') }}',
		changetype:'deptusercheck',
		onselect:function(sna,sid){
			form('usablename').value=sna;
			form('usableid').value=sid;
		}
	});
}
</script>
@endsection