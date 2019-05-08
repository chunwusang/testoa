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
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentmenu.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agentmenu.name') }}" required placeholder="{{ trans('table/agentmenu.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" type="text" name="name">
				</div>
			</div>
	
			<div class="form-group" inputname="num">
				<label for="input_num" class="col-sm-3 control-label">{{ trans('table/agentmenu.num') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agentmenu.num') }}" placeholder="{{ trans('table/agentmenu.num_msg') }}" value="{{ $data->num }}" maxlength="100" id="input_num" type="onlyen" name="num">
				</div>
			</div>
			
			<div class="form-group" inputname="type">
				<label for="input_type" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentmenu.type') }}</label>
				<div class="col-sm-8">
				  <select class="form-control" data-fields="{{ trans('table/agentmenu.type') }}" required id="input_type" name="type">
				  @foreach($fieldstype as $rs) <option value="{{ $rs['value'] }}" {{ $rs['selected'] }}>{{ $rs['name'] }}</option> @endforeach
				  </select>
				</div>
			</div>
			
			<div class="form-group" inputname="url">
				<label for="input_url" class="col-sm-3 control-label">{{ trans('table/agentmenu.url') }}</label>
				<div class="col-sm-8">
					<input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agentmenu.url') }}" placeholder="{{ trans('table/agentmenu.url_msg') }}" value="{{ $data->url }}" maxlength="100" id="input_url" name="url">
					<div><select onchange="get('input_url').value=this.value"><option value="">{{ trans('table/agentmenu.url_auto') }}</option><option value="my">{{ trans('table/agentmenu.url_my') }}</option><option value="myunread">{{ trans('table/agentmenu.url_myunread') }}</option><option value="all">{{ trans('table/agentmenu.url_all') }}</option><option value="allunread">{{ trans('table/agentmenu.url_allunread') }}</option></select></div>
				</div>
				
			</div>
			
			<div class="form-group" inputname="recename">
				<label class="col-sm-3 control-label">{{ trans('table/agentmenu.recename') }}</label>
				<div class="col-sm-8">
					<input type="hidden" value="{{ $data->receid }}" name="receid">
					<div class="input-group">
					  <input class="form-control" data-fields="{{ trans('table/agentmenu.recename') }}" placeholder="{{ trans('table/agentmenu.recename_msg') }}" value="{{ $data->recename }}" readonly name="recename">
					  <span class="input-group-btn">
						<button class="btn btn-default" onclick="clearxuan()" type="button"><i class="glyphicon glyphicon-remove"></i></button>
						<button class="btn btn-default" type="button"><i class="glyphicon glyphicon-search"></i></button>
					  </span>
					</div>
				</div>
			</div>
			
			<div class="form-group" inputname="color">
				<label for="input_color" class="col-sm-3 control-label">{{ trans('table/agentmenu.color') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" onblur="js.str(this)" data-fields="{{ trans('table/agentmenu.color') }}" placeholder="{{ trans('table/agentmenu.color_msg') }}" value="{{ $data->color }}" maxlength="20" id="input_url" type="onlyen" name="color">
				</div>
			</div>
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/agentmenu.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/agentmenu.sort') }}" required placeholder="{{ trans('table/agentmenu.sort_msg') }}" value="{{ $data->sort }}" maxlength="6" id="input_sort" type="number" name="sort">
				</div>
			</div>
			
			
			
			<div class="form-group">
				<label  class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
					<label><input type="checkbox" @if ($data->status==1)checked @endif value="1" name="status">{{ trans('table/agentmenu.status') }}{{ trans('table/agentmenu.status1') }}</label>&nbsp;
					
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
<script>
function submitadd(o){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/agenh_menusave',
		backurl:'{{ route('manageagenhmenu', [$cid,$data->agenhid]) }}',
		submitmsg:'{{ $pagetitles }}'
	});
}

function clearxuan(){
	form('recename').value='';
	form('receid').value='';
}
</script>
@endsection