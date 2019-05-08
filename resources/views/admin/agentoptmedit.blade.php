@extends('admin.public')

@section('content')
<div class="container" align="center">
	<div align="left" style="max-width:600px">
		<div>
			<h3>{{ $pagetitle }}</h3>
			<div>{!! $helpstr !!}</div>
			<hr class="head-hr" />
		</div>	
	
		<form name="myform" class="form-horizontal">
			
			<input type="hidden" value="{{ $data->id }}" name="id">
			<input type="hidden" value="{{ $data->agentid }}" name="agentid">
			

	
			@foreach ($formfields as $fid=>	$item)
			<div class="form-group" inputname="{{ $fid }}">
				<label for="input_{{ $fid }}" class="col-sm-3 control-label">@if(isset($item['required'])) <font color=red>*</font> @endif {{ trans('table/flowmenu.'.$fid.'') }}</label>
				<div class="col-sm-8">
					@if ($item['type']=='textarea')
					<textarea class="form-control" {!! $item['attr'] !!} data-fields="{{ trans('table/flowmenu.'.$fid.'') }}" placeholder="{{ trans('table/flowmenu.'.$fid.'_msg') }}" id="input_{{ $fid }}" @if(isset($item['required'])) required @endif name="{{ $fid }}">{{ $data->$fid }}</textarea>
					@elseif($item['type']=='select')
					<select class="form-control"  data-fields="{{ trans('table/flowmenu.'.$fid.'') }}"  @if(isset($item['required'])) required @endif name="{{ $fid }}">
					@foreach($item['store'] as $store)
					<option @if($data->$fid===$store[0])selected @endif value="{{ $store[0] }}">{{ $store[1] }}</option>
					@endforeach
					</select>
					@else	
					
					<input class="form-control" data-fields="{{ trans('table/flowmenu.'.$fid.'') }}" type="{{ $item['type'] }}" placeholder="{{ trans('table/flowmenu.'.$fid.'_msg') }}" id="input_{{ $fid }}" @if(isset($item['required'])) required @endif name="{{ $fid }}" value="{{ $data->$fid }}">
					@endif
					@if($item['tishi'])
					<div style="color:#aaaaaa">{{ $item['tishi'] }}</div>	
					@endif
				</div>
			</div>
			@endforeach
			
			<div class="form-group">
				<label class="col-sm-3 control-label"></label>
				<div class="col-sm-8">
				  <label><input @if($data->status==1) checked @endif name="status" value="1" type="checkbox">{{ trans('table/flowmenu.status1') }}</label>&nbsp;
				  <label><input @if($data->islog==1) checked @endif name="islog" value="1" type="checkbox">{{ trans('table/flowmenu.islog') }}</label>&nbsp;
				  <label><input @if($data->issm==1) checked @endif name="issm" value="1" type="checkbox">{{ trans('table/flowmenu.issm1') }}{{ trans('table/flowmenu.issm') }}</label>&nbsp;
				  <label><input @if($data->iszs==1) checked @endif name="iszs" value="1" type="checkbox">{{ trans('table/flowmenu.iszs') }}</label>&nbsp;
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
function submitadd(o){
	$.rockvalidate({
		url:'{{ route('adminagentoptmsave') }}',
		submitmsg:'{{ $pagetitle }}'
	});
}
function xuantuan(){
	if(typeof(upbtn)=='undefined')upbtn = $.rockupfile({
		'uptype':'image',
		onsuccess:function(ret){
			var url = ret.imgpath;
			get('faceimg').src = url;
			form('face').value = url;
		}
	});
	upbtn.changefile();
}
</script>
@endsection