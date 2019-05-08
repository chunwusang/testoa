var QOM = 'xinhuyun_',apiurl='',NOWURL='',HOST,TOKEN='',TOKENKEY='usertoken',device='',nwjsgui=false;
function initbody(){}
function bodyunload(){}
$(document).ready(function(){
	try{if(typeof(nw)=='object'){nwjsgui = nw;}else{nwjsgui = require('nw.gui');}}catch(e){nwjsgui=false;}
	HOST 	= js.gethost();
	var ttpe= js.request(TOKENKEY);
	if(ttpe)js.setoption(TOKENKEY, ttpe);
	TOKEN 	= js.getoption(TOKENKEY);
	apiurl 	= js.getoption('apiurl', apiurl);
	device	= js.cookie('deviceid');
	if(device=='')device=js.now('time');
	js.savecookie('deviceid', device, 365);
	js.navigator();
	initbody();
	$('body').click(function(e){
		js.downbody(this, e);
	});
	$(window).unload(function(){
		bodyunload();
	});
});
var js={focusval:0,url:''};
var isIE=true;
if(!document.all)isIE=false;
var get=function(id){return document.getElementById(id)};
var isempt=function(an){var ob	= false;if(an==''||an==null||typeof(an)=='undefined'){ob=true;}if(typeof(an)=='number'){ob=false;}return ob;}
var strreplace=function(str){if(isempt(str))return '';return str.replace(/[ ]/gi,'').replace(/\s/gi,'')}
var strhtml=function(str){if(isempt(str))return '';return str.replace(/\</gi,'&lt;').replace(/\>/gi,'&gt;')}
var form=function(an,fna){if(!fna)fna='myform';return document[fna][an]}
var xy10=function(s){var s1=''+s+'';if(s1.length<2)s1='0'+s+'';return s1;};
js.getarr=function(caa,bo){
	var s='';
	for(var a in caa)s+=' @@ '+a+'=>'+caa[a]+'';
	if(!bo)alert(s);
	return s;
}
js.str=function(o){
	o.value	= strreplace(o.value);
}
js.gethost=function(){
	var url = location.href,sau='';
	var urla= url.split('//');
	try{sau = urla[1].split('/')[0];}catch(e){}
	if(sau.indexOf('demo.rockoa.com')>=0)ISDEMO=true;
	var lse = url.lastIndexOf('/');NOWURL = url.substr(0, lse+1);
	apiurl	= urla[0]+'//'+sau+'';
	if(apiurl.substr(0,4)!='http')apiurl='http://127.0.0.1:166';
	return sau;
}
function winHb(){
	var winH=(!isIE)?window.innerHeight:document.documentElement.offsetHeight;
	return winH;
}
function winWb(){
	var winH=(!isIE)?window.innerWidth:document.documentElement.offsetWidth;
	return winH;
}
js.scrolla	= function(){
	var top	= $(document).scrollTop();
	js.scroll(top);
}
js.request=function(name,dev,url){
	if(!dev)dev='';
	if(!name)return dev;
	if(!url)url=location.href;
	if(url.indexOf('\?')<0)return dev;
	if(url.indexOf('#')>0)url = url.split('#')[0];
	var neurl=url.split('\?')[1];
	neurl=neurl.split('&');
	var value=dev,i,val;
	for(i=0;i<neurl.length;i++){
		val=neurl[i].split('=');
		if(val[0].toLowerCase()==name.toLowerCase()){
			value=val[1];
			break;
		}
	}
	if(!value)value='';
	return value;
}
js.now=function(type,sj){
	if(!type)type='Y-m-d';
	if(type=='now')type='Y-m-d H:i:s';
	var dt,ymd,his,weekArr,Y,m,d,w,H=0,i=0,s=0,W;
	if(typeof(sj)=='string')sj=sj.replace(/\//gi,'-');
	if(/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}/.test(sj)){
		sj=sj.split(' ');
		ymd=sj[0];
		his=sj[1];if(!his)his='00:00:00';
		ymd=ymd.split('-');
		his=his.split(':');
		H = his[0];if(his.length>1)i = his[1];if(his.length>2)s = his[2];
		dt=new Date(ymd[0],ymd[1]-1,ymd[2],H,i,s);
	}else{
		dt=(typeof(sj)=='number')?new Date(sj):new Date();
	}
	var xy10=function(s){var s1=''+s+'';if(s1.length<2)s1='0'+s+'';return s1;};
	weekArr=new Array('日','一','二','三','四','五','六');
	Y=dt.getFullYear();
	m=xy10(dt.getMonth()+1);
	d=xy10(dt.getDate());
	w=dt.getDay();
	H=xy10(dt.getHours());
	i=xy10(dt.getMinutes());
	s=xy10(dt.getSeconds());
	W=weekArr[w];
	if(type=='time'){
		return dt.getTime();
	}else{
		return type.replace('Y',Y).replace('m',m).replace('d',d).replace('H',H).replace('i',i).replace('s',s).replace('w',w).replace('W',W);
	}
}
js.float=function(num,w){
	if(isNaN(num)||num==''||!num||num==null)num='0';
	num=parseFloat(num);
	if(!w&&w!=0)w=2;
	var m=num.toFixed(w);
	return m;	
}
js.splittime=0;
js.getsplit=function(){
	if(!js.servernow)return false;
	var dt=js.now('Y-m-d H:i:s');
	var d1=js.now('time',dt);	
	var d2=js.now('time',js.servernow);
	js.splittime=d1-d2;
}
js.serverdt=function(atype){
	if(!atype)atype='Y-m-d H:i:s';
	var d1=js.now('time')-js.splittime;
	var dt=js.now(atype,d1);
	return dt;
}
js.decode=function(str){
	var arr	= {length:-1};
	try{
		arr	= new Function('return '+str+'')();
	}catch(e){}
	return arr;
}
js.formatsize=function(size){
	var arr = new Array('Byte', 'KB', 'MB', 'GB', 'TB', 'PB');
	var e	= Math.floor(Math.log(size)/Math.log(1024));
	var fs	= size/Math.pow(1024,Math.floor(e));
	return js.float(fs)+' '+arr[e];
}
js.getformdata=function(nas){
	var da	={},ona='',o,type,val,na,i,obj;
	if(!nas)nas='myform';
	obj	= document[nas];
	for(i=0;i<obj.length;i++){
		o 	 = obj[i];type = o.type,val = o.value,na = o.name;
		if(o.disabled || !na)continue;
		if(type=='checkbox'){
			val	= '0';
			if(o.checked)val='1';
			da[na]	= val;
		}else if(type=='radio'){
			if(o.checked)da[na]	= val;					
		}else{
			da[na] = val;
		}
		if(na.indexOf('[]')>-1){
			if(ona.indexOf(na)<0)ona+=','+na+'';
		}
	}
	if(ona != ''){
		var onas = ona.split(',');
		for(i=1; i<onas.length; i++){
			da[onas[i].replace('[]','')] = js.getchecked(onas[i]);
		}
	}
	return da;
}
js.selall = function(o,na,bh){
	var i,oi1;
	if(bh){
		o1=$("input[name^='"+na+"']");
	}else{
		o1=$("input[name='"+na+"']");
	}
	for(i=0;i<o1.length;i++){
		if(!o1[i].disabled)o1[i].checked = o.checked;
	}
}
js.getchecked=function(na,bh){
	var s = '';
	var o1;
	if(bh){
		o1=$("input[name^='"+na+"']");
	}else{
		o1=$("input[name='"+na+"']");
	}
	for(var i=0;i<o1.length;i++){
		if(o1[i].checked && !o1[i].disabled)s+=','+o1[i].value+'';
	}
	if(s!='')s=s.substr(1);
	return s;
}
js.cookie=function(name){
	var str=document.cookie,cda,val='',arr,i;
	if(str.length<=0)return '';
	arr=str.split('; ');
	for(i=0;i<arr.length;i++){
		cda=arr[i].split('=');
		if(name.toLowerCase()==cda[0].toLowerCase()){
			val=cda[1];
			break;
		}
	}
	if(!val)val='';
	return val;
}
js.savecookie=function(name,value,d){
	var expires = new Date();
	if(!d)d=365;
	if(!value)d=-10;
	expires.setTime(expires.getTime()+d*24*60*60*1000);
	var str=''+name+'='+value+';expires='+expires.toGMTString()+';path=/';
	document.cookie = str;
}
js.backtop=function(ci){
	if(!ci)ci=0;
	$('body,html').animate({scrollTop:ci});
	return false;
}
js.backto = function(oid){
	if(!get(oid))return;
	var of	= $('#'+oid+'').offset();
	this.backtop(of.top);
	return false;
}
js.applyIf=function(a,b){
	if(!a)a={};
	if(!b)b={};
	for(var c in b)if(typeof(a[c])=='undefined')a[c]=b[c];
	return a;
}
js.apply=function(a,b){
	if(!a)a={};
	if(!b)b={};
	for(var c in b)a[c]=b[c];
	return a;
}
js.move=function(id,rl){
	var _left=0,_top=0,_x=0,_right=0,_y=0;
	var obj	= id;if(!rl)rl='left';
	if(typeof(id)=='string')obj=get(id);
	var _Down=function(e){
		try{
			var s='<div id="divmovetemp" style="filter:Alpha(Opacity=0);opacity:0;z-index:99999;width:100%;height:100%;position:absolute;background-color:#000000;left:0px;top:0px;cursor:move"></div>';
			$('body').prepend(s);
			_x = e.clientX;_y = e.clientY;_left=parseInt(obj.style.left);_top=parseInt(obj.style.top);_right=parseInt(obj.style.right);
			document.onselectstart=function(){return false}
		}catch(e1){}		
	}
	var _Move=function(e){
		try{
			var c=get('divmovetemp').innerHTML;
			var x = e.clientX-_x,y = e.clientY-_y;
			if(rl=='left')obj.style.left=_left+x+'px';
			if(rl=='right')obj.style.right=_right-x+'px';
			obj.style.top=_top+y+'px';
		}catch(e1){_Down(e)}
	}
	var _Up=function(){
		document.onmousemove='';
		document.onselectstart='';
		$('#divmovetemp').remove();	
	}
	document.onmousemove=_Move;
	document.onmouseup=_Up;
}
js.tanbodyindex = 1210;
js.tanbody=function(act,title,w,h,can1){
	this.tanbodyindex++;
	var can	= js.applyIf(can1,{html:'',msg:'',showfun:function(){},bodystyle:'',guanact:'',titlecls:'',btn:[]});
	var l=(winWb()-w-50)*0.5,t=(winHb()-h-50)*0.5;
	var s	= '';
	var mid	= ''+act+'_main';
	$('#'+mid+'').remove();
	var posta= 'fixed';
	if(js.path == 'admin')posta='absolute';
	s+='<div id="'+mid+'" tanbody="rock" style="position:'+posta+';background-color:#ffffff;left:'+l+'px;width:'+w+'px;top:'+t+'px;z-index:'+this.tanbodyindex+';box-shadow:0px 0px 10px rgba(0,0,0,0.3);">';
	s+='	<div class="'+can.titlecls+'" style="-moz-user-select:none;-webkit-user-select:none;user-select:none;background:#336699">';
	s+='		<table border="0"  width="100%" cellspacing="0" cellpadding="0"><tr>';
	s+='			<td height="34" style="font-size:16px; font-weight:bold;color:white; padding-left:8px" width="100%" onmousedown="js.move(\''+mid+'\')" id="'+act+'_title">'+title+'</td>';
	s+='			<td><div onmouseover="this.style.backgroundColor=\'#C64343\'" onmouseout="this.style.backgroundColor=\'\'" style="padding:0px 8px;height:40px;overflow:hidden;cursor:pointer;" onclick="js.tanclose(\''+act+'\',\''+can.guanact+'\')"><div id="'+act+'_spancancel" style="height:16px;overflow:hidden;width:16px;background:url(/images/wclose.png);margin-top:12px"></div></div></td>';
	s+='		</tr></table>';
	s+='	</div>';
	s+='	<div id="'+act+'_body" style="'+can.bodystyle+'">';
	s+=can.html;
	s+='	</div>';
	s+='	<div id="'+act+'_bbar" style="padding:5px 10px;background:#eeeeee;line-height:30px;" align="right"><span id="msgview_'+act+'">'+can.msg+'</span>&nbsp;';
	for(var i=0; i<can.btn.length; i++){
		var a	= can.btn[i];
		s+='<a class="btn btn-success" id="'+act+'_btn'+i+'" onclick="return false">';
		if(!isempt(a.icons))s+='<i class="icon-'+a.icons+'"></i>&nbsp; ';
		s+=''+a.text+'</a>&nbsp; ';
	}
	s+='		<a class="btn btn-default" id="'+act+'_cancel" onclick="return js.tanclose(\''+act+'\',\''+can.guanact+'\')">取消</a>';
	s+='	</div>';
	s+='</div>';
	js.xpbody(act,can.mode);
	$('body').prepend(s);
	if(can.closed=='none'){
		$('#'+act+'_bbar').remove();
		$('#'+act+'_spancancel').parent().remove();
	}
	if(can.bbar=='none')$('#'+act+'_bbar').remove();
	this.tanoffset(act);
	can.showfun(act);
}
js.tanoffset=function(act){
	var mid=''+act+'_main';
	var lw = get(mid).offsetWidth,lh=get(mid).offsetHeight,l,t;
	l=(winWb()-lw)*0.5;t=(winHb()-lh-20)*0.5;
	if(t<0)t=1;
	$('#'+mid+'').css({'left':''+l+'px','top':''+t+'px'});
}
js.tanclose=function(act, guan){
	if(!isempt(guan)){
		var s= guan.split(',');
		for(var i=0;i<s.length;i++)$('#'+s[i]+'_main').remove();
	}
	$('#'+act+'_main').remove();
	js.xpbody(act,'none');
	return false;
}
js.xpbody=function(act,type){
	if(type=='none'){
		$("div[xpbody='"+act+"']").remove();
		if(!get('xpbg_bodydds'))$('div[tanbody]').remove();
		$('body').css('overflow','auto');
		return;
	}
	if(get('xpbg_bodydds'))return false;
	var H	= (document.body.scrollHeight<winHb())?winHb()-5:document.body.scrollHeight;
	var W	= document.documentElement.scrollWidth+document.body.scrollLeft;
	var bs='<div id="xpbg_bodydds" xpbody="'+act+'" oncontextmenu="return false" style="position:absolute;display:none;width:100%;height:'+(H+20)+'px;filter:Alpha(opacity=30);opacity:0.3;left:0px;top:0px;background-color:#000000;z-index:1200">w </div>';
	$('body').prepend(bs).css('overflow','hidden');
	$('#xpbg_bodydds').fadeIn(300);
}
js.focusval	= '0';
js.number=function(obj){
	val=strreplace(obj.value);
	if(!val){
		obj.value=js.focusval;
		return false;
	}
	if(isNaN(val)){
		js.msg('msg','输入的不是数字');
		obj.value=js.focusval;
		obj.focus();
	}else{
		var o1 = $(obj);
		var min= o1.attr('minvalue');
		if(isempt(min))min= o1.attr('min');
		if(min && parseFloat(val)<parseFloat(min))val=min;
		var max= o1.attr('maxvalue');
		if(isempt(max))max= o1.attr('max');
		if(max && parseFloat(val)>parseFloat(max))val=max;
		obj.value=val;
	}
}
js.setmsg=function(txt,col,ids){
	if(!ids)ids='msgview';
	$('#'+ids+'').html(js.getmsg(txt,col));
}
js.getmsg  = function(txt,col){
	if(!col)col='red';
	var s	= '';
	if(!txt)txt='';
	if(txt.indexOf('...')>0){
		s='<img src="/images/loading.gif" height="16" width="16" align="absmiddle"> ';
		col = '#ff6600';
	}	
	s+='<span style="color:'+col+'">'+txt+'</span>';
	if(!txt)s='';
	return s;
}
js.debug	= function(s){
	if(typeof(console)!='object')return;
	console.log(s);
}
js.msg = function(lx, txt,sj){
	clearTimeout(this.msgshowtime);
	if(typeof(sj)=='undefined')sj=5;
	$('#msgshowdivla').remove();
	if(lx == 'none' || !lx){
		return;
	}
	if(lx == 'wait'){
		txt	= '<img src="/images/loadings.gif" height="14" width="15" align="absmiddle"> '+txt;
		sj	= 60;
	}
	if(lx=='msg')txt='<font color=red>'+txt+'</font>';var t=10;
	var s = '<div onclick="$(this).remove()" id="msgshowdivla" style="position:fixed;top:'+t+'px;z-index:200;" align="center"><div style="padding:8px 20px;background:rgba(0,0,0,0.7);color:white;font-size:16px;">'+txt+'</div></div>';
	$('body').append(s);
	var w=$('#msgshowdivla').width(),l=(winWb()-w)*0.5;
	$('#msgshowdivla').css('left',''+l+'px');
	if(sj>0)this.msgshowtime= setTimeout("$('#msgshowdivla').remove()",sj*1000);	
}
js.getrand=function(){
	var r;
	r = ''+new Date().getTime()+'';
	r+='_'+parseInt(Math.random()*9999)+'';
	return r;
}

