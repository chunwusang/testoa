<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $pagetitle or config('app.name') }}</title>
<link href="{{ Auth::user()->bootstyle }}" id="bootstyle" rel="stylesheet">
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

                   
                    <a class="navbar-brand" href="{{ route('usersmanage') }}">
                       <img src="{{ config('app.logo') }}" style="display:inline" align="absmiddle" height="24"> {{ config('app.name') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                   
					<ul class="nav navbar-nav">
						<!--
						<li><a href="{{ route('usersagent') }}">{{ trans('users/cog.agent') }}</a></li>
						-->
						<li><a href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a></li>
					</ul>
                   
                    <ul class="nav navbar-nav navbar-right">
                        
                       <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
								{{ Auth::user()->name }} <span class="caret"></span>
							</a>

							<ul class="dropdown-menu" role="menu">
								<li>
								
									<a href="{{ route('usersindex') }}">{{ trans('manage/public.menu.ushome') }}</a>

									<a href="{{ route('usersloginout') }}">{{ trans('base.exittext') }}</a>
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
	<script src="/res/plugin/jquery-rockmodel.js"></script>
	@yield('script')
	
	@include('layouts.footer')
</body>
</html>
