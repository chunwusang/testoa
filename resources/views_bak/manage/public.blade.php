<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $pagetitle or config('app.name') }}({{ trans('manage/public.menu.guanli') }})</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />

<link href="{{ Auth::user()->bootstyle }}" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top ">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="{{ route('manage', $cnum) }}">
                       <img src="{{ $companyinfo->logo }}" style="display:inline;" align="absmiddle" height="24"> {{ $companyinfo->name }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    
					 <ul class="nav navbar-nav">
						<li class="dropdown @if((strpos($tpl,'dept')!==false || strpos($tpl,'usera')!==false || strpos($tpl,'group')!==false) && strpos($tpl,'wxqy')===false) active @endif">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ trans('manage/public.menu.tongxl') }} <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li @if(strpos($tpl,'dept')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'dept']) }}">{{ trans('manage/public.menu.dept') }}</a></li>
						 
								<li @if(strpos($tpl,'usera')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'usera']) }}">{{ trans('manage/public.menu.usera') }}</a></li>
						 
								<li @if(strpos($tpl,'group')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'group']) }}">{{ trans('manage/public.menu.group') }}</a></li>
							</ul>
						</li>
                         
						 
                         <li @if(strpos($tpl,'agenh')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'agenh']) }}">{{ trans('manage/public.menu.agenh') }}</a></li>
						 
						 <li @if(strpos($tpl,'authory')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'authory']) }}">{{ trans('manage/public.menu.authory') }}</a></li>
						 
						 <li @if(strpos($tpl,'option')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'option']) }}">{{ trans('manage/public.menu.option') }}</a></li>
						 
						 <!--
						 <li class="dropdown @if(strpos($tpl,'reim')!==false) active @endif">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ trans('manage/public.menu.reim') }} <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li @if(strpos($tpl,'reimcog')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'reim_cog']) }}">{{ trans('manage/public.menu.reimcog') }}</a></li>
								
							</ul>
						</li>
						-->
						 
                        
						<li class="dropdown @if(strpos($tpl,'wxqy')!==false) active @endif">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ trans('manage/public.menu.wxqy') }} <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li @if(strpos($tpl,'wxqycog')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'wxqy_cog']) }}">{{ trans('manage/public.menu.wxqycog') }}</a></li>
								<li @if(strpos($tpl,'wxqydept')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'wxqy_dept']) }}">{{ trans('manage/public.menu.wxqydept') }}</a></li>
								<li @if(strpos($tpl,'wxqyuser')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'wxqy_user']) }}">{{ trans('manage/public.menu.wxqyuser') }}</a></li>
								<li @if(strpos($tpl,'wxqyagent')!==false) class="active" @endif><a href="{{ route('manage', [$cnum,'wxqy_agent']) }}">{{ trans('manage/public.menu.wxqyagent') }}</a></li>
							</ul>
						</li>
                    </ul>
					

                   
                    <ul class="nav navbar-nav navbar-right">
						<li class="dropdown @if(strpos($tpl,'manage/cog')!==false)active @endif" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ trans('manage/public.menu.cog') }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li @if($tpl=='manage/cog') class="active" @endif><a href="{{ route('manage', [$cnum,'cog']) }}">{{ trans('manage/public.menu.dwinfo') }}</a></li>
								<li @if($tpl=='manage/cogemail') class="active" @endif><a href="{{ route('manage', [$cnum,'cog_email']) }}">{{ trans('manage/public.menu.cogemail') }}</a></li>
							</ul>	
						</li>
						
						
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
								{{ Auth::user()->name }} <span class="caret"></span>
							</a>

							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ route('usersmanage') }}">{{ trans('manage/public.menu.grhome') }}</a></li>
								<li><a href="{{ route('usersindexs', $cnum) }}">{{ trans('manage/public.menu.ushome') }}</a></li>
								<li><a href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a></li>
									
								<li><a href="{{ route('usersloginout') }}">{{ trans('base.exittext') }}</a>
								</li>
							</ul>
						</li>
                    </ul>
                </div>
            </div>
        </nav>

        <div style="min-height:480px">
        @yield('content')
		</div>
    </div>

    <script src="/bootstrap/js/bootstrap.min.js"></script>
	<script src="/res/plugin/jquery-rockvalidate.js"></script>
	<script src="/res/plugin/jquery-rockmodel.js"></script>
	
	<script>
	var companyid = {{ $cid }},cnum='{{ $companyinfo->num }}';
	</script>
	@yield('script')
	
	<div align="center">{!! $helpstr !!}</div>
	@include('layouts.footer')
	
</body>
</html>
