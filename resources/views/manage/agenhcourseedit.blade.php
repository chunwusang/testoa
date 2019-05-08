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
			<input type="hidden" value="{{ $data->agenhid }}" name="agenhid">
			<input type="hidden" value="{{ $data->pid }}" name="pid">
			<input type="hidden" value="{{ $cid }}" name="cid">

			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenhcourse.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenhcourse.name') }}" required placeholder="{{ trans('table/agenhcourse.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" type="text" name="name">
				</div>
			</div>
	
			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label">{{ trans('table/agenhcourse.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenhcourse.num') }}" placeholder="{{ trans('table/agenhcourse.num_msg') }}" value="{{ $data->num }}" maxlength="100" id="input_num" type="onlyen" name="num">
				</div>
			</div>
			

			<div class="form-group" inputname="recename">
				<label class="col-sm-3 control-label">{{ trans('table/agenhcourse.recename') }}</label>
				<div class="col-sm-8">
					<input type="hidden" value="{{ $data->receid }}" name="receid">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('table/agenhcourse.recename') }}" placeholder="{{ trans('table/agenhcourse.recename_msg') }}" value="{{ $data->recename }}" readonly name="recename">
					  <span class="input-group-btn">
						<button class="btn btn-default" onclick="clearxuan()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
						<button class="btn btn-default" onclick="changexuan()" type="button"><i class="glyphicon glyphicon-search"></i></button>
					  </span>
					</div>
				</div>
			</div>
			
			<div class="form-group" inputname="checkwhere">
				<label for="input_checkwhere" class="col-sm-3 control-label">{{ trans('table/agenhcourse.checkwhere') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" style="height:70px" data-fields="{{ trans('table/agenhcourse.checkwhere') }}" placeholder="{{ trans('table/agenhcourse.checkwhere_msg') }}" id="input_checkwhere" name="checkwhere">{{ $data->checkwhere }}</textarea>
				  
				</div>
			</div>
			
			<div class="form-group" inputname="checktype">
				<label for="input_checktype" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenhcourse.checktype') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" onchange="onchangetype(0)" data-fields="{{ trans('table/agenhcourse.checktype') }}" required id="input_checktype" name="checktype">
				  <option value="">{{ trans('table/agenhcourse.checktype_msg') }}</option>
				  @foreach($checktypearr as $type) <option @if($type==$data->checktype)selected @endif value="{{ $type }}">{{ trans('table/agenhcourse.checktype_'.$type.'') }}({{ $type }})</option> @endforeach
				  </select>
				</div>
			</div>
			
			<div class="form-group" id="div_xuanssss" inputname="checktypename">
				<label class="col-sm-3 control-label" id="label_checktypename">{{ trans('table/agenhcourse.checktypename') }}</label>
				<div class="col-sm-8">
					<input type="hidden" value="{{ $data->checktypeid }}" name="checktypeid">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('table/agenhcourse.checktypename') }}" placeholder="{{ trans('table/agenhcourse.checktypename_msg') }}" value="{{ $data->checktypename }}" name="checktypename">
					  <span class="input-group-btn">
						<button class="btn btn-default" onclick="clearxuan1()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
						<button class="btn btn-default" onclick="changexuan1()" type="button"><i class="glyphicon glyphicon-search"></i></button>
					  </span>
					</div>
					<span id="myform_checktypename_errview"></span> 

				</div>
			</div>
			
			<div class="form-group" inputname="isqm">
				<label for="input_isqm" class="col-sm-3 control-label">{{ trans('table/agenhcourse.isqm') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_isqm" name="isqm">
				  <option value="0">{{ trans('table/agenhcourse.isqm0') }}</option>
				  <option value="1" @if($data->isqm==1)selected @endif>{{ trans('table/agenhcourse.isqm1') }}</option>
				  <option value="2" @if($data->isqm==2)selected @endif>{{ trans('table/agenhcourse.isqm2') }}</option>
				  <option value="3" @if($data->isqm==3)selected @endif>{{ trans('table/agenhcourse.isqm3') }}</option>
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="courseact">
				<label for="input_courseact" class="col-sm-3 control-label">{{ trans('table/agenhcourse.courseact') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenhcourse.courseact') }}" placeholder="{{ trans('table/agenhcourse.courseact_msg') }}" value="{{ $data->courseact }}" maxlength="200" id="input_courseact" name="courseact">
				</div>
			</div>
			
			<div class="form-group" inputname="checkfields">
				<label for="input_checkfields" class="col-sm-3 control-label">{{ trans('table/agenhcourse.checkfields') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agenhcourse.checkfields') }}" placeholder="{{ trans('table/agenhcourse.checkfields_msg') }}" maxlength="500" value="{{ $data->checkfields }}" id="input_checkfields" name="checkfields">
				</div>
			</div>
			
			<div class="form-group" inputname="checksmlx">
				<label for="input_checksmlx" class="col-sm-3 control-label">{{ trans('table/agenhcourse.checksmlx') }}</label>
				<div class="col-sm-8">
				  <select class="form-control"  id="input_checksmlx" name="checksmlx">
				  <option value="0">{{ trans('table/agenhcourse.checksmlx0') }}</option>
				  <option value="1" @if($data->checksmlx==1)selected @endif>{{ trans('table/agenhcourse.checksmlx1') }}</option>
				  <option value="2" @if($data->checksmlx==2)selected @endif>{{ trans('table/agenhcourse.checksmlx2') }}</option>
				  <option value="3" @if($data->checksmlx==3)selected @endif>{{ trans('table/agenhcourse.checksmlx3') }}</option>
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agenhcourse.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agenhcourse.sort') }}" required placeholder="{{ trans('table/agenhcourse.sort_msg') }}" value="{{ $data->sort }}" maxlength="6" id="input_sort" type="number" name="sort">
				</div>
			</div>
			
			
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/agenhcourse.status') }}{{ trans('table/agenhcourse.status1') }}</label>&nbsp;
					<label><input type="checkbox" @if ($data->iszb==1)checked @endif value="1" name="iszb">{{ trans('table/agenhcourse.iszb1') }}</label>&nbsp;
					
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-8">
					<button type="button" name="submitbtn" id="btn1" onclick="submitadd()" class="btn btn-primary">{{ $pagetitles }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
	
			
		</form>	
	
	</div>
</div>
@endsection

@section('script')
<script src="/res/js/jquery-changeuser.js"></script>

<script>
function submitadd(o){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/agenh_coursesave',
		backurl:'/manage/'+cnum+'/agenh_course?agenhid={{ $data->agenhid }}',
		submitmsg:'{{ $pagetitles }}',
		oncheck:function(na,val,da){
			if(na=='checktypename'){
				if(da.checktype=='auto' && val==''){
					return '{{ trans('table/agenhcourse.checktypename_auto') }}'
				}
				if(da.checktype=='rank' && val==''){
					return '{{ trans('table/agenhcourse.checktypename_rank') }}'
				}
				if(da.checktype=='user' && da.checktypeid==''){
					return '{{ trans('table/agenhcourse.checktypename') }}'
				}
				if(da.checktype=='field' && da.checktypeid==''){
					return '{{ trans('table/agenhcourse.checktypename_field') }}'
				}
			}
		}
	});
}
function initbody(){
	onchangetype(1);
}
function onchangetype(lx){
	var val = form('checktype').value;
	var ne = '{{ trans('table/agenhcourse.checktypename') }}';
	if(val=='change')ne='{{ trans('table/agenhcourse.checktypename_change') }}';
	if(val=='rank')ne='{{ trans('table/agenhcourse.checktypename_rank') }}';
	if(val=='auto')ne='{{ trans('table/agenhcourse.checktypename_auto') }}';
	if(val=='field')ne='{{ trans('table/agenhcourse.checktypename_field') }}';
	$('#label_checktypename').html(ne);
	if(lx==0)clearxuan1();
	if(val=='rank' || val=='change' || val=='user' || val=='auto' || val=='field'){
		$('#div_xuanssss').show();
		if(val=='rank'){
			form('checktypename').readOnly=false;
		}else{
			form('checktypename').readOnly=true;
		}
	}else{
		$('#div_xuanssss').hide();
	}
	
	
}
function clearxuan(){
	
	form('recename').value='';
	form('receid').value='';
}

function clearxuan1(){
	form('checktypeid').value='';
	form('checktypename').value='';
}

function changexuan1(){
	var val = form('checktype').value;
	if(val=='rank'||val=='auto')return;
	
	if(val=='field'){
		$.selectdata({
			data:[],title:'{{ trans('table/agenhcourse.checktypename_field') }}',
			url:'/api/unit/'+cnum+'/agenh_getfield/?agentid={{ $data->agentid }}',
			checked:true, 
			nameobj:form('checktypename'),
			idobj:form('checktypeid'),
			onloaddata:function(a){
				
			},
			onselect:function(seld,sna,sid){
				
			}
		});
		return;
	}
	
	var lex = 'usercheck';
	if(val=='change')lex='deptusercheck';
	$.rockmodeuser({
		title:'{{ trans('table/agenhcourse.checktypename') }}',
		changetype:lex,
		onselect:function(sna,sid){
			form('checktypename').value=sna;
			form('checktypeid').value=sid;
		}
	});
}

function changexuan(){
	$.rockmodeuser({
		title:'{{ trans('table/agenhcourse.recename') }}',
		changetype:'deptusercheck',
		onselect:function(sna,sid){
			form('recename').value=sna;
			form('receid').value=sid;
		}
	});
}
</script>
@endsection