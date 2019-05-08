<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
	<title>{{ $pagetitle or config('app.nameadmin') }}</title>
	<link href="{{ $bootstyle }}" id="bootstyle" rel="stylesheet">
</head>
<script src="{{ config('app.url') }}/jct/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<style type="text/css">
	*{margin: 0;padding: 0;list-style: none;}
	.management_nav{display: flex;flex-direction: row;justify-content: space-between;width: 100%;height: 80px;background: -webkit-linear-gradient(left,#22C3FF, #2EACFF);background: -o-linear-gradient(left,#22C3FF, #2EACFF); background: -moz-linear-gradient(left,#22C3FF, #2EACFF);background: linear-gradient(left,#22C3FF, #2EACFF); padding: 0 40px;box-sizing: border-box;color: #FFFFFF;}
	.management_logo{width: 205px;height: 80px;display: flex;flex-direction: row;align-items: center;}
	.management_logo img:nth-child(1){width: 30px;height: 24px;margin-right: 30px;cursor: pointer;}
	.management_logo img:nth-child(2){width: 50px;height: 50px;margin-right: 10px;}
	.management_logo text{font-size: 16px;line-height: 80px;}

	.management_list{display: flex;flex-direction: row;justify-content: center;flex-grow: 1;}
	.management_list li a{color:#EEEEEE;}
	.management_list li{width: 150px;height: 80px;line-height: 80px;display: flex;flex-direction: row;justify-content: center;cursor: pointer;align-items: center;}
	.management_list li img{width: 20px;height: 22px;margin-right: 10px;}
	.management_list li text{font-size: 14px;}
	.management_list li:hover{background: #009eed;}

	.management_user{display: flex;flex-direction: row;align-items: center;}
	.management_user img:nth-child(1){width: 30px;height: 30px;margin-right: 20px;cursor: pointer;}
	.management_user img:nth-child(2){width: 40px;height: 40px;margin-right: 10px;}
	.management_user img:nth-child(2){font-size: 12px;}

	*{margin: 0;padding: 0;list-style: none;}
	.management_box{display: flex;flex-direction: row;}
	.sidebar{border-right: 1px solid #CCCCCC;min-height:1000px;flex-grow: 0;flex-shrink: 0;}
	.sidebar_userInfo{width: 100%;display: flex;flex-direction: column;justify-content: center;align-items: center;}
	.sidebar_userInfo img{width: 60px;height: 60px;border-radius: 50%;margin-top: 20px;}
	.sidebar_userInfo>span{color: #333333;font-size: 22px;line-height: 40px;}
	.sidebar_userInfo div{display: flex;flex-direction: row;justify-content: center;align-items: center;}
	.sidebar_userInfo div span:nth-child(1){width: 10px;height: 10px;background: #00DFAD;border-radius: 50%;margin-right: 5px;}
	.sidebar_userInfo div span:nth-child(2){font-size: 16px;color: #333333;}

	.sidebar_list{margin-top: 20px;line-height: 40px;text-indent: 10px;}
	.sidebar_list_item_title{background:#EEEEEE ;height: 40px;color: #333333;}
	.sidebar_list_item li a{display: flex;flex-direction: row;box-sizing: border-box;border-left: 4px solid transparent;border-bottom: 1px solid #ccc;cursor: pointer;align-items: center;padding: 0 20px;box-sizing: border-box;height: 40px;}
	.sidebar_list_item li a img:nth-child(1){width: 21px;height: 22px;}
	.sidebar_list_item li a img:nth-child(3){width: 21px;height: 22px;transform: rotate(180deg);}
	.sidebar_list_item li a{display: flex;flex-direction: row;box-sizing: border-box;border-left: 4px solid transparent;border-bottom: 1px solid #ccc;cursor: pointer;align-items: center;padding: 0 20px;box-sizing: border-box;height: 40px;}
	.sidebar_list_item li a>span{flex-grow: 1;}
	.sidebar_list_item li.active{border-left: 4px solid #22C3FF;}
	.sidebar_list_item li.active img:nth-child(3){transform: rotate(0);}

	.sidebar_s{width: 100px;}
	.sidebar_b{width: 280px;}

	.management-content_box{position:relative;flex-grow: 1;}

	.header{position: absolute;top: 0;left: 0;padding: 40px;box-sizing: border-box;width: 100%;height: 170px;background: #fafafa;}
	.header_title{font-size: 24px;color: #333333;}
	.header_content{font-size: 16px;color: #333333;padding: 10px 0;}
	.header_btn{display: flex;flex-direction: row;}
	.header_btn input{width: 400px;height: 40px;border: 1px solid #CCCCCC;box-sizing: border-box;margin:0 20px;padding: 0 20px;}
	.header_btn div{background: #22C3FF;border-radius: 4px;color: #FFFFFF;width: 100px;height: 40px;text-align: center;line-height: 40px;cursor: pointer;}

	.table_box{margin: 190px 0 0 0px;width: 100%;box-sizing: border-box;padding-left: 40px;}
	.table_box table{width: 100%;border: 1px solid #CCCCCC;border-radius: 8px;}
	tr,th,td{padding: 0;margin: 0;text-align: center;border: none;}
	.th_width{max-width: 120px;padding: 0 10px;box-sizing: border-box;}
	.table_box table tr th{height: 80px;font-size: 16px;color:#333;}
	.table_box table tr:nth-of-type(even){background: #F8F9FE;}
	.table_box table tr th img{width: 18px;height: 18px;margin-left: 4px;}
	.table_box table tr td{height: 60px;font-size:16px ;color: #333333;}
	.img_loading img{width: 44px;height: 44px;background: red;border-radius: 50%;}
	.table_box table tr td.td_color{color: #1ABC9C;}
	.td_color{color: #1ABC9C;margin-right: 4px;cursor: pointer;}
	.td_red{color: #FF4C4C;cursor: pointer;}
	.table_box table tr td.url_box{overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;max-width: 160px;padding: 0 10px;box-sizing: border-box;word-wrap:break-word;
		margin: 0 !important;}
	tr:active{background: #F8F9FE;}
    .nav_active{background: rgb(0, 158, 237);}
	input::-webkit-outer-spin-button,input::-webkit-inner-spin-button{-webkit-appearance: none !important;margin: 0;}
</style>
<body>
<div id="app" class="management_nav">
	<div class="management_logo">
		<img src="{{ config('app.url') }}/jct/img/nav_btn_fold.png" alt="" onclick="management.navBar()"/>
		<img src="{{ config('app.logo') }}" alt="" />
		<text>{{ config('app.nameadmin') }}</text>
	</div>

	@if (Auth::guest() )
	@else
	<ul class="management_list">

		<li id="nav0" onclick="management.navBtnSel('nav0')">
			<a href="{{ route('jctadmin_startpage') }}">
			<img src="{{ config('app.url') }}/jct/img/nav_icon_dwgl.png" alt="" />
			<text>启动页管理</text>
			</a>
		</li>


		<li id="nav1" @if(strpos($tpl,'users')!==false)class="nav_active" @endif onclick="management.navBtnSel('nav1')">
			<a href="{{ route('adminusers') }}">
			<img src="{{ config('app.url') }}/jct/img/nav_iocn_yhgl.png" alt="" />
			<text>{{trans('admin/public.menu.platuser')}}</text>
			</a>
		</li>

		<li id="nav2" @if(strpos($tpl,'agent')!==false)class="active" @endif onclick="management.navBtnSel('nav2')">
			<a href="{{ route('adminagent') }}">
			<img src="{{ config('app.url') }}/jct/img/nav_icon_yygl.png" alt="" />
			<text>{{trans('admin/public.menu.agentname')}}</text>
			</a>
		</li>

		<li id="nav3"  @if(strpos($tpl,'platupgde')!==false)class="active" @endif onclick="management.navBtnSel('nav3')">
			<a href="{{ route('adminmanage','upgde') }}">
			<img src="{{ config('app.url') }}/jct/img/nav_icon_sjgl.png" alt="" />
			<text>{{trans('admin/public.menu.platupgde')}}</text>
			</a>
		</li>
	<!--
		<li id="nav4" onclick="management.navBtnSel('nav4')">
			<img src="{{ config('app.url') }}/jct/img/nav_icon_appgl.png" alt="" />
			<text>APP管理</text>
		</li>
		-->
	</ul>
	@endif
		<!--完全没用
			<div class="management_user">
				<img src="{{ config('app.url') }}/jct/img/nav_icon_set.png" alt="" />
				<img src="{{ config('app.url') }}/jct/img/left_head.png" alt="" />
				<text>userName</text>
			</div>
			-->
</div>

<div class="management_box">
	<div class="sidebar sidebar_b">
		<div class="sidebar_userInfo">
			<img src="{{ config('app.url') }}/jct/img/imgup.png"/>
			<span id="">admin</span>
			<div class="">
				<span id=""></span>
				<span id="">在线</span>
			</div>
		</div>
		<ul class="sidebar_list">
			<li>
				<div class="sidebar_list_item_title">{{ trans('admin/public.menu.company') }}</div>
				<ul class="sidebar_list_item">
					<li @if(strpos($tpl,'companylist')!==false)class="active" @endif >
						<a href="{{ route('admincompany') }}"><img src="{{ config('app.url') }}/jct/img/dwlb.png" alt="" /><span>{{ trans('admin/public.menu.companylist') }}</span></a></li>
					<li @if(strpos($tpl,'companydept')!==false)class="active" @endif >
						<a href="{{ route('admindept') }}"><img src="{{ config('app.url') }}/jct/img/dwxdbm.png" alt="" /><span>{{ trans('admin/public.menu.companydept') }}</span></a>
					</li>
					<li @if(strpos($tpl,'companyusera')!==false)class="active" @endif >
						<a href="{{ route('adminusera') }}"><img src="{{ config('app.url') }}/jct/img/dwxdyh.png" alt="" /><span>{{ trans('admin/public.menu.companyusera') }}</span></a>
					</li>
				</ul>
			</li>
			<li>
				<div class="sidebar_list_item_title">{{trans('admin/public.menu.platuser')}}</div>
				<ul class="sidebar_list_item">
					<li @if(strpos($tpl,'users')!==false)class="active" @endif><a href="{{ route('adminusers') }}"><img src="{{ config('app.url') }}/jct/img/ptyhlb.png" alt="" /><span>平台用户列表</span></a></li>
				</ul>
			</li>

			<li>
				<div class="sidebar_list_item_title">个人</div>
				<ul class="sidebar_list_item">
					<li @if(strpos($tpl,'platcog')!==false)class="active" @endif ><a href="{{ route('adminmanage','cog') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>{{ trans('admin/public.menu.platcog') }}</span></a></li>
					<li @if(strpos($tpl,'platadmin')!==false)class="active" @endif ><a href="{{ route('adminmanage','admin') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>{{ trans('admin/public.menu.platadmin') }}</span></a></li>
					<li @if(strpos($tpl,'platlog')!==false)class="active" @endif ><a href="{{ route('adminmanage','log') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>{{ trans('admin/public.menu.platlog') }}</span></a></li>
					<li @if(strpos($tpl,'plattask')!==false)class="active" @endif ><a href="{{ route('adminmanage','task') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>{{ trans('admin/public.menu.plattask') }}</span></a></li>
					<li @if(strpos($tpl,'platqueue')!==false)class="active" @endif ><a href="{{ route('adminmanage','queue') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>{{ trans('admin/public.menu.platqueue') }}</span></a></li>
					<li> <a href="{{ route('adminloginout') }}"><img src="{{ config('app.url') }}/jct/img/zx.png" alt="" /><span>{{ trans('base.exittext') }}</span></a></li>
				</ul>
			</li>
			<li>
				<div class="sidebar_list_item_title">官网设置</div>
				<ul class="sidebar_list_item">
					<li ><a href="{{ route('jctadmin_website_config') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>官网全局设置</span></a></li>
					<li ><a href="{{ route('jctadmin_website_bannerlist') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>首页轮播图</span></a></li>
					<li ><a href="{{ route('jctadmin_website_articlecat') }}"><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /><span>栏目管理</span></a></li>
				</ul>
			</li>
		</ul>
	</div>

	<div class="sidebar sidebar_s">
		<div class="sidebar_userInfo">
			<img src="{{ config('app.url') }}/jct/img/imgup.png"/>
			<span id="">admin</span>
			<div class="">
				<span id=""></span>
				<span id="">在线</span>
			</div>
		</div>
		<ul class="sidebar_list">
			<li>
				<div class="sidebar_list_item_title">单位</div>
				<ul class="sidebar_list_item">
					<li class="active"><a><img src="{{ config('app.url') }}/jct/img/dwlb.png" alt="" /></a></li>
					<li><a><img src="{{ config('app.url') }}/jct/img/dwxdbm.png" alt="" /></a></li>
					<li><a><img src="{{ config('app.url') }}/jct/img/dwxdyh.png" alt="" /></a></li>
					<li><a><img src="{{ config('app.url') }}/jct/img/ptyhlb.png" alt="" /></a></li>
				</ul>
			</li>
			<li>
				<div class="sidebar_list_item_title">个人</div>
				<ul class="sidebar_list_item">
					<li><a><img src="{{ config('app.url') }}/jct/img/grpz.png" alt="" /></a></li>
					<li><a><img src="{{ config('app.url') }}/jct/img/zx.png" alt="" /></a></li>
				</ul>
			</li>
		</ul>
	</div>

	<div class="management-content_box" >

		@yield('content')
		<script src="/bootstrap/js/bootstrap.min.js"></script>
		<script src="/res/plugin/jquery-rockvalidate.js"></script>
		<script src="/res/plugin/jquery-rockmodel.js"></script>
		@yield('script')
		@include('layouts.footer')
	</div>

</div>

</body>
<script type="text/javascript">
    $(".sidebar_b").hide();
    $(".sidebar_s").hide();
    var management = {
        oldNavBtnSel:null,
        navId:0,
        navBtnSel(str){
            $("#" + management.oldNavBtnSel).css("background","none")
            $("#" + str).css("background","#009eed")
            management.oldNavBtnSel = str;
            if(management.navId == 0){
                $(".sidebar_b").hide();
                $(".sidebar_s").show();
                $(".management_content").addClass("management_content_s");
                $(".management_content").removeClass("management_content_b");
            }else{
                $(".sidebar_b").show();
                $(".sidebar_s").hide();
                $(".management_content").addClass("management_content_b");
                $(".management_content").removeClass("management_content_s");
            }
        },
        navBar(){
            console.log(management.navId)
            if(management.navId == 0){
                $(".sidebar_b").show();
                $(".sidebar_s").hide();
                management.navId = 1;
            }else{
                $(".sidebar_b").hide();
                $(".sidebar_s").show();
                management.navId = 0;
            }
        }
    }
</script>
</html>
