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
<style>
.list-group-item{border-radius:0px;border-left:none;border-right:none;cursor:pointer;TEXT-DECORATION:none}
.list-group-item:last-child{border-radius:0px}
.list-group-item:first-child{border-radius:0px;border-top:none}
.list-group-item.activea{ background:rgba(0,0,0,0.1)}
</style>
</head>
<body style="overflow:hidden">

<div id="topheader">
<nav class="navbar navbar-default navbar-static-top" style="margin:0px">
	<div>
		<div class="navbar-header">

			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
				<span class="sr-only">Toggle Navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

		   
			<a class="navbar-brand" href="">
			   <img src="{{ $companyinfo->logo }}" style="display:inline;" align="absmiddle" height="24" width="24"> {{ $companyinfo->name }}
			</a>
		</div>

		<div class="collapse navbar-collapse" id="app-navbar-collapse">
		   
			<ul class="nav navbar-nav" id="topmenuul">
				<li onclick="tab.showhome()"><a style="cursor:pointer">首页</a></li>
				@foreach($agenharr as $atype=>$data)
				<li onclick="tab.clickmenuul({{ $agenhtarr[$atype][1] }})"><a style="cursor:pointer">{{ $atype }}<span id="badgeatype_{{ $agenhtarr[$atype][1] }}" class="badge">{{ $agenhtarr[$atype][0] }}</span></a></li>
				@endforeach
			</ul>
		   
			<ul class="nav navbar-nav navbar-right">
				<li><a href="javascript:;" onclick="openmobile()">{{ trans('manage/public.menu.phone') }}</a></li>
				<li><a href="javascript:;" onclick="openreim()">REIM</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
						<div style="padding-right:10px">
						<img style="width:18px;height:18px;border-radius:50%" src="{{ Auth::user()->face }}" align="absmiddle">
						{{ $useainfo->name }}<span class="caret"></span>
						</div>
					</a>
					<ul class="dropdown-menu" role="menu">
						@if($useatype>0)
						<li><a target="_blank" href="{{ route('manage', $companyinfo->num) }}">{{ trans('manage/public.menu.unitgl') }}</a></li>
						@endif
						<li><a target="_blank" href="{{ route('userscog') }}">{{ trans('users/cog.title') }}</a></li>
						<li><a target="_blank" href="{{ route('usersmanage') }}">{{ trans('manage/public.menu.grhome') }}</a></li>
						<li><a href="javascript:;" onclick="pipeiall()">{{ trans('base.pipeitext') }}</a></li>
						<li><a href="javascript:;" onclick="js.reload()">{{ trans('base.reloadtext') }}</a></li>	
						<li><a href="javascript:;" onclick="exitlogin()">{{ trans('base.exittext') }}</a></li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
</div>

    
<table width="100%" style="height:300px">
<tr valign="top">
	<td>
	<div id="menuleft" style="width:200px;overflow:hidden;border-right:5px rgba(0,0,0,0.1) solid">
		<div id="menutops">
			<!-- <div>我是主题内容</div> -->
		<ul class="list-group" style="margin:0">
			<li class="list-group-item list-group-item-info"><b id="menutext">基本</b>
			<!--<span style="float:right">
			<i class="glyphicon glyphicon-search"></i>&nbsp;
			<i onclick="tab.chagneleft(this)" class="glyphicon glyphicon-menu-hamburger"></i></span>-->
			</li>
		</ul>
		</div>
		<div style="overflow:auto;height:200px;" id="menulist">
			<!-- <div>我是主题内容</div> -->
			<ul class="list-group" style="margin:0" id="menushow">
			</ul>
		</div>
	</div>
	</td>
	<td width="100%" height="100%" id="iframecont"></td>