js.apiurl= function(url){
	if(url.substr(0,1)!='/')url='/'+url;
	if(url.substr(0,4)!='http')url = ''+apiurl+''+url+'';
	return url;
}


js.reload = function(){
	location.reload();
}

js.ajax = function(url,da,fun,type,efun, tsar){
	if(!da)da={};if(!type)type='get';if(!tsar)tsar='';tsar=tsar.split(',');
	if(typeof(fun)!='function')fun=function(){};
	if(typeof(efun)!='function')efun=function(){};
	js.ajaxbool=true;if(tsar[0])js.msg('wait', tsar[0]);
	var ajaxcan={
		type:type,data:da,url:this.apiurl(url,false),dataType:'json',
		success:function(ret){
			js.ajaxbool=false;
			js.unloading();
			if(ret.success){
				if(tsar[1])js.msg('success', tsar[1]);
				fun(ret);
			}else{
				js.msg('msg', ret.msg);
				efun(ret.msg);
				if(ret.code==401){
					js.msg('none');
					js.alert('登录失效，请重新登录',function(){
						js.setoption(TOKENKEY);
						js.location('index.html');
					});
				}
			}
		},error:function(e){
			js.ajaxbool=false;
			js.unloading();
			js.msg('msg','ERROR:'+e.responseText+'');
			console.error(e);
			efun(e.responseText,e);
		}
	};
	if(TOKEN)ajaxcan.beforeSend=function(request){request.setRequestHeader(TOKENKEY, TOKEN);}
	$.ajax(ajaxcan);
}

