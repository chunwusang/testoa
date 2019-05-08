<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $pagetitle or config('app.nameadmin') }}</title>
<link href="{{ $bootstyle }}" id="bootstyle" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="{{ route('adminhome') }}">
                       <img src="{{ config('app.logo') }}" style="display:inline;" align="absmiddle" height="24"> {{ config('app.nameadmin') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    
					@if (Auth::guest())
					@else	
                    <ul class="nav navbar-nav">
                         <li class="dropdown @if(strpos($tpl,'company')!==false)active @endif" >
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ trans('admin/public.menu.company') }} <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li @if(strpos($tpl,'companylist')!==false)class="active" @endif>
									<a href="{{ route('admincompany') }}">{{ trans('admin/public.menu.companylist') }}</a>
								</li>
								<li @if(strpos($tpl,'companydept')!==false)class="active" @endif>
									<a href="{{ route('admindept') }}">{{ trans('admin/public.menu.companydept') }}</a>
								</li>
								<li @if(strpos($tpl,'companyusera')!==false)class="active" @endif>
									<a href="{{ route('adminusera') }}">{{ trans('admin/public.menu.companyusera') }}</a>
								</li>
								
							</ul>
						 </li>
                         <li @if(strpos($tpl,'users')!==false)class="active" @endif><a href="{{ route('adminusers') }}">{{ trans('admin/public.menu.platuser') }}</a></li>
                         <li @if(strpos($tpl,'agent')!==false)class="active" @endif><a href="{{ route('adminagent') }}">{{ trans('admin/public.menu.agentname') }}</a></li>
		
						 
						 <li @if(strpos($tpl,'platupgde')!==false)class="active" @endif>
							<a href="{{ route('adminmanage','upgde') }}"><i class="glyphicon glyphicon-arrow-up"></i> {{ trans('admin/public.menu.platupgde') }}</a>
						 </li>
						 
						 <li @if(strpos($tpl,'anstall')!==false)class="active" @endif><a href="{{ route('adminanstall') }}">{{ trans('admin/public.menu.anstall') }}</a></li>
                    </ul>
					@endif

                
                    <ul class="nav navbar-nav navbar-right">
    
                        @if (Auth::guest())
                            <li><a href="{{ route('adminlogin') }}">{{ trans('base.logintext') }}</a></li>
                        @else
							<li class="dropdown @if(strpos($tpl,'plat')!==false && $tpl!='platupgde')active @endif">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
									{{ trans('admin/public.menu.platmanage') }} <span class="caret"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li @if(strpos($tpl,'platcog')!==false)class="active" @endif>
										<a href="{{ route('adminmanage','cog') }}">{{ trans('admin/public.menu.platcog') }}</a>
									</li>
									<li @if(strpos($tpl,'platadmin')!==false)class="active" @endif>
										<a href="{{ route('adminmanage','admin') }}">{{ trans('admin/public.menu.platadmin') }}</a>
									</li>
									<li @if(strpos($tpl,'platlog')!==false)class="active" @endif>
										<a href="{{ route('adminmanage','log') }}">{{ trans('admin/public.menu.platlog') }}</a>
									</li>
									<li @if(strpos($tpl,'plattask')!==false)class="active" @endif>
										<a href="{{ route('adminmanage','task') }}">{{ trans('admin/public.menu.plattask') }}</a>
									</li>
									<li @if(strpos($tpl,'platqueue')!==false)class="active" @endif>
										<a href="{{ route('adminmanage','queue') }}">{{ trans('admin/public.menu.platqueue') }}</a>
									</li>
								</ul>
							</li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('adminloginout') }}">{{ trans('base.exittext') }}</a>
                                    </li>
                                </ul>
                            </li>
                        @endif
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
	@yield('script')
	
	@include('layouts.footer')
</div>
</body>
</html>
