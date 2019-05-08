@extends('manage.public')

@section('content')
<div class="container" align="center">
    <div align="left" style="max-width:800px">
	
		<div>
			<h3>{{ trans('manage/cog.title') }}</h3>
			<hr class="head-hr" />
		</div>
		
		<div >
		<div>啦啦啦</div>
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active"><a href="#tab1" aria-controls="tab1" role="tab" data-toggle="tab">{{ trans('manage/cog.basetab') }}</a></li>
				
				<li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab" data-toggle="tab">{{ trans('manage/cog.logoedit') }}</a></li>
				
				<li role="presentation"><a href="#tab3" aria-controls="tab3" role="tab" data-toggle="tab">{{ trans('manage/cog.createedit') }}</a></li>
				
				<li role="presentation"><a href="#tab4" aria-controls="tab4" role="tab" data-toggle="tab">{{ trans('manage/cog.jieshan') }}</a></li>
			</ul>
		  
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="tab1">
					
					<div style="margin:20px;max-width:500px">
						<form name="myform" class="form-horizontal">
						<input type="hidden" value="{{ $data->num }}" name="cnum">
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.id') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px">{{ $data->id }}</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.num') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px">{{ $data->num }}</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.flaskm') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px">{{ $data->flaskm }}/{{ $data->flasks }}</div>
							</div>
						</div>
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.created_at') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px">{{ $data->created_at }}</div>
							</div>
						</div>
						<div class="form-group" inputname="name">
							<label for="input_name" class="col-sm-4 control-label"><font color=red>*</font> {{ trans('table/company.name') }}</label>
							<div class="col-sm-8">
							  <input class="form-control" data-fields="{{ trans('table/company.name') }}" required placeholder="{{ trans('table/company.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
							</div>
						</div>
						<div class="form-group" inputname="shortname">
							<label for="input_shortname" class="col-sm-4 control-label">{{ trans('table/company.shortname') }}</label>
							<div class="col-sm-8">
							  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/company.shortname') }}" placeholder="{{ trans('table/company.shortname_msg') }}" value="{{ $data->shortname }}" maxlength="20" id="input_nickname" name="shortname" type="text">
							</div>
						</div>
						
						<div class="form-group" inputname="contacts">
							<label for="input_contacts" class="col-sm-4 control-label">{{ trans('table/company.contacts') }}</label>
							<div class="col-sm-8">
							  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/company.contacts') }}" placeholder="{{ trans('table/company.contacts_msg') }}" value="{{ $data->contacts }}" maxlength="200" id="input_contacts" name="contacts" type="text">
							</div>
						</div>
						
						<div class="form-group" inputname="tel">
							<label for="input_tel" class="col-sm-4 control-label">{{ trans('table/company.tel') }}</label>
							<div class="col-sm-8">
							  <input class="form-control" onblur="this.value=strreplace(this.value)" data-fields="{{ trans('table/company.tel') }}" placeholder="{{ trans('table/company.tel_msg') }}" value="{{ $data->tel }}" maxlength="50" id="input_tel" name="tel" type="text">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-4"></div>
							<div class="col-sm-8">
								<button type="button" name="submitbtn" onclick="savebase('myform','base')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
								<span id="msgview_base"></span>
							</div>
						</div>
						</form>
					</div>
				
				</div>
				<div role="tabpanel" class="tab-pane" id="tab2">
					
					<div style="margin:20px;margin-left:120px;;max-width:400px">
						<form name="myformface">
						<input type="hidden" value="{{ $data->num }}" name="cnum">
						<input name="face" type="hidden" value="{{ $data->logo }}">
						<div><img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ $data->logo }}" id="face" width="100"></div>
						<div style="margin-top:5px"><input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}..."></div>
						<div style="margin-top:5px"><button type="button" name="submitbtn" onclick="savebase('myformface','face')" class="btn btn-primary">{{ trans('base.savetext') }}</button><span id="msgview_face"></span></div>
						</form>
					</div>
					
				</div>
				
				<div role="tabpanel" class="tab-pane" id="tab3">
					<div style="margin:20px;max-width:500px">
						<div class="form-horizontal" >
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.uid') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px">{{ $data->uid }}</div>
							</div>
						</div>
						
						<div class="form-group">
							<label for="input_name" class="col-sm-4 control-label"> {{ trans('table/company.createname') }}</label>
							<div class="col-sm-8">
								<div style="line-height:30px"><img width="30" align="absmiddle" src="{{ $udata->face }}"> {{ $udata->name }}，<input type="button" class="btn btn-default btn-xs" onclick="$('#editcreate').toggle()" value="{{ trans('base.xiugtext') }}"></div>
							</div>
						</div>
						</div>
						
						<form name="myformcreate"  class="form-horizontal" id="editcreate" style="display:none">
						
						<input type="hidden" value="{{ $data->num }}" name="cnum">
						
						<div class="form-group" inputname="superman">
							<label for="input_superman" class="col-sm-4 control-label"><font color=red>*</font> {{ trans('table/company.editcreatename') }}</label>
							<div class="col-sm-8">
							 <div class="input-group">
							  <input type="text" name="superman" required  data-fields="{{ trans('table/company.editcreatename') }}" value="" readonly class="form-control" placeholder="{{ trans('table/company.editcreatename_msg') }}">
							  <input type="hidden" value="" name="superid">
							  <span class="input-group-btn">
								<button class="btn btn-default" onclick="clearxuan1()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
								<button class="btn btn-default" onclick="searchxuan1()" type="button"><i class="glyphicon glyphicon-search"></i></button>
							  </span>
							</div>
								<span id="myformcreate_superman_errview"></span>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-4"></div>
							<div class="col-sm-8">
								<button type="button" name="submitbtn" onclick="savebase('myformcreate','create')" class="btn btn-primary">{{ trans('base.savetext') }}</button>
								<span id="msgview_create"></span>
							</div>
						</div>
						
						</form>
					</div>
				</div>
				
				
				<div role="tabpanel" class="tab-pane" id="tab4">
					<div style="margin-left:150px;margin-top:50px;max-width:450px">
						
						<form name="myformjiesan"  class="form-horizontal" >
						
						<div>{{ trans('manage/cog.jieshan_msg') }}</div>
						<div style="height:20px"></div>
						
						
						<div class="form-group">
							<label class="col-sm-3 control-label"> {{ trans('table/company.createname') }}{{ trans('users/reg.mobile') }}</label>
							<div class="col-sm-8">
							{{ substr($udata->mobile,0,3) }}****{{ substr($udata->mobile,-4) }}
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
								<span id="myformjiesan_mobileyzm_errview"></span>
							</div>
						</div>
						
						<div class="form-group" >
							<label class="col-sm-3 control-label"></label>
							<div class="col-sm-8">
								<button type="button" name="submitbtn" onclick="onjieshan()" class="btn btn-primary">{{ trans('manage/cog.jieshan') }}</button>
								<span id="msgview_jiesan"></span>
							</div>
						</div>
						
						
						</form>
					</div>
					
				</div>
				
			</div>	
		
		</div >
		
	</div>	