js.setoption=function(k,v,qzb){
	if(!qzb)k=QOM+k;
	try{
		if(isempt(v)){
			localStorage.removeItem(k);
		}else{
			localStorage.setItem(k, escape(v));
		}
	}catch(e){
		js.savecookie(k,escape(v));
	}
	return true;
}
js.getoption=function(k,dev, qzb){
	var s = '';
	if(!qzb)k=QOM+k;
	try{s = localStorage.getItem(k);}catch(e){s=js.cookie(k);}
	if(s)s=unescape(s);
	if(isempt(dev))dev='';
	if(isempt(s))s=dev;
	return s;
}
js.location = function(url){
	location.href = url;
}
js.isimg = function(lx){
	var ftype 	= '|png|jpg|bmp|gif|jpeg|';
	var bo		= false;
	if(ftype.indexOf('|'+lx+'|')>-1)bo=true;
	return bo;
}
js.back=function(){
	history.back();
}
js.fileall=',aac,ace,ai,ain,amr,app,arj,asf,asp,aspx,av,avi,bin,bmp,cab,cad,cat,cdr,chm,com,css,cur,dat,db,dll,dmv,doc,docx,dot,dps,dpt,dwg,dxf,emf,eps,et,ett,exe,fla,ftp,gif,hlp,htm,html,icl,ico,img,inf,ini,iso,jpeg,jpg,js,m3u,max,mdb,mde,mht,mid,midi,mov,mp3,mp4,mpeg,mpg,msi,nrg,ocx,ogg,ogm,pdf,php,png,pot,ppt,pptx,psd,pub,qt,ra,ram,rar,rm,rmvb,rtf,swf,tar,tif,tiff,txt,url,vbs,vsd,vss,vst,wav,wave,wm,wma,wmd,wmf,wmv,wps,wpt,wz,xls,xlsx,xlt,xml,zip,';
js.filelxext = function(lx){
	if(js.fileall.indexOf(','+lx+',')<0)lx='wz';
	return lx;
}
js.importjs=function(url,fun){
	var sid = jm.encrypt(url);
	if(!fun)fun=function(){};
	if(get(sid)){fun();return;}
	var scr = document.createElement('script');
	scr.src = url;
	scr.id 	= sid;
	if(isIE){
		scr.onreadystatechange = function(){
			if(this.readyState=='loaded' || this.readyState=='complete'){fun(this);}
		}
	}else{
		scr.onload = function(){fun(this);}
	}
	document.getElementsByTagName('head')[0].appendChild(scr);
	return false;	
}


