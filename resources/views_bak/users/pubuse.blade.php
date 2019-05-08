<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $companyinfo->name }}</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />
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

                    <a class="navbar-brand" href="{{ route('usershomes', $companyinfo->num) }}">
                       <img src="{{ $companyinfo->logo }}" style="display:inline" align="absmiddle" height="24"> {{ $companyinfo->name }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                   
					<ul class="nav navbar-nav">
						@foreach($agenharr as $atype=>$data)
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ $atype }}@if($agenhtarr[$atype]>0) <span class="badge">{{ $agenhtarr[$atype] }}</span>@endif <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
							@foreach($data as $item)
							<li>	
								<a href="{{ $item->url }}">
								<img src="{{ $item->face }}" style="display:inline" align="absmiddle" width="20" height="20"> {{ $item->name }}
								@if($item->stotal>0)<span class="badge">{{ $item->stotal }}</span>@endif
								</a>
							</li>
							@endforeach
							</ul>
						</li>	
						@endforeach
					</ul>
                   
                    <ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								{{ trans('users/home.qiename') }} <span class="caret"></span>
							</a>
							
							<ul class="dropdown-menu" role="menu">
								
								@foreach($joincompany as $item)
								@if($item->id==$companyinfo->id)
								<li class="active">	
									<a href="javascript:;"><img src="{{ $item->logo }}" style="display:inline" align="absmiddle" width="20" height="20"> {{ $item->name }} √</a>
								</li>
								@else
								<li>	
									<a href="{{ route('usershomes', $item->num) }}"><img src="{{ $item->logo }}" style="display:inline" align="absmiddle" width="20" height="20"> {{ $item->name }}</a>
								</li>	
								@endif	
								@endforeach
								
							</ul>
						</li>
						
                         <li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
								<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
								{{ Auth::user()->name }} <span class="caret"></span>
							</a>

							<ul class="dropdown-menu" role="menu">
								<li>
									<a target="_blank" href="{{ route('usersmanage') }}">{{ trans('manage/public.menu.grhome') }}</a>
									@if($useatype>0)
									<a target="_blank" href="{{ route('managehome', $companyinfo->id) }}">{{ trans('manage/public.menu.unitgl') }}</a>
									@endif
									<a href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a>
									
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