</div>
@endsection


@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function initbody(){
	
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		'thumbnail':'200x200',
		onsuccess:function(ret){
			get('face').src = ret.viewpats;
			form('face','myformface').value = ret.imgpath;
		}
	});
	upbtn.changefile();
}

function savebase(na, lx){
	var can = {
		url:'/api/unit/'+cnum+'/company_'+lx+'',
		autoback:false,
		formname:na,
		submitparams:{'device':device},
		msgview:'msgview_'+lx+'',
		submitmsg:'{{ trans('base.savetext') }}'
	};
	if(lx=='jiesan'){
		can.autoback=true;
		can.submitmsg='{{ trans('manage/cog.jieshan') }}';
		can.backurl='/users/manage';
	}
	$.rockvalidate(can);
}

function clearxuan1(){
	form('superman','myformcreate').value='';
	form('superid','myformcreate').value='';
}

function searchxuan1(){
	$.rockmodeuser({
		title:'{{ trans('table/usera.superman_msg') }}',
		changetype:'user',
		onselect:function(sna,sid){
			form('superman','myformcreate').value=sna;
			form('superid','myformcreate').value=sid;
		}
	});
}

function onjieshan(){
	$.rockmodelconfirm('{{ trans('manage/cog.jieshan_msg0') }}', function(jg){
		if(jg=='yes')savebase('myformjiesan','jiesan');
	});
}


function getcode(o1){
	var sj = '{{ $udata->mobile }}';
	o1.disabled = true;
	js.ajax('/api/we/base_getcode',{
		'mobilecode':'{{ $udata->mobilecode }}',
		'gtype':'jiesan',
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