js.gotourl=function(url){
	this.location(url);
}

js.navigator=function(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.indexOf('micromessenger')>0)this.iswxbro=true;
	if(this.iswxbro && ua.indexOf('wxwork')>0)this.isqywx=true;
	if(ua.indexOf('dingtalk')>0)this.isdingbro=true;
}

js.getcfrom=function(){
	var s = 'mweb';
	if(this.iswxbro)s='wxbro';
	if(this.isqywx)s='wxqybro';
	if(this.isdingbro)s='ding';
	return s;
}

js.isshowheader=function(){
	if(this.iswxbro)return false;
	if(this.isdingbro)return false;
	return true;
}

js.datechange=function(o1,lx){
	if(!lx)lx='date';
	$(o1).rockdatepicker({'view':lx,'initshow':true});
	return false;
}

js._bodyclick = {};
js.downbody=function(o1, e){
	this.allparent = '';
	this.getparenta($(e.target),0);
	var a,s = this.allparent,a1;
	for(a in js._bodyclick){
		a1 = js._bodyclick[a];
		if(s.indexOf(a)<0){
			if(a1.type=='hide'){
				$('#'+a1.objid+'').hide();
			}else{
				$('#'+a1.objid+'').remove();
			}
		}
	}
	return true;
}
js.addbody = function(num, type,objid){
	js._bodyclick[num] = {type:type,objid:objid};
}
js.getparenta=function(o, oi){
	try{
	if(o[0].nodeName.toUpperCase()=='BODY')return;}catch(e){return;}
	var id = o.attr('id');
	if(!isempt(id)){
		this.allparent+=','+id;
	}
	this.getparenta(o.parent(), oi+1);
}


