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
			
			<div class="form-group" inputname="name">
				<label for="input_name" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/group.name') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/group.name') }}" required placeholder="{{ trans('table/group.name_msg') }}" value="{{ $data->name }}" maxlength="100" id="input_name" name="name">
				</div>
			</div>
			
			
			
			<div class="form-group" inputname="sort">
				<label for="input_sort" class="col-sm-3 control-label"><font color=red>*</font> {{ trans('table/group.sort') }}</label>
				<div class="col-sm-8">
				  <input class="form-control" data-fields="{{ trans('table/group.sort') }}" required placeholder="{{ trans('table/group.sort_msg') }}" type="number" maxlength="10" value="{{ $data->sort }}" id="input_sort" name="sort">
				</div>
			</div>
			
			
			
			<div class="form-group">
				<div class="col-sm-3"></div>
				<div class="col-sm-9">
					<button type="button" name="submitbtn" onclick="submitadd()" class="btn btn-primary">{{ $pagetitles }}</button>
					&nbsp;<span id="msgview"><a href="javascript:;" onclick="js.back()">&lt;&lt;{{ trans('base.back') }}</a></span>
				</div>
			</div>
		
			
			
		</form>

	</div>
</div>
@endsection

@section('script')
<script>
function submitadd(){
	$.rockvalidate({
		url:'/api/unit/'+cnum+'/group',
		submitmsg:'{{ $pagetitles }}',
		backurl: '/manage/'+cnum+'/group'
	});
}
</script>
@endsection