</tr>
</table>
<div id="nowclockdiv" style="position:absolute;bottom:2px;right:5px;display:none"><a href="javascript:;" onclick="tab.closenowtabs()" title="{{ trans('users/index.closetabs') }}"><i class="glyphicon glyphicon-remove"></i></a></div>
<script src="/res/plugin/jquery-rockmodel.js"></script>
<script>
var cnum = '{{ $companyinfo->num }}',agenharr={!! json_encode($agenharr) !!},agenhobj={};
function initbody(){
	resize();
	tab.init();
	tab.clickmenuul(0);
	tab.add('home','{{ route('usershome', $companyinfo->num) }}');
	
	//禁止后退
	try{
		history.pushState(null, null, document.URL);
		window.addEventListener('popstate', function (){
			history.pushState(null, null, document.URL);
		});
	}catch(e){}
}
function resize(){
	var hei = winHb()-$('#topheader').height()-$('#menutops').height();
	$('#menulist').css('height',''+hei+'px');
}
function clickmenu(xu){
	$("li[atype='"+xu+"']").toggle();
}
function clickmenusubs(num){
	var d = agenhobj[num];
	if(!d)return;
	d.stotal = 0;
	clickmenusub(d.oi,d.id,d.name,d.num,d.url);
}
function clickmenusub(lx, ids, name,num, url){
	var st = $('#badgeatype_'+lx+'_'+ids+'').html();
	if(!st)st = '0';
	var st1 = $('#badgeatype_'+lx+'').html();
	if(!st1)st1 = '0';
	st1 = parseInt(st1)- parseInt(st);
	if(st1==0)st1 = '';
	$('#badgeatype_'+lx+'').html(''+st1+'');
	$('#badgeatype_'+lx+'_'+ids+'').html('');
	var bo = tab.add(num, url, name);
}
function addtabs(num, url, name){
	return tab.add(num, url, name);
}
var tab = {
	openarr:[],
	nowtabs:{},
	openobj:{},
	add:function(num, url, name){
		var ifid = 'iframe_'+num+'';
		$("iframe[temp='ifcont']").hide();
		this.nowtabs = {name:name,url:url,num:num};
		$('#nowclockdiv').hide();
		if(num!='home')$('#nowclockdiv').show();
		this.openarr.push(this.nowtabs);
		this.openobj[num] = this.nowtabs;
		if(get(ifid)){
			$('#'+ifid+'').show();
			return true;
		}
		$.rockmodelmsg('wait','{{ trans('base.loading') }}');
		var s = '<iframe temp="ifcont" id="'+ifid+'" width="100%" height="100%" frameborder="0"></iframe>';
		$('#iframecont').append(s);
		get(ifid).src = url;
		get(ifid).onload=this.ifloadsucc;
		$('#menu_'+num+'').addClass('activea');
	},
	changetabs:function(num){
		var narr = this.openobj[num];
		if(!narr)return false;
		return this.add(narr.num,narr.url,narr.name);
	},
	chagneleft:function(o1){
		$('#menuleft').css('width','30px');
	},
	ifloadsucc:function(){
		$.rockmodelmsg('none');
	},
	closenowtabs:function(){
		this.closetabs(this.nowtabs.num);
	},
	closetabs:function(num){
		$('#iframe_'+num+'').remove();
		$('#menu_'+num+'').removeClass('activea');
		this.openobj[num] = false;
		var len = this.openarr.length,i,nus;
		for(i=len-2;i>=0;i--){
			var bo = this.changetabs(this.openarr[i].num);
			if(bo)break;
		}
		
	},
	onmorelit:function(o1){
		if(this.downobj)this.downobj.remove();
		this.downobj=$.rockmenu({
			data:[{name:'{{ trans('base.reloadtext') }}',lx:0},{
				name:'{{ trans('manage/public.menu.grhome') }}',lx:2
			},{
				name:'{{ trans('base.pipeitext') }}',lx:3
			},{
				name:'{{ trans('base.exittext') }}',lx:1
			}],
			itemsclick:function(d){
				if(d.lx==0)js.reload();
				if(d.lx==1)exitlogin();
				if(d.lx==2)window.open('{{ route('usersmanage') }}');
				if(d.lx==3)pipeiall();
			},
			width:130
		});
		var off=$(o1).offset();
		setTimeout(function(){tab.downobj.showAt(off.left,off.top+20);},10);
	},
	init:function(){
		var i,at,d,len,menu,oi=0;
		for(at in agenharr){
			menu = agenharr[at];
			len  = menu.length;
			for(i=0;i<len;i++){
				d = menu[i];
				d.oi = oi;
				agenhobj[d.num] = d;
			}
			oi++;
		}
	},
	clickmenuul:function(oi){
		$('#topmenuul').find('li').removeClass('active');
		$('#topmenuul').find('li:eq('+(oi+1)+')').addClass('active');
		var at,nat,i1,i,len,menu,d,s,act,typs='';
		i1 = 0;
		for(at in agenharr){
			if(i1==oi){
				nat = at;
				break;
			}
			i1++;
		}
		$('#menutext').html(nat);
		menu = agenharr[nat];
		len  = menu.length;
		s 	 = '';
		for(i=0;i<len;i++){
			d = menu[i];
			act='';
			if(get('iframe_'+d.num+''))act=' activea';
			if(typs!=d.atypes){
				if(d.atypes)s+='<a class="list-group-item" style="padding:5px;TEXT-DECORATION:none;font-size:12px;opacity:0.5">'+d.atypes+'</a>';
			}
			s+='<a onclick="clickmenusubs(\''+d.num+'\')" style="TEXT-DECORATION:none" class="list-group-item'+act+'" id="menu_'+d.num+'"><img src="'+d.face+'" style="display:inline" align="absmiddle" width="20" height="20"> '+d.name+'';
			if(d.stotal>0)s+='	<span id="badgeatype_'+oi+'_'+d.id+'" class="badge">'+d.stotal+'</span></a>';
			s+='</a>';
			typs=d.atypes;
		}
		$('#menushow').html(s);
	},
	showhome:function(){
		if(this.nowtabs.num=='home'){
			js.msgok('当前已是首页');
			return;
		}
		this.changetabs('home');
	}
};

