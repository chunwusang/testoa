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
		
			
			<div id="midtypess" class="form-group" inputname="objectname">
				<label for="input_objectname" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/authory.objectname') }}</label>
				<div class="col-sm-8">
				 <div class="input-group">
				  <input type="text" name="objectname"  data-fields="{{ trans('table/authory.objectname') }}" value="{{ $data->objectname }}" readonly class="form-control" placeholder="{{ trans('table/authory.objectname_msg') }}">
				  <input type="hidden" value="{{ $data->objectid }}" name="objectid">
				  <span class="input-group-btn">
					<button class="btn btn-default" onclick="clearxuan()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
					<button class="btn btn-default" onclick="searchxuan()" type="button"><i class="glyphicon glyphicon-search"></i></button>
				  </span>
				</div>
					<span id="myform_mname_errview"></span>
				</div>
			</div>
			
			<div class="form-group" inputname="atype">
				<label for="input_atype" class="col-sm-3 control-label">{{ trans('table/authory.atype') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" onchange="changeatype()" data-fields="{{ trans('table/authory.atype') }}" id="input_atype" name="atype">
				  @for($i=0;$i<=7;$i++)
				  <option value="{{ $i }}" @if($i==$data->atype) selected @endif>{{ $i }}.{{ trans('table/authory.atype'.$i.'') }}</option>
				  @endfor
				  </select>
				</div>
			</div>
			
			<div class="form-group"  @if($data->atype<=1) style="display:none" @endif id="agenhiddiv" inputname="agenhid">
				<label for="input_agenhid" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/authory.agenhid') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" data-fields="{{ trans('table/authory.agenhid') }}" id="input_agenhid" name="agenhid">
				  <option value="0" >{{ trans('table/authory.agenhid_msg') }}</option>
				  
				  
				  @foreach($agenharr as $xxtype=>$itemarr)
				  <optgroup label="{{ $xxtype }}">
				  @foreach($itemarr as $k=>$item)
				  <option value="{{ $item->id }}" {{ $item->sel }}>{{ $item->name }}({{ $item->num }})</option>
				  @endforeach
				 </optgroup>
				  @endforeach
				  
				  
				  </select>
				</div>
			</div>
			
			<div id="divrecename" class="form-group" inputname="recename">
				<label for="input_recename" class="col-sm-3 control-label">{{ trans('table/authory.recename') }}</label>
				<div class="col-sm-8">
				 <div class="input-group">
				  <input type="text" name="recename"  data-fields="{{ trans('table/authory.recename') }}" value="{{ $data->recename }}" readonly class="form-control" placeholder="{{ trans('table/authory.recename_msg') }}">
				  <input type="hidden" value="{{ $data->receid }}" name="receid">
				  <span class="input-group-btn">
					<button class="btn btn-default" onclick="clearxuan1()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
					<button class="btn btn-default" onclick="searchxuan1()" type="button"><i class="glyphicon glyphicon-search"></i></button>
				  </span>
				</div>
					<span id="myform_recename_errview"></span>
				</div>
			</div>
			
			<div class="form-group" inputname="wherestr">
				<label for="input_wherestr"  class="col-sm-3 control-label">{{ trans('table/authory.wherestr') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/authory.wherestr') }}" placeholder="{{ trans('table/authory.wherestr_msg') }}" id="input_wherestr" name="wherestr">{{ $data->wherestr }}</textarea>
				  <div></div>
				</div>
			</div>
			
			
			<div class="form-group" inputname="explain">
				<label for="input_explain"  class="col-sm-3 control-label">{{ trans('table/authory.explain') }}</label>
				<div class="col-sm-8">
				  <textarea class="form-control" data-fields="{{ trans('table/authory.explain') }}" placeholder="{{ trans('table/authory.explain_msg') }}" id="input_explain" name="explain">{{ $data->explain }}</textarea>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
				  <label><input @if($data->status==1) checked @endif name="status" value="1" type="checkbox">{{ trans('table/authory.status1') }}</label>
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
<script src="/res/js/jquery-changeuser.js"></script>
<script>
function submitadd(o){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/authory',
		submitmsg:'{{ $pagetitles }}',
		backurl: '/manage/'+cnum+'/authory'
	});
}
function clearxuan(){
	form('objectname').value='';
	form('objectid').value='';
}
function clearxuan1(){
	form('recename').value='';
	form('receid').value='';
}
function searchxuan(){
	$.rockmodeuser({
		title:'{{ trans('table/authory.objectname_msg') }}',
		changetype:'deptusercheck',
		onselect:function(sna,sid){
			form('objectname').value=sna;
			form('objectid').value=sid;
		}
	});
}
function searchxuan1(){
	$.rockmodeuser({
		title:'{{ trans('table/authory.recename_msg') }}',
		changetype:'deptusercheck',
		onselect:function(sna,sid){
			form('recename').value=sna;
			form('receid').value=sid;
		}
	});
}
function changemtype(){
	var lx = form('mtype').value;
	if(lx==3){
		$('#midtypess').hide();
		clearxuan();
	}else{
		$('#midtypess').show();
	}
}
function changeatype(){
	var lx = form('atype').value;
	if(lx>=2 && lx<=7){
		$('#agenhiddiv').show();
	}else{
		$('#agenhiddiv').hide();
		form('agenhid').value='0';
	}
	if(lx==2 || lx==4 || lx==5){
	}else{
		clearxuan1();
		form('wherestr').value='';
	}
}
</script>
@endsection