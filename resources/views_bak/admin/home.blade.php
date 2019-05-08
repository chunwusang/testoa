@extends('admin.public')

@section('content')
<style>
.thumbnail {
	text-align: center;
}
.thumbnail span {
  font-size: 40px;
  font-weight: bold;
  line-height: 60px;
}
</style>
<div class="container">

	
	<div>
		<h3>平台统计</h3>
		<div>{!! $helpstr !!}</div>
	</div>
    <div class="row">
		<div class="col-md-3 col-sm-3">
			<div class="thumbnail">
				<span>{{ $logtotal }}</span>
				<div class="caption"><a href="{{ route('adminmanage','log') }}">{{ trans('admin/home.logtotal') }}@if($logtotals>0)<font color=red>({{ $logtotals }})</font>@endif</a></div>
			</div>
        </div>
        <div class="col-md-3 col-sm-3">
           <div class="thumbnail">
            <span>{{ $companytotal }}</span>
            <div class="caption"><a href="{{ route('admincompany') }}">{{ trans('admin/home.companytotal') }}</a></div>
          </div>
        </div>
		<div class="col-md-3 col-sm-3">
            <div class="thumbnail">
				<span>{{ $userstotal }}</span>
				<font color="red"></font>
				<div class="caption"><a href="{{ route('adminusers') }}">{{ trans('admin/home.userstotal') }}</a></div>
			</div>
        </div>
		<div class="col-md-3 col-sm-3">
			<div class="thumbnail">
				<span>{{ $useratotal }}</span>
				<div class="caption"><a href="{{ route('adminusera') }}">{{ trans('admin/home.useratotal') }}</a></div>
			</div>
        </div>
		<div class="col-md-3 col-sm-3">
			<div class="thumbnail">
				<span>{{ $depttotal }}</span>
				<div class="caption"><a href="{{ route('admindept') }}">{{ trans('admin/home.depttotal') }}</a></div>
			</div>
        </div>
		<div class="col-md-3 col-sm-3">
			<div class="thumbnail">
				<span>{{ $agenttotal }}</span>
				<div class="caption"><a href="{{ route('adminagent') }}">{{ trans('admin/home.agenttotal') }}</a></div>
			</div>
        </div>
		
    </div>
	
</div>
@endsection
