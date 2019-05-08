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
			
				
			<div align="center" style="padding:20px">
			<img style="background:white;border:1px #dddddd solid;border-radius:10px" src="{{ Rock::replaceurl($data->facesrc) }}" id="faceimg" width="50"><br>
			<input type="button" class="btn btn-default btn-xs" onclick="xuantuan()" value="{{ trans('base.xuantext') }}...">
			</div>
			
			
			<div class="form-group">
				<label class="col-sm-3 control-label">{{ trans('table/agent.id') }}</label>
				<div class="col-sm-8" style="line-height:40px">{{ $data->id }}</div>
			</div>
		
			@foreach ($formfields as $fid=>	$item)
			<div class="form-group" inputname="{{ $fid }}">
				<label for="input_{{ $fid }}" class="col-sm-3 control-label">@if(isset($item['required'])) <font color=red>*</font> @endif {{ trans('table/agent.'.$fid.'') }}</label>
				<div class="col-sm-8">
					@if ($item['type']=='textarea')
					<textarea class="form-control" {!! $item['attr'] !!} data-fields="{{ trans('table/agent.'.$fid.'') }}" placeholder="{{ trans('table/agent.'.$fid.'_msg') }}" id="input_{{ $fid }}" @if(isset($item['required'])) required @endif name="{{ $fid }}">{{ $data->$fid }}</textarea>
					@elseif($item['type']=='select')
					<select class="form-control"  data-fields="{{ trans('table/agent.'.$fid.'') }}"  @if(isset($item['required'])) required @endif name="{{ $fid }}">
					@foreach($item['store'] as $store)
					<option @if($data->$fid===$store[0])selected @endif value="{{ $store[0] }}">{{ $store[1] }}</option>
					@endforeach
					</select>
					@else	
					
					<input class="form-control" data-fields="{{ trans('table/agent.'.$fid.'') }}" type="{{ $item['type'] }}" placeholder="{{ trans('table/agent.'.$fid.'_msg') }}" id="input_{{ $fid }}" @if(isset($item['required'])) required @endif name="{{ $fid }}" value="{{ $data->$fid }}">
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
				  <label><input @if($data->status==1) checked @endif name="status" value="1" type="checkbox">{{ trans('table/agent.status1') }}</label>&nbsp;
				  <label><input @if($data->istxset==1) checked @endif name="istxset" value="1" type="checkbox">{{ trans('table/agent.istxset1') }}{{ trans('table/agent.istxset') }}</label>&nbsp;
				  <label><input @if($data->ispl==1) checked @endif name="ispl" value="1" type="checkbox">{{ trans('table/agent.ispl1') }}{{ trans('table/agent.ispl') }}</label>&nbsp;
				  <label><input @if($data->islu==1) checked @endif name="islu" value="1" type="checkbox">{{ trans('table/agent.islu1') }}</label>&nbsp;
				  <label><input @if($data->issy==1) checked @endif name="issy" value="1" type="checkbox">{{ trans('table/agent.issy') }}</label>&nbsp;
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
		url:'{{ route('adminagentsave') }}',
		submitmsg:'{{ $pagetitle }}'
	});
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
</script>
@endsection