js.alert=function(msg,fun,tit, cof1){
	$('#weui_dialog_alert_div').remove();
	if(msg=='none')return;
	var s='';
	if(!tit)tit='';
	s+='<div id="weui_dialog_alert_div" class="weui_dialog_alert" >';
    s+='<div class="weui_mask"></div>';
    s+='<div class="weui_dialog" style="max-width:400px">';
    s+='    <div class="weui_dialog_hd"><strong class="weui_dialog_title">'+tit+'</strong></div>';
    s+='    <div class="weui_dialog_bd">'+msg+'</div>';
    s+='    <div class="weui_dialog_ft">';
	s+='        <a href="javascript:;" id="confirm_btn" sattr="yes" class="weui_btn_dialog primary">确定</a>';
    if(cof1==1)s+='       <a href="javascript:;" id="confirm_btn1" sattr="no" class="weui_btn_dialog default">取消</a>';
    s+='   </div>';
    s+='</div>';
	s+='</div>';
	$('body').append(s);
	function backl(e){
		var jg	= $(this).attr('sattr');
		if(typeof(fun)=='function')fun(jg,this);
		$('#weui_dialog_alert_div').remove();
		return false;
	}
	$('#confirm_btn1').click(backl);
	$('#confirm_btn').click(backl);
}
js.confirm=function(msg,fun,tit){
	this.alert(msg,fun,tit, 1);
}
js.prompt=function(tit,msg,fun,nr){
	if(!nr)nr='';
	var msg = '<div align="left">'+msg+'</div><div align="left" style="padding:3px; background:white" class="r-border"><input value="'+nr+'" style="border:none;background:none" class="r-input" id="prompttxt" type="text"></div>';
	function func(lx){
		fun(lx,get('prompttxt').value);
	}
	this.alert(msg,func,tit, 1);
}

