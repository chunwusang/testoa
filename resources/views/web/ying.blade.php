<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>{{ $pagetitle }}</title>
<link rel="stylesheet" type="text/css" href="/css/weui.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/rui.css"/>
<link rel="stylesheet" type="text/css" href="/res/fontawesome/css/font-awesome.min.css">
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jsm.js"></script>
<script type="text/javascript" src="/js/base64-min.js"></script>
<style>
.weui_tab{position:fixed;bottom:0px;width:100%;height:50px}
.weui_navbar_item{font-size:16px;}
.weui_navbar_item span{position:absolute;left:3px;top:3px}
.weui_navbar_item:active{background-color:#f5f5f5}
.weui_navbar_item_disabled{color:#aaaaaa}
.weui_media_box:before{left:0}
.showblank{color:#aaaaaa;font-size:14px;padding-bottom:15px;text-align:center}
.showblanks{padding:10px;color:#aaaaaa;font-size:12px;text-align:center}
.notrecord{text-align:center;font-size:20px;padding-top:50px;color:#aaaaaa}
</style>
<script>
var menuarr = {!! json_encode($menuarr) !!},
	agenhnum= '{{ $agenhinfo->num }}',
	cnum 	= '{{ $cnum }}',adminid={{ $useainfo->id }};
function yingyonginit(){}
function initbody(){
	yy.init();
	yingyonginit();
}

var yy = {
	init:function(){
		this.showobj = $('#mainbody');
		this.showfirstxu();
	},
	resizehei:function(){
		var hei= this.getheight();
		if(agentlx==0){
			var ob = this.showobj.css({'height':''+hei+'px'});
			return ob;
		}
	},
	getheight:function(ss){
		var hei = 0;if(!ss)ss=0;
		if(get('searsearch_bar'))hei+=45;
		if(get('header_title'))hei+=50;
		if(get('footerdiv'))hei+=50;
		return $(window).height()-hei+ss;
	},
	showfirstxu:function(){
		var i,len=menuarr.length,xu=0,atype=js.request('atype');
		if(menuarr.length==0 || !get('pager_view'))return;
		if(atype){
			for(i=0;i<len;i++){
				if(menuarr[i].url==atype){
					xu = i;
					break;
				}
			}
		}else{
			if(menuarr[0].stotal==0){
				xu = 0;
			}else{
				xu = 1;
			}
		}
		this.clickmenus(xu,true);
	},
	clickmenu:function(xu, o1){
		if(o1 && o1.className.indexOf('disabled')>0)return;
		var d = menuarr[xu];
		$('#menudiv').remove();
		if(d.stotal==0){
			this.clickmenus(xu, false);
		}else{
			var o=$(o1),w=1/3*100,i,slen=menuarr.length,a1,sel='';
			var s='<div id="menudiv" style="position:fixed;z-index:5;left:'+(o.offset().left)+'px;bottom:50px; background:white;width:'+w+'%" class="menulist r-border-r r-border-l">';
			for(i=0;i<slen;i++){
				a1=menuarr[i];
				sel='';
				if(a1.pid==d.id){
					if(a1.url==this.atype)sel=' <i class="weui_icon_success_no_circle"></i>';
					s+='<div onclick="yy.clickmenu('+i+')" class="r-border-t" style="color:'+a1.color+';">'+a1.name+''+sel+'</div>';
				}
			}
			s+='</div>';
			$('body').append(s);
		}
	},
	clickevent:function(){
		return true;
	},
	clickmenus:function(xu){
		var d = menuarr[xu],type=d.type;
		var bo = this.clickevent(d);
		if(!bo)return;
		if(type=='add'){
			if(!d.url)d.url = agenhnum;
			var url = '/input/'+cnum+'/'+d.url+'';
			js.location(url);
			return;
		}
		if(type=='url'){
			js.location(d.url);
			return;
		}
		var lx = type;
		if(d.url)lx = d.url;
		this.sousoukey = '';
		this.showtabstr(d);
		this.getdata(lx+'_'+d.id, 1);
	},
	showtabstr:function(d){
		var cs = {'color':'#336699','border-top':'1px #336699 solid'};
		$('div[tempid]').css({'color':'','border-top':''});
		$('div[tempid='+d.id+']').css(cs);
		if(d.pid>0)$('div[tempid='+d.pid+']').css(cs);
		this.settitle(d.name);
	},
	searchuser:function(){
		$('#searsearch_bar').addClass('weui_search_focusing');
		$('#search_input').focus();
	},
	searchcancel:function(){
		$('#search_input').blur();
		$('#searsearch_bar').removeClass('weui_search_focusing');
	},
	souclear:function(){
		$('#search_input').val('').focus();
	},
	sousousou:function(){
		var key = $('#search_input').blur().val();
		this.keysou(key);
	},
	sousoukey:'',
	keysou:function(key){
		if(this.sousoukey == key)return;
		this.sousoukey = key;
		this.regetdata(false,1);
	},
	settitle:function(tit){
		document.title = tit;
		$('#header_title').html(tit);
	},
	
	//-----------只有列表页才运行以下---------------
	regetdata:function(o,p){
		var mo = 'mode';
		if(o){
			o.innerHTML='<img src="/images/loading.gif" align="absmiddle">';
			mo = 'none';
		}
		this.getdata(this.atype,p);
	},
	geturl:function(act,nus){
		if(!nus)nus = agenhnum;
		return 'api/agent/'+cnum+'/'+nus+'/'+act+''
	},
	params:{},
	getdata:function(lx,p,bo){
		this.page  = p;
		this.atype = lx;
		if(!bo)js.loading('加载中...');
		var key = ''+this.sousoukey;
		if(key)key=jm.base64encode(key);
		if(p==1)this.data=[];
		var can = {'page':p,'atype':lx,'key':key};
		for(var i in this.params)can[i]=this.params[i];
		js.ajax(this.geturl('data'),can, function(ret){
			yy.showdata(ret.data);
		},'get',function(estr){
			js.msgerror(estr);
		});
	},
	reloaddata:function(bo){
		this.getdata(this.atype,1,bo);
	},
	nextdata:function(bo){
		this.getdata(this.atype, this.page+1, bo);
	},
	showdata:function(da){
		var i,rows = da.rows;
		var s = '',pager=da.pager;
		this.fieldsarr	= da.fieldsarr;
		if(pager.page==1)$('#data_view').html('');
		$('#wujilu').hide();
		if(pager.count>0){
			s = '共'+pager.count+'条记录';
			if(pager.maxpage>1)s+=',当前'+pager.maxpage+'/'+pager.page+'';
			if(pager.maxpage!=pager.page)s+=',<a href="javascript:;" onclick="yy.nextdata(false)">点击加载</a>';
		}else{
			$('#wujilu').show();
		}
		$('#pager_view').html(s);
		for(i=0;i<rows.length;i++)this.showdatastr(rows[i]);
	},
	showdatastr:function(d){
		var s='',farr=this.fieldsarr,val,i,fid,s1='',s2='',s3='',flx,st='',oi;
		oi=this.data.push(d);
		if(d.ishui==1)st='color:#aaaaaa;';
		if(d.liststyle)st+=d.liststyle;//卡片样式
		for(i=0;i<farr.length;i++){
			fid = farr[i].fields;
			flx = farr[i].fieldstype;
			val = d[fid];
			if(!isempt(val)){
				if(fid=='title'){
					s1 = '<div class="title">'+val+'</div>';
					continue;
				}
				if(flx=='uploadimg'){
					s3 = '<div class="imgs"><img width="100%" src="'+val+'"></div>';
					continue;
				}
				s2+='<div><font class="fields">'+farr[i].name+'：</font>'+val+'</div>';
			}
		}
		s+='<div style="'+st+'" onclick="yy.showmenu('+oi+')" class="r-border contlist">'+s1+''+s3+''+s2+'';
		if(d.statustext)s+='<div style="background-color:'+d.statuscolor+';opacity:0.7" class="zt">'+d.statustext+'</div>';
		s+='</div>';
		//alert(s);
		$('#data_view').append(s);
	},
	showmenudata:[],
	showmenu:function(oi, lobs){
		this.tempoi	= oi;
		var a = this.data[oi-1],mid = a.id,i,num=''+agenhnum;
		if(a.mid && a.agenhnum){
			mid = a.mid;
			num = a.agenhnum;
		}
		var da = [{name:'详情',lx:'xiang'}];
		if(!lobs){
			da.push({name:'<img src="/images/loadings.gif" align="absmiddle"> 加载菜单中...',lx:999});
			this.loadoptnum(num,mid);
		}else{
			for(i=0;i<this.showmenudata.length;i++)da.push(this.showmenudata[i]);
		}
		js.showmenu({
			data:da,
			width:150,
			onclick:function(d){
				yy.showmenuclick(d);
			}
		});
	},
	showmenuclick:function(d){
		var a = this.data[this.tempoi-1],mid = a.id,num=''+agenhnum;
		if(a.mid && a.agenhnum){
			mid = a.mid;
			num = a.agenhnum;
		}
		var lx= d.lx,url = '/detail/'+cnum+'/'+num+'/'+mid+'';
		if(lx=='xiang'){
			js.location(url);
		}
		if(lx=='edit'){
			url = '/input/'+cnum+'/'+num+'/'+mid+'';
			js.location(url);
		}
		if(lx=='del'){
			js.confirm('确定要删除吗？',function(jg){
				if(jg=='yes')yy.delbill(num,mid);
			});
		}
		if(lx=='optm'){
			if(d.type==1){
				this.optmenu(d.optmid,num,mid,'');
			}
			if(d.type==0){
				var sms = (d.issm==1) ? '必填' : '选填';
				js.prompt(d.name, '请输入['+d.name+']的说明('+sms+')', function(jg,txt){
					if(jg!='yes')return;
					if(d.issm==1 && !txt){
						js.msgerror('没有输入说明');
						return;
					}
					yy.optmenu(d.optmid,num,mid,txt);
				});
			}
			//打开新窗口
			if(d.type==4){
				var upg = d.upgcont;
				if(isempt(upg)){
					js.msgerror('没有设置打开的操作地址');
				}else{
					js.location(this.getupgurl(upg));
				}
			}
		}
		if(lx=='remind'){
			url = '/input/'+cnum+'/remind/'+d.remindid+'';
			if(d.remindid==0)url+='?num='+num+'&mid='+mid+'';
			js.location(url);
		}
	},
	getupgurl:function(str){
		if(str.substr(0,4)=='http')return str;
		var a1 = str.split('|'),lx = a1[0],mk = a1[1],cs=a1[2];
		var url= '';
		if(lx=='add'){
			url='/input/'+cnum+'/'+mk+'';
		}
		if(lx=='xiang'){
			url='/detail/'+cnum+'/'+mk+'/'+cs+'';
			cs = a1[3];
		}
		if(cs)url+='?'+cs;
		return url;
	},
	optmenu:function(optmid,num,mid,sm){
		js.loading();
		js.ajax(this.geturl('data_optmenu', num),{optmid:optmid,sm:sm,mid:mid}, function(ret){
			js.msgok('处理成功');
			yy.reloaddata(true);
		},'post', function(estr){
			js.msgerror(estr);
		});
	},
	delbill:function(num,mid){
		js.loading('删除中...');
		js.ajax(this.geturl('data_delbill', num),{mid:mid},function(ret){
			js.msgok('删除成功');
			yy.reloaddata(true);
		},'post',function(estr){
			js.msgerror(estr);
		});
	},
	loadoptnum:function(num,mid){
		js.ajax(this.geturl('data_getoptmenu', num),{mid:mid},function(ret){
			var da = ret.data;
			yy.showmenudata = da;
			yy.showmenu(yy.tempoi, true);
		},'get',function(estr){
			js.msgerror(estr);
		});
	},
	
	getacturl:function(){
		var url = '/api/agent/'+cnum+'/'+agenhnum+'/flow_yunact';
		return url;
	},
	
	imgview:function(url){
		$.imgview({url:url});
	},
	imgviews:function(o1){
		var lup = o1.src;
		lup = lup.replace('_s.','.');
		this.imgview(lup);
	},
	openfiles:function(num,glx, o1){
		if(num=='undefined'){
			this.imgview(o1.src);
			return;
		}
		if(!glx)glx=0;
		js.loading('处理中...');
		js.ajax('api/we/file_down/'+cnum+'', {'num':num,'glx':glx},function(ret){
			var da = ret.data;
			if(glx==0 && da.isimg=='1'){
				yy.imgview(da.upurl);
			}else{
				js.location(da.upurl);
			}
		},'post');
	},
	
	runurl:function(act,cans, fun, lxs){
		var url = this.getacturl();
		js.loading();
		if(!cans)cans={};
		cans.act = act;
		if(!lxs)lxs='get';
		if(!fun)fun=function(){}
		js.ajax(url, cans, function(ret){
			js.unloading();
			fun(ret);
		},lxs, function(msg){
			js.msg();
			js.msgerror(msg);
		});
	}
};

function runurl(act,cans){
	js.loading();
	if(!cans)cans={};
	cans.act = act;
	js.ajax(yy.getacturl(), cans, function(ret){
		var msg = ret.data;
		if(msg.length==0)msg='处理成功';
		js.msgok(msg);
		yy.reloaddata(true);
	},'post', function(msg){
		js.msg();
		js.msgerror(msg);
	});
}
function runurls(act,cans,lx){
	if(!cans)cans={};
	if(!lx)lx='get';
	cans.act = act;
	js.ajax(yy.getacturl(), cans,false,lx);
}
</script>
</head>


<body>

@if ($showheader==1)
<div id="headertop">
	<div class="r-header">
		<div onclick="js.reload()" id="header_title" class="r-header-text">{{ $pagetitle }}</div>
		<span onclick="js.back()" class="r-position-left r-header-btn"><i class="icon-chevron-left"></i></span>
		@if($addxu>-1)
		<span onclick="yy.clickmenu({{ $addxu }})" class="r-position-right r-header-btn"><i class="icon-plus"></i></span>
		@endif
	</div>
	<div class="blank50"></div>
</div>
@endif

@if($searchbool)
<div style="z-index:2" id="searsearch_bar" class="weui_search_bar">
	<form onclick="yy.searchuser()" class="weui_search_outer" onsubmit="yy.sousousou();return false;">
		<div class="weui_search_inner">
			<i class="weui_icon_search"></i>
			<input type="search" class="weui_search_input" id="search_input" placeholder="输入关键词搜索" >
			<a onclick="yy.souclear()" class="weui_icon_clear"></a>
		</div>
		<label for="search_input" class="weui_search_text" id="search_text">
			<i class="weui_icon_search"></i>
			<span>输入关键词搜索</span>
		</label>
	</form>
	<a onclick="yy.searchcancel()" style="color:#336699" class="weui_search_cancel">取消</a>
</div>
@endif

<div id="mainbody" onclick="$('#menudiv').remove()">
	@include($tplpath)
</div>



@if($menuarr)
<div style="height:50px;overflow:hidden"></div>
<div id="footerdiv" style="z-index:5" class="weui_tab">
	<div class="weui_navbar weui_tabbar">
		<?php
		$xu = 0;
		?>
		@foreach($menuarr as $k=>$item)
		@if($item->pid==0)
		
		<div temp="tabying" tempxu="{{ $xu++ }}" tempid="{{ $item->id }}" onclick="yy.clickmenu({{ $k }},this)" class="weui_navbar_item {{ $item->disabled }}">{{ $item->name }}
		@if($item->stotal>0)<i class="icon-caret-down"></i> @endif
		</div>
		@endif
		@endforeach
	</div>
</div>
@endif

<script src="/res/js/jquery-imgview.js"></script>

</body>
</html>