function exitlogin(){
	var url = '{{ route('usersloginout') }}';
	$.rockmodelconfirm('{{ trans('users/index.exitmsg') }}',function(lx){
		if(lx=='yes'){
			js.location(url);
		}
	});
}

function openmobile(){
	js.open('/we/index.html?cnum='+cnum+'', 350,550);
}

function openreim(){
	js.open('/reim/index.html?cnum='+cnum+'', 860,550);
}

function pipeiall(){
	var url = '/api/unit/'+cnum+'/company_pipei';
	$.rockmodelmsg('wait');
	js.ajax(url,false, function(ret){
		$.rockmodelmsg('ok',ret.data,5);
	},'get',function(msg){
		$.rockmodelmsg('msg',msg);
	});
}

/**
*	打开详情
*/
function openxiangind(name,num,mid){
	var url = '/detail/'+cnum+'/'+num+'/'+mid+'';
	return openurlind(name,url);
}

/**
*	打卡录入页
*/
function openluind(name,num,mid){
	var url = '/input/'+cnum+'/'+num+'';
	if(mid)url+='/'+mid+'';
	return openurlind(name,url);
}

function imgviewind(url){
	$.imgview({url:url,ismobile:false,iconpath:'glyphicon glyphicon'});
	return true;
}

function openurlind(name, url,op1){
	iframeobj=$.rockmodeliframe(name,url,op1);
	return iframeobj;
}

function showcallback(ts){
	try{iframeobj.close();}catch(e){}
	$.rockmodelmsg('ok', ts);
}


</script>	

<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>

<!--rockmenu-->
<link href="/res/plugin/jquery-rockmenu.css" rel="stylesheet">
<script src="/res/plugin/jquery-rockmenu.js"></script>
<script src="/res/js/jquery-imgview.js"></script>

</body>
</html>