js.loading=function(txt){
	this.unloading();
	if(txt=='none')return;
	if(!txt)txt='处理中...';
	var s='';var t = (winHb()-130)*0.5;
	s+='<div id="loadingToastsss" class="weui_loading_toast">'+
    '<div class="weui_mask_transparent"></div>'+
    '<div style="top:'+t+'px" class="weui_toast">'+
    '    <div class="weui_loading">'+
    '        <div class="weui_loading_leaf weui_loading_leaf_0"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_1"></div>'+
     '       <div class="weui_loading_leaf weui_loading_leaf_2"></div>'+
      '      <div class="weui_loading_leaf weui_loading_leaf_3"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_4"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_5"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_6"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_7"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_8"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_9"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_10"></div>'+
    '        <div class="weui_loading_leaf weui_loading_leaf_11"></div>'+
    '    </div>'+
    '    <p class="weui_toast_content">'+txt+'</p>'+
    '</div>'+
	'</div>';
	$('body').append(s);
}
js.unloading=function(){
	$('#loadingToastsss').remove();
}
js.msgok=function(txt,fun,ms,lx){
	this.unloading();
	$('#toastssss').remove();
	clearTimeout(this.msgtime);
	if(txt=='none')return;
	if(!ms)ms=3;
	var s='<div id="toastssss" onclick="$(this).remove()">';
	s+='<div class="weui_mask_transparent"></div>';
	s+=	'<div class="weui_toast">';
	if(lx=='err'){
		s+=		'<i class="weui_icon_toastwarn"></i>';
		s+=		'<p class="weui_toast_content" style="color:red">'+txt+'</p>';
	}else{
		s+=		'<i class="weui_icon_toast"></i>';
		s+=		'<p class="weui_toast_content">'+txt+'</p>';
	}
	s+=	'</div>';
	s+='</div>';
	$('body').append(s);
	this.msgtime=setTimeout(function(){
		$('#toastssss').remove();
		if(typeof(fun)=='function')fun();

	}, ms*1000);
}
js.msgerror=function(txt,fun,ms){
	js.msg('none');
	js.msgok(txt,fun,ms,'err');
}

