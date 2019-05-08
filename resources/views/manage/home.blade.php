@extends('manage.public')

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
	<!-- <div>我是主题</div> -->
    <h1 align="center">{{ $companyinfo->name }}</h1>
	<div style="margin-top:40px" class="row">
		
        <div class="col-md-3">
           <div class="thumbnail">
            <span>{{ $companyinfo->flasks }}</span>/{{ $companyinfo->flaskm }}
            <div class="caption"><a href="{{ route('manage', [$cnum,'usera']) }}">{{ trans('manage/home.useratotal') }}</a></div>
          </div>
        </div>
		
		<div class="col-md-3">
           <div class="thumbnail">
            <span>{{ $usehtotal }}</span>
            <div class="caption"><a href="{{ route('manage', [$cnum,'usera']) }}?status=0">{{ trans('manage/home.usehtotal') }}</a></div>
          </div>
        </div>
		
		<div class="col-md-3">
           <div class="thumbnail">
            <span>{{ $agenhtotal }}</span>
            <div class="caption"><a href="{{ route('manage', [$cnum,'agenh']) }}">{{ trans('manage/home.agenhtotal') }}</a></div>
          </div>
        </div>
		
		<div class="col-md-3">
           <div class="thumbnail">
            <span>{{ $depttotal }}</span>
            <div class="caption"><a href="{{ route('manage', [$cnum,'dept']) }}">{{ trans('manage/home.depttotal') }}</a></div>
          </div>
        </div>
		
    </div>
</div>


@endsection