js.actionsheet=function(d){
	$('#actionsheetshow').remove();
	var d=js.apply({onclick:function(){},oncancel:function(){}},d);
	var a=d.data,s='';
	if(!a)return;
	s+='<div onclick="$(this).remove();"  id="actionsheetshow">';
	s+='<div class="weui_mask_transition weui_fade_toggle" style="display:block"></div>';
	s+='<div class="weui_actionsheet weui_actionsheet_toggle" >';
	s+='	<div class="weui_actionsheet_menu">';
	for(var i=0;i<a.length;i++){
		s+='<div oi="'+i+'" style="color:'+a[i].color+'" class="weui_actionsheet_cell">'+a[i].name+'</div>';
	}
	s+='	</div>';
	s+='	<div class="weui_actionsheet_action"><div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div></div>';
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	$('#actionsheetshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#actionsheetshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
}

js.showmenu=function(d){
	$('#menulistshow').remove();
	var d=js.apply({width:200,top:'50%',renderer:function(){},align:'center',onclick:function(){},oncancel:function(){}},d);
	var a=d.data;
	if(!a)return;
	var h1=$(window).height(),h2=document.body.scrollHeight,s1;
	if(h2>h1)h1=h2;
	var col='';
	var s='<div onclick="$(this).remove();" align="center" id="menulistshow" style="background:rgba(0,0,0,0.6);height:'+h1+'px;width:100%;position:absolute;left:0px;top:0px;z-index:198" >';
	s+='<div id="menulistshow_s" style="width:'+d.width+'px;margin-top:'+d.top+';position:fixed;-webkit-overflow-scrolling:touch;" class="menulist r-border-r r-border-l">';
	for(var i=0;i<a.length;i++){
		s+='<div oi="'+i+'" style="text-align:'+d.align+';color:'+a[i].color+'" class="r-border-t">';
		s1=d.renderer(a[i]);
		if(s1){s+=s1}else{s+=''+a[i].name+'';}
		s+='</div>';
	}
	s+='</div>';
	s+='</div>';
	$('body').append(s);
	var mh = $(window).height();
	var l=($(window).width()-d.width)*0.5,o1 = $('#menulistshow_s'),t = (mh-o1.height()-10)*0.5;
	if(t<10){
		t = 10;
		o1.css({height:''+(mh-20)+'px','overflow':'auto'});
	}
	o1.css({'left':''+l+'px','margin-top':''+t+'px'});
	$('#menulistshow div[oi]').click(function(){
		var oi=parseFloat($(this).attr('oi'));
		d.onclick(a[oi],oi);
	});
	$('#menulistshow').click(function(){
		$(this).remove();
		try{d.oncancel();}catch(e){}
	});
};

//通信
js.sendmessage=function(evt, lx, data,bfun){
	var da = '{"atype":"'+lx+'", "data":"'+data+'"}';
	this.setoption(evt, da);
	if(bfun)setTimeout(function(){
		var val=js.getoption(evt);
		if(val){bfun();js.setoption(evt);}
	},250);
}
js.eventmessage=function(lx,fun){
	if(!this.eventmessagea){
		js.eventmessagea={};
		setInterval('js.eventmessagestart()', 300);
	}
	this.setoption(lx);
	this.eventmessagea[lx]=fun;
}
js.eventmessagestart=function(){
	var lx,fun,da,dats;
	for(lx in this.eventmessagea){
		fun = this.eventmessagea[lx];
		if(!fun)continue;
		da  = this.getoption(lx);
		if(da){
			try{
				dats = this.decode(da);
				fun(dats.atype, dats.data);
			}catch(e){
				console.error(e)
			}
			this.setoption(lx);
		}
	}
}

this.ontabsclicks=function(){};
js.inittabs=function(){
	$('.r-tabs div').click(function(){
		js.tabsclicks(this);
	});
}
js.tabsclicks=function(o1){
	var o = $(o1);
	var tid= o.parent().attr('tabid');
	$('.r-tabs[tabid="'+tid+'"] div').removeClass('active');
	$('[tabitem][tabid="'+tid+'"]').hide();
	var ind = o.attr('index');
	o.addClass('active');
	var ho = $('[tabitem='+ind+'][tabid="'+tid+'"]');
	ho.show();
	this.ontabsclicks(ind, tid, o, ho);
}