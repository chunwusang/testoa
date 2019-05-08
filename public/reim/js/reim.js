/**
*	createname：雨中磐石
*	homeurl：http://www.rockoa.com/
*	Copyright (c) 2016 rainrock (xh829.com)
*	Date:2016-01-01
*/
var agentarr={},cnum='',windowfocus=true,jietubool=false;
var reim={
	chatobj:{},
	initci:0,
	init:function(){
		date = js.now('Y-m-d');
		nwjs.init();
		bodyunload=function(){
			nwjs.removetray();
		}
		this.resize();
		
		$(window).resize(this.resize);
		$(window).focus(function(){windowfocus=true;im.windowfocus()});
		$(window).blur(function(){windowfocus=false});
		var fse=js.getoption('reimface');
		if(fse)get('myface').src=fse;
		
		fse=js.getoption('nowlogo');
		if(fse){
			get('myshow_logo').src = fse;
			get('ico').href = fse;
		}
		
		fse=js.getoption('nowcompanyname');
		if(fse){
			$('#myshow_companyname').html(fse);
			document.title=fse;
		}
		nwjs.createtray(document.title, 1);
		cnum = js.request('cnum');
		if(!cnum)cnum = js.getoption('nowcnum');
		strformat.ismobile=0; 
		//禁止后退
		try{
			history.pushState(null, null, document.URL);
			window.addEventListener('popstate', function (){
				history.pushState(null, null, document.URL);
			});
		}catch(e){}
		this.initload();
		$('#centlist').perfectScrollbar();
		$.getScript('/base/upfilejs', function(){
			uploadobj = $.rockupfile({
				onchange:function(d){
					im.sendfileshow(d);
				},
				onprogress:function(f,per,evt){
					im.upprogresss(per);
				},
				onsuccess:function(f){
					im.sendfileok(f,false);
				},
				onerror:function(str){
					js.msg('msg', str);
					im.senderror();
				}
			});
			strformat.upobj = uploadobj;
		});
		$('body').keydown(function(e){
			return reim.bodykeydown(e);
		});
		
		notifyobj=new notifyClass({
			title:'系统提醒',
			sound:'/reim/sound/todo.ogg',
			sounderr:'',
			soundbo:true,
			showbool:false
		});
		
		this.righthistroboj = $.rockmenu({
			data:[],
			itemsclick:function(d){
				reim.rightclick(d);
			}
		});
		$('#reimcog').click(function(){
			reim.clickcog(this);
			return false;
		});
		document.ondragover=function(e){e.preventDefault();};
        document.ondrop=function(e){e.preventDefault();};
		
		//注册全局ajax的错误
		js.ajaxerror=function(msg,code){
			if(code==401){
				js.msg();
				js.msgerror('登录失效，重新登录');
				if(!nwjsgui){
					js.location('/users/login?backurl=reim');
				}else{
					js.location('/reim/login.html');
				}
			}
		}
		if(!nwjsgui){
			$('#closediv').remove();
		}
	},
	resize:function(){
		viewheight = winHb()-10-50; //可操作高度
		$('#mindivshow').css('height',''+(viewheight+50)+'px');
		$('#centlist').css('height',''+viewheight+'px');
		$('#viewzhulist').css('height',''+viewheight+'px');
		var obj = $('div[resizeh]'),o,hei;
		for(var i=0;i<obj.length;i++){
			o = $(obj[i]);
			hei=parseInt(o.attr('resizeh'));
			o.css('height',''+(viewheight-hei)+'px');
		}
	},
	bodykeydown:function(e){
		var code	= e.keyCode;
		if(code==27){
			if(get('xpbg_bodydds')){
				js.tanclose($('#xpbg_bodydds').attr('xpbody'));
			}else{
				this.closenowtabss();
			}
			return false;
		}
	},
	clickcompany:function(){
		//var url = nwjs.getpath()+'/notify.html';
		//nw.Window.open(url, {'frame':false,width:300,height:120});
	},
	winclose:function(){
		nwjs.win.hide();
	},
	winzuida:function(){
		if(!this.zdhbo){
			nwjs.win.maximize();
			this.zdhbo=true;
		}else{
			nwjs.win.unmaximize();
			this.zdhbo=false;
		}
	},
	changetabs:function(ind){
		$('div[id^="changetabs"]').css('color','#cccccc');
		$('div[id^="centshow"]').hide();
		$('#changetabs'+ind+'').css('color','#336699');
		$('#centshow'+ind+'').show();
		if(ind==1)this.showdept();
		if(ind==2){
			$('#maincenter').hide();
			this.showagent(true);
		}else{
			this.hideagent();
			$('#maincenter').show();
		}
	},
	initload:function(){
		this.initbool = true;
		js.ajax(this.getapiurl('index_reimdata'),{initci:this.initci}, function(ret){
			reim.initci++;
			reim.data = ret.data;
			reim.showdata(ret.data);
			if(reim.data.reiminfo)reim.websocketlink(reim.data.reiminfo);
		},'get',function(msg,code){
		});
	},
	websocketlink:function(cans){
		this.reiminfo = cans;
		js.savecookie('clientkey', cans.clientkey);
		websocketobj = new websocketClass({
			adminid:cans.adminid,
			reimfrom:cans.reimfrom,
			wshost:jm.uncrypt(cans.wshost),
			sendname:TOKEN+device,
			onerror:function(o,ws){
				reim.connectbool=false;
				reim.serverstatus(0);
				js.msg('msg','无法连接服务器1<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
				reim.relianshotime(30);
			},
			onmessage:function(str){
				reim.connectbool=true;
				clearTimeout(reim.relianshotime_time);
				var a=js.decode(str);
				reim.receivemesb(a);
			},
			onopen:function(){
				reim.connectbool=true;
				reim.serverstatus(1);
				clearTimeout(reim.relianshotime_time);
				js.msg('none');
				reim.initnotify();
			},
			onclose:function(o,e){
				reim.connectbool=false;
				if(reim.otherlogin)return;
				reim.serverstatus(0);
				js.msg('msg','连接已经断开了<br><span id="lianmiaoshoetime"></span><a href="javascript:;" onclick="reim.connectservers()">[重连]</a>',0);
				reim.relianshotime(30);
			}
		});
	},
	connectservers:function(){
		if(this.connectbool){
			this.serverstatus(1);
			return;
		}
		var bo = this.websocketlink(this.reiminfo);
		if(bo)js.msg('wait','连接中...');
	},
	serverstatus:function(lx){
		var s = '<font color="green">已连接</font>';s='';
		if(lx==0)s='<font color="red">未连接</font>'
		if(lx==2)s='<font color="#ff6600">在别处连接</font>'
		if(lx==3)s='<font color="blue">没服务端</font>';
		$('#reim_statusserver').html(s);
	},
	relianshotime:function(oi){
		clearTimeout(this.relianshotime_time);
		$('#lianmiaoshoetime').html('('+oi+'秒后重连)');
		if(oi<=0){
			this.connectservers();
		}else{
			this.relianshotime_time=setTimeout('reim.relianshotime('+(oi-1)+')',1000);
		}
	},
	showdept:function(id){
		if($('#showdept').html()==''){
			this.reloaduser();
		}else{
		}
	},
	initnotify:function(){
		var lx=notifyobj.getaccess();
		if(lx!='ok'){
			//js.msg('msg','为了可及时收到信息通知 <br>请开启提醒,<span class="zhu cursor" onclick="reim.indexsyscogss()">[开启]</span>',-1);
		}
	},
	indexsyscogs:function(){
		var str = notifyobj.getnotifystr('reim.indexsyscogss()');
		return '桌面通知提醒'+str+'';
	},
	indexsyscogss:function(){
		notifyobj.opennotify(function(){
			js.msg('success', reim.indexsyscogs());
		});
	},
	reloaduser:function(){
		var s = '<div style="margin-top:50px" align="center"><img src="/images/mloading.gif"></div>';
		$('#showdept').html(s);
		js.ajax(this.getapiurl('tongxl'),false,function(ret){
			reim.reloadusershow(ret.data);
		}, 'get', function(){
		});
	},
	reloadusershow:function(da){
		this.tongxldata = da;
		$('#showdept').html('');
		this.showuserlists(0,0, 'showdept');
		this.showgroup();
	},
	
	//别的地方登录
	otherlogins:function(){
		this.otherlogin = true;
		var msg='已在别的地方连接了';
		js.msg('success', msg, -1);
		this.serverstatus(2);
	},
	
	//收到推送消息
	receivemesb:function(d, lob){
		var lx=d.ptype,sendid=d.sendid;
		if(lx=='offoline'){
			this.otherlogins();
			return;
		}
	
		//单位不一样
		if(d.cid && d.cid!=this.companyid){
			this.receiveunit(d);
			return;
		}
		
		if(lx=='user' || lx=='group'){
			if(sendid!=adminid)this.receivechat(d);
		}
		if(lx=='agenh'){
			this.receiveagenh(d);
		}
	},
	showuserlists:function(pid,xu, svie){
		var o = $('#'+svie+'');
		var tx= o.text();
		if(tx){if(pid!=0){o.toggle();}return;}
		var a =this.tongxldata.useaarr,i,len=a.length,d,dn,s='',wfj,zt;
		
		for(i=0;i<len;i++){
			d=a[i];
			if(pid==d.deptid || d.deptids.indexOf(','+pid+',')>-1){
				zt='';
				if(d.status==0)zt='&nbsp;<font style="font-size:12px" color=red>未加入</font>';
				s='<div class="lists" onclick="reim.showuserinfo('+i+')" style="padding-left:'+(xu*20+10)+'px" >';
				s+='<table cellpadding="0" border="0" width="100%"><tr>';
				s+='<td style="padding-right:5px"><div style="height:24px;overflow:hidden"><img src="'+d.face+'" style="height:24px;width:24px"></div></td>';
				s+='<td align="left" width="100%"><div class="name">'+d.name+''+zt+'</div></td>';
				s+='</tr></table>';
				s+='</div>';
				o.append(s);
			}
		}
		
		a = this.tongxldata.deptarr;
		len=a.length;
		for(i=0;i<len;i++){
			d = a[i];
			if(d.pid==pid){
				wfj = 'icon-folder-close-alt';
				s='<div class="lists" style="padding-left:'+(xu*20+10)+'px" onclick="reim.showuserlists('+d.id+','+(xu+1)+',\'showdept_'+d.id+'\')">';
				s+='	<i class="'+wfj+'"></i> '+d.name+'';
				if(d.ntotal>0)s+=' <span style="font-size:12px;color:#888888">('+d.ntotal+')</span>';
				s+='</div>';
				s+='<span id="showdept_'+d.id+'"></span>';
				o.append(s);
				if(pid==0)this.showuserlists(d.id, xu+1, 'showdept_'+d.id+'');
			}
		}
	},
	showgroup:function(){
		var a =this.tongxldata.grouparr,i,len=a.length,d,s='';
		s='<div style="padding:5px;margin-top:5px;color:#aaaaaa;border-bottom:1px #f1f1f1  solid">会话('+len+')</div>';
		for(i=0;i<len;i++){
			d = a[i];
			s+='<div onclick="reim.openchat(\'group\',\''+d.id+'\',\''+d.name+'\',\''+d.face+'\')" class="lists">';
			s+='<table cellpadding="0" border="0" width="100%"><tr>';
			s+='<td style="padding-right:5px"><div style="height:24px;overflow:hidden"><img src="'+d.face+'" style="height:24px;width:24px"></div></td>';
			s+='<td align="left" width="100%"><div class="name">'+d.name+'</div></td>';
			s+='</tr></table>';
			s+='</div>';
		}
		$('#showgroup').html(s)
	},
	getapiurl:function(lx){
		var url = '/api/we/'+lx+'';
		if(cnum!='')url+='/'+cnum+'';
		return url;
	},
	showdata:function(da){
		var ats 	  = da.useainfo; //当前单位用户
		this.userinfo = da.userinfo;
		this.useainfo = ats;
		this.companyid= 0;
		adminid 	  = 0;
		adminname 	  = this.userinfo.name;
		this.showhistory(da.charhist);
		jm.setJmstr(jm.base64decode(this.userinfo.randshow));
		get('myface').src = this.userinfo.face;
		js.setoption('reimface', this.userinfo.face);
		if(ats){
			adminid		 	 = ats.id;
			adminname		 = ats.name;
			this.companyinfo = ats.company;
			this.companyid	 = this.companyinfo.id;
			cnum			 = this.companyinfo.num;
			js.setoption('nowcnum', cnum);
			js.setoption('nowlogo', this.companyinfo.logo);
			js.setoption('nowcompanyname', this.companyinfo.name);
			document.title=this.companyinfo.name;
			nwjs.traytip(this.companyinfo.name+'-'+adminname);
			var shortna = this.companyinfo.shortname;
			get('myshow_logo').src = this.companyinfo.logo;
			get('ico').href = this.companyinfo.logo;
			$('#myshow_companyname').html(this.companyinfo.name);
			this.showagent(false);
		}
		$('#myshow_name').html(this.userinfo.name);
	},
	showhistory:function(a){
		var i,len=a.length;
		$('#historylist').html('');
		$('#historylist_tems').show();
		for(i=0;i<len;i++){
			this.showhistorys(a[i]);
		}
		if(i>0)$('#historylist_tems').hide();
	},
	showhistorys:function(d,pad, lex){
		var s,ty,o=$('#historylist'),d1,st,attr;
		var num = ''+d.type+'_'+d.receid+'';
		$('#history_'+num+'').remove();
		st	= d.stotal;if(!st)st='';
		var ops = d.optdt.substr(11,5);
		if(d.optdt.indexOf(date)!=0)ops=d.optdt.substr(5,5);
		ty	= d.type;
		var cls = lex ? ' active' : '';
		var na  = d.name;
		if(d.title)na = d.title;
		s	= '<div class="lists'+cls+'" rtype="hist" oncontextmenu="reim.historyright(this,event)" tsaid="'+d.receid+'" tsaype="'+d.type+'"  temp="hist" id="history_'+num+'" onclick="reim.openchat(\''+ty+'\',\''+d.receid+'\',\''+d.name+'\',\''+d.face+'\')">';
		s+='<table cellpadding="0" border="0" width="100%"><tr>';
		s+='<td style="padding-right:8px"><div style="height:30px;overflow:hidden"><img src="'+d.face+'"></div></td>';
		s+='<td align="left" width="100%"><div class="name">'+na+'</div><div class="huicont">'+jm.base64decode(d.cont)+'</div></td>';
		s+='<td align="right" nowrap><span id="chat_stotal_'+num+'" class="badge red">'+st+'</span><br><span style="color:#cccccc;font-size:10px">'+ops+'</span></td>';
		s+='</tr></table>';
		s+='</div>';
		if(!pad){o.append(s);}else{o.prepend(s)}
		$('#historylist_tems').hide();
		this.showbadge('chat');
	},
	historyright:function(o1,e){
		var rt = $(o1).attr('rtype');
		if(isempt(rt))return false;
		this.rightdivobj = o1;
		var d=[{name:'打开',lx:0}];
		if(rt.indexOf('hist')>-1){
			d.push({name:'删除此记录',lx:2});
		}
		this.righthistroboj.setData(d);
		this.righthistroboj.showAt(e.clientX-3,e.clientY-3);
		return false;
	},
	rightclick:function(d){
		var o1 = $(this.rightdivobj),lx=d.lx;
		var tsaid = o1.attr('tsaid'),
			tsayp = o1.attr('tsaype');
		if(lx==0){
			this.rightdivobj.onclick();
		}			
		if(lx==2){
			o1.remove();
			var tst=$('#historylist').text();if(tst=='')$('#historylist_tems').show();
			js.ajax(this.getapiurl('reim_delhchat'),{type:tsayp,gid:tsaid},false,'get');
		}	
	},
	showuserinfo:function(oi){
		var d = this.tongxldata.useaarr[oi];
		var num = 'userinfo_'+d.id+'';
		var s = '<div align="center"><div align="left" style="width:300px;margin-top:50px">';
		s+='	<div style="padding-left:70px"><img id="myfacess" onclick="$(this).imgview()" src="'+d.face+'" height="100" width="100" style="border-radius:50%;border:1px #eeeeee solid"></div>';
		var gender = '';
		if(d.gender==1)gender = '男';
		if(d.gender==2)gender = '女';
		var zt = '';
		if(d.status==0)zt=' &nbsp;<font style="font-size:12px" color=red>未加入</font>';
		s+='	<div style="line-height:30px;padding:10px;padding-left:20px;"><font color=#888888>姓名：</font>'+d.name+''+zt+'<br><font color=#888888>部门：</font>'+d.deptallname+'<br><font color=#888888>职位：</font>'+d.position+'<br><font color=#888888>性别：</font>'+gender+'<br><font color=#888888>电话：</font>'+d.tel+'<br><font color=#888888>手机：</font>'+jm.uncrypt(d.mobile)+'<br><font color=#888888>邮箱：</font>'+jm.uncrypt(d.email)+'</div>';
		s+='	<div style="padding-top:10px;padding-left:50px"><input type="button" value="发消息" onclick="reim.openchat(\'user\',\''+d.id+'\',\''+d.name+'\',\''+d.face+'\')" class="btn">&nbsp; &nbsp; <input onclick="reim.closetabs(\''+num+'\')" type="button" value="关闭" class="btn btn-danger"></div>';
		s+='</div></div>';
		this.addtabs(num,s);
	},
	qieunit:function(na,reid){
		js.confirm('确定要却换到单位['+na+']吗？',function(jg){
			if(jg=='yes'){
				js.loading('切换中...');
				js.location('/reim/index.html?cnum='+reid+'');
			}
		},'切换单位');
	},
	openchat:function(type,reid,na,fac){
		if(type=='unit'){
			this.qieunit(na, reid);
			return;
		}
		var num = ''+type+'_'+reid+'';
		
		$('#chat_stotal_'+num+'').html('');
		this.showbadge('chat');
		
		if(type=='agenh'){
			this.openagenh(reid);
			return;
		}
		
		var s = '<div style=" background:#f5f9ff">';
		s+='<div id="viewtitle_'+num+'" style="height:50px;overflow:hidden;border-bottom:#dddddd solid 1px;">';
		s+='</div>';
		var hei = 206;
		s+='<div resizeh="'+hei+'" id="viewcontent_'+num+'" style="height:'+(viewheight-hei)+'px;overflow:hidden;position:relative;"><div style="margin-top:50px" align="center"><img src="/images/mloading.gif"></div></div>';
		
		s+='<div class="toolsliao" id="toolsliao_'+num+'">';
		s+='	<span title="表情" tools="emts" class="cursor"><i class="icon-heart"></i></span>';
		s+='	<span title="文件/图片" tools="file" class="cursor"><i class="icon-folder-close"></i></span>';
		if(nwjsgui){
			s+='	<span title="粘贴图片" tools="paste" class="cursor"><i class="icon-paste"></i></span>';
			s+='	<span title="截屏" tools="crop" class="cursor"><i class="icon-cut"></i></span>';
		}	
		s+='</div>';
		
		s+='<div style="height:80px;overflow:hidden;"><div style="height:70px;margin:5px"><textarea onpaste="im.readclip(\''+num+'\',event)"  class="content" style="background:none;"  id="input_content_'+num+'"></textarea></div></div>';
		
		s+='<div style="height:40px;overflow:hidden;"><div align="right" style="padding:9px"><input id="chatclosebtn_'+num+'" class="webbtn" style="background:none;color:#aaaaaa" type="button" value="关闭(C)">&nbsp;<input class="webbtn" style="background:none;color:#336699" id="chatsendbtn_'+num+'" type="button" value="发送(S)"></div></div>';
		
		s+='</div>';
		var bo = this.addtabs(num,s);
		get('input_content_'+num+'').focus();
		if(!bo){
			this.chatobj[num]=new chatcreate({
				'type' : type,
				'gid'  : reid,
				'num'  : num,
				'name' : na,
				'face' : fac
			});
		}
		this.chatobj[num].onshow();
	},
	biaoyd:function(type,gid){
		js.ajax(this.getapiurl('reim_biaoyd'),{type:type,gid:gid},false,'get');
	},
	receiveunit:function(d){
		var msg = jm.base64decode(d.cont);
		notifyobj.showpopup(msg,{icon:d.companylogo,title:d.companyname,rand:d.companynum,click:function(b){
			reim.qieunit(b.title,b.rand);
		}});
	},
	receiveagenh:function(d){
		var gid = d.gid;
		var num = d.ptype+'_'+gid,stotal=0,msg;
		var so = $('#chat_stotal_'+num+'').html();
		if(!so)so=0;
		stotal = parseInt(so)+1;
		
		this.showhistorys({
			'cont' : d.cont,
			'name' : d.name,
			'title' : d.title,
			'face' : d.face,
			'optdt' : d.optdt,
			'type'	: d.ptype,
			'receid' : gid,
			'stotal' : stotal
		}, true);
		msg = jm.base64decode(d.cont);
		notifyobj.showpopup(msg,{icon:d.face,url:d.url,gid:gid,title:d.title,rand:num,click:function(b){
			reim.openagenh(b.gid, b.url);
			return true;  //不激活主窗口
		}});
	},
	receivechat:function(d){
		var gid = d.gid,lx = d.ptype,stotal=0,num,msg;
		if(lx=='user'){
			gid = d.sendid;
		}
		num = d.ptype+'_'+gid;
		var showtx = true;
		if(this.isopentabs(num)){
			this.chatobj[num].receivedata(d);
			if(this.nowtabs!=num){
				this.chatobj[num].newbool=true;
			}
		}
		if(windowfocus && this.nowtabs==num)showtx=false;
		//未读数
		if(this.nowtabs!=num){
			var so = $('#chat_stotal_'+num+'').html();
			if(!so)so=0;
			stotal = parseInt(so)+1;
		}
		this.showhistorys({
			'cont' : d.cont,
			'name' : d.name,
			'face' : d.face,
			'optdt' : d.optdt,
			'type'	: d.ptype,
			'receid' : gid,
			'stotal' : stotal
		}, true, this.nowtabs==num);
		var nr = jm.base64decode(d.cont);
		if(showtx || nr.indexOf('@'+adminname+'')>-1){
			var title = '会话消息';
			msg  = '人员['+d.sendname+']，发来一条信息';
			if(lx == 'group'){
				msg += '，来自['+d.name+']';
			}
			notifyobj.showpopup(msg,{icon:d.face,type:lx,gid:gid,name:d.name,title:title,rand:num,click:function(b){
				reim.openchat(b.type, b.gid,b.name,b.icon);
			}});
		}
	},
	addtabs:function(num, s){
		var ids = 'tabs_'+num+'',bo;
		if(!get(ids)){
			var s = '<div tabs="'+num+'" id="'+ids+'">'+s+'</div>';
			$('#viewzhulist').append(s);
			bo = false;
		}else{
			bo = true;
		}
		this.showtabs(num);
		return bo;
	},
	closetabs:function(num){
		var ids = 'tabs_'+num+'';
		$('#'+ids+'').remove();
		var ood = $('#viewzhulist div[tabs]:last');
		var snu = ood.attr('tabs');
		this.showtabs(snu);
	},
	closenowtabs:function(){
		if(this.nowtabs)this.closetabs(this.nowtabs);
	},
	closenowtabss:function(){
		var nun = this.nowtabs;
		if(!nun)return;
		if(nun.indexOf('user_')==0 || nun.indexOf('group_')==0 || nun.indexOf('userinfo_')==0)this.closenowtabs();
	},
	isopentabs:function(num){
		return get('tabs_'+num+'');
	},
	showtabs:function(num){
		$('div[tabs]').hide();
		var ids = 'tabs_'+num+'';
		$('#'+ids+'').show();
		$('div[temp]').removeClass('active');
		$('#history_'+num+'').addClass('active');
		this.nowtabs = num;
	},
	showagent:function(sbo){
		var agedt = this.data.agenharr,s='',ty,a,len,d,d1,sno,so=0,sodd=1;
		s+='<div id="agenhview" resizeh="0" style="height:'+viewheight+'px;overflow:hidden;position:relative; background:#fcfdff" align="center"><div style="width:80%;padding:20px" align="left">';
		agenharr={};
		for(ty in agedt){
			a 	= agedt[ty];
			len	= a.length;
			s+='<div style="color:#aaaaaa;padding-left:20px;margin-bottom:10px;padding:5px;border-bottom:'+sodd+'px solid #eeeeee">&nbsp;&nbsp;'+ty+'('+len+')</div>';
			s+='<div class="agenhclsdiv">';
			for(i=0;i<len;i++){
				d1 = a[i];
				if(!agenharr[d1.id])agenharr[d1.id]=d1;
				d   = agenharr[d1.id];
				sno = d.stotal;
				so += sno;
				if(sno==0)sno='';
				s+='<div onclick="reim.openagenh(\''+d.id+'\')" class="agenhcls"><div style="padding-top:5px"><img src="'+d.face+'"></div><div>'+d.name+'</div>';
				s+='<span id="agenh_stotal_'+d.id+'" class="badge">'+sno+'</span>';
				s+='</div>';
			}
			s+='</div>';
			sodd=1;
		}
		s+='</div>';
		if(!sbo){
			if(so==0)so='';
			$('#agenh_stotal').html(so);
			return;
		}
		var bo = this.addtabs('agenh',s);
		if(!bo)$('#agenhview').perfectScrollbar();
		this.showbadge('agenh');
	},
	hideagent:function(){
		if(get('tabs_agenh'))
			this.closetabs('agenh');
	},
	openagenh:function(id, url){
		var d = agenharr[id];
		if(!d){
			js.msg('msg','应用不存在，请刷新');
			return;
		}
		d.stotal=0;
		var num = 'agenh_'+d.id+'';
		$('#agenh_stotal_'+d.id+'').html('');
	
		this.showagent(false); 
		$('#chat_stotal_'+num+'').html('');

		this.showbadge('chat');
		this.biaoyd('agenh',d.id);
		if(!url)url = d.url;
		js.open(url,1000,580);
	},
	showbadge:function(lx){
		var obj = $('span[id^="'+lx+'_stotal_"]'),so=0,s1,o,i;
		for(i=0;i<obj.length;i++){
			o = $(obj[i]);
			s1= o.html();
			if(!s1)s1='0';
			so+=parseInt(s1);
		}
		if(so==0)so='';
		$('#'+lx+'_stotal').html(so);
		var zoi = 0;
		so = $('#agenh_stotal').html();
		if(!so)so = 0;
		zoi+=parseInt(so);
		so = $('#chat_stotal').html();
		if(!so)so = 0;
		zoi+=parseInt(so);
		nwjs.changeicon(zoi);
	},
	clickcog:function(o1){
		if(!this.cogmenu)this.cogmenu =$.rockmenu({
			data:[],
			width:120,
			itemsclick:function(d){
				reim.clickcogclick(d);
			}
		});//{'name':'设置',lx:'cog'},
		var d = [{'name':'切换单位',lx:'qh'},{'name':'退出',lx:'exit'}];
		this.cogmenu.setData(d);
		var off = $(o1).offset();
		this.cogmenu.showAt(40,off.top-70);
	},
	clickcogclick:function(d){
		var lx=d.lx;
		if(lx=='qh'){
			js.winiframe(d.name,'/we/unit.html?gfrom=reim', 400,320);
		}
		if(lx=='exit'){
			this.exitlogin();
		}
	},
	qiehuanunit:function(num){
		js.winiframeclose();
		js.loading('切换中...');
		js.location('/reim/index.html?cnum='+num+'');
	},
	exitlogin:function(bo){
		if(!bo){
			js.confirm('确定要退出系统吗？',function(jg){
				if(jg=='yes')reim.exitlogin(true);
			});
			return;
		}
		if(nwjsgui){
			js.loading('退出中...');
			js.ajax('/api/we/base_loginout',false, function(){
				js.location('/reim/login.html');
			},'get', function(){
				js.msg('none');
				js.location('/reim/login.html');
			});
		}else{
			window.close();
		}
	}
};

function chatcreate(cans){
	for(var i in cans)this[i]=cans[i];
	strformat.emotspath='/';
	var me = this;
	this._init = function(){
		this.minid 	  = 999999999;
		this.showobj  = $('#viewcontent_'+this.num+'');
		this.inputobj = $('#input_content_'+this.num+'');
		this.sendbtn  = $('#chatsendbtn_'+this.num+'');
		this.loadci   = 0;
		this.objstr	  = 'reim.chatobj[\''+this.num+'\']';
		this.sendbtn.click(function(){
			me.sendcont();
		});
		$('#chatclosebtn_'+this.num+'').click(function(){
			me.closechat();
		});
		this.inputobj.keydown(function(e){
			return me.onkeydown(e);
		});
		$('#toolsliao_'+this.num+'').find('span').click(function(e){
			me.clicktools(this);
			return false;
		});
		this.showtitle();
		this.loaddata();
		get('tabs_'+this.num+'').addEventListener('drop', function(e) {
			var files = e.dataTransfer;
			me.filedrop(files);
		}, false);
	};
	this.showtitle=function(){
		var o = $('#viewtitle_'+this.num+''),s='';
		var od = this.receinfo;
		if(!od)od={};
		s+='<table width="100%"><tr>';
		s+='<td width="30" align="center"><div style="padding:0px 10px;padding-right:8px;height:30px;overflow:hidden"><img style="border-radius:0px" src="'+this.face+'" height="30" width="30"></div></td>';
		s+='<td height="50" width="100%">';
		s+='	<div><b style="font-size:16px;">'+this.name+'</b>';
		if(this.type=='group' && this.usershu)s+='('+this.usershu+')';
		if(od.position)s+=' <span style="font-size:12px;color:#aaaaaa">('+od.position+')</span>';
		s+='	</div>';
		if(od.deptallname)s+='<div style="font-size:12px;color:#aaaaaa">'+od.deptallname+'</div>';
		s+='</td>';
		if(this.type=='group'){
			s+='<td width="30" title="邀请" class="chattitbtn" nowrap><i class="icon-plus"></i></td>';
			s+='<td width="30" title="会话信息" class="chattitbtn" nowrap><i class="icon-group"></i></td>';
		}
		s+='</tr></table>';
		o.html(s);
	};
	this.loaddata=function(o1, iref){
		if(this.boolload)return;
		var iref = (!iref)?false:true;
		var minid= 0;
		if(iref)minid=this.minid;
		if(o1)$(o1).html('<img src="../images/loadings.gif" height="14" width="15" align="absmiddle"> 加载中...');
		this.boolload 	= true;
		this.isshangla 	= false;
		js.ajax(reim.getapiurl('reim_getrecord'),{type:this.type,gid:this.gid,minid:minid,loadci:this.loadci},function(ret){
			if(o1)$(o1).html('');
			var da = ret.data;
			if(me.loadci==0){
				me.showobj.html('');
				me.sendinfo = da.sendinfo;
				me.receinfo	= da.receinfo;
				me.usershu	= da.usershu;
				me.showtitle();
				me.showobj.perfectScrollbar();
			}
			me.loadci++;
			me.boolload = false;
			me.loaddatashow(da, iref);
		},'get');
	};
	this.loaddatashow=function(ret,isbf, isls){
		var a 		= ret.rows;
		this.lastdt = ret.nowdt;
		var i,len 	= a.length,cont,lex,nas,fase,nr,d,na=[],rnd,sid;
		$('#loadmored_'+this.num+'').remove();
		if(isbf){
			if(len>0)this.showobj.prepend('<div class="showblanks">---------↑以上是新加载---------</div>');
			na = a;
		}else{
			for(i= len-1; i>=0; i--)na.push(a[i]);
		}
		for(i= 0; i<len; i++){
			d   = na[i];
			sid = d.id;
			lex = 'right';
			nas = '我';
			fase= this.sendinfo.face;
			if(d.sendid!=this.sendinfo.id){
				lex='left';
				nas= d.sendname;
				fase= d.face;
			}
			nr  = this.contshozt(d.filers);
			if(nr=='')nr= jm.base64decode(d.cont);
			rnd = 'mess_'+sid+'';
			if(get('qipaocont_'+rnd+''))continue;
			cont= strformat.showqp(lex,nas,d.optdt,nr ,'', fase, rnd);
			if(!isbf){
				this.addcont(cont, isbf);
			}else{
				this.showobj.prepend(cont);
			}
			if(sid<this.minid)this.minid=sid;
		}
		if(len>0 && !isls){
			var s = '<div class="showblanks" >';
			if(ret.wdtotal==0){
				s+='---------↑以上是历史记录---------';
				if(len>=5){
					this.showobj.prepend('<div id="loadmored_'+this.num+'" class="showblanks" ><a href="javascript:;" onclick="'+this.objstr+'.loadmoreda(this)"><i class="icon-time"></i> 查看更多消息</a></div>');
					this.isshangla = true;
				}
			}else{
				s+='---↑以上是历史,还有未读信息'+ret.wdtotal+'条,<a href="javascript:;" onclick="'+this.objstr+'.loaddata(this)">点击加载</a>---';
			}
			s+='</div>';
			if(!isbf)this.addcont(s);
			if(isbf)this._addclickf();
		}
	};
	
	this.contshozt=function(d){
		if(!d)return '';
		d.imgpath = showbackurl(d.thumbpath);
		return strformat.contshozt(d,'/');
	};
	
	this.addcont=function(cont, isbf){
		var o	= this.showobj;
		if(cont){if(isbf){o.prepend(cont);}else{o.append(cont);}}
		clearTimeout(this.scrolltime);
		this.scrolltime	= setTimeout(function(){
			me.scrollboot();
			me._addclickf();
		}, 50);
	};
	//滚动条到最下面
	this.scrollboot=function(){
		this.showobj.animate({scrollTop:get('viewcontent_'+this.num+'').scrollHeight},100);
	};
	this._addclickf=function(){
		var o = this.showobj.find('img[fid]');
		o.unbind('click');
		o.click(function(){
			me.clickimg(this);
		});
	};
	
	this.clickimg=function(o1){
		var o=$(o1);
		var fid=o.attr('fid');
		var src = o1.src.replace('_s.','.');
		$.imgview({url:src,ismobile:false});
	};
	
	this.loadmoreda=function(o1){
		this.loaddata(o1, true);
	};
	
	this.sendcont=function(ssnr){
		if(this.sendbool)return;
		js.msg('none');
		var o	= this.inputobj;
		var nr	= strformat.sendinstr(o.val());
		nr		= nr.replace(/</gi,'&lt;').replace(/>/gi,'&gt;').replace(/\n/gi,'<br>');
		if(ssnr)nr=ssnr;
		if(isempt(nr))return false;
		var conss = jm.base64encode(nr);
		if(conss.length>3998){
			js.msg('msg','发送内容太多了');
			return;
		}
		var nuid= js.now('time'),optdt = js.serverdt();
		var cont= strformat.showqp('right','我',optdt, nr, nuid, this.sendinfo.face);
		this.addcont(cont);
		o.val('').focus();
		this.sendconts(conss, nuid, optdt, 0);
		return false;
	};
	//收到推送消息来了
	this.receivedata=function(d){
		var minid=this.minid;
		js.ajax(reim.getapiurl('reim_getrecord'),{type:this.type,gid:this.gid,minid:0,lastdt:this.lastdt,loadci:this.loadci},function(ret){
			me.loaddatashow(ret.data, false, true);
		},'get');
	};
	
	this.onshow=function(){
		if(this.newbool){
			this.scrollboot();
		}
		this.newbool = false;
	};
	
	this.onkeydown=function(e){
		var code = e.keyCode;
		if(code==13 && !e.ctrlKey){
			this.sendcont();
			return false;
		}
		if(e.altKey && code==83){
			this.sendcont();
			return false;
		}
		if(e.altKey && code==67){
			this.closechat();
			return false;
		}
		if(e.ctrlKey && code==13){
			this.addinput('\n');
			return false;
		}
		return true;
	};
	this.sendconts=function(conss, nuid, optdt, fid){
		this.sendbool = true;
		var d 	 = {cont:conss,gid:this.gid,type:this.type,nuid:nuid,optdt:optdt,fileid:fid};
		js.ajax(reim.getapiurl('reim_sendinfor'),d,function(ret){
			me.sendsuccess(ret.data,nuid);
		},'post',function(){
			me.senderror(nuid);
		});
		//显示到会话里
		reim.showhistorys({
			'cont' : d.cont,
			'name' : this.receinfo.name,
			'face' : this.receinfo.face,
			'optdt' : d.optdt,
			'type'	: this.type,
			'receid' : this.gid,
			'stotal' : 0
		}, true, true);
	};
	this.senderror=function(nuid){
		get(nuid).src='/images/error.png';
		get(nuid).title='发送失败';
		this.sendbool=false;
	};
	this.sendsuccess=function(d,nuid){
		this.sendbool = false;
		if(!d.id){
			this.senderror(nuid);
			return;
		}
		$('#'+d.nuid+'').remove();
		var bo = false;
		d.messid=d.id;
		d.face  = this.sendinfo.face;
	};
	this.addinput=function(s){
		var val = this.inputobj.val()+s;
		this.inputobj.val(val).focus();
	};
	
	this.closechat=function(){
		if(this.sendbool)return;
		reim.chatobj[this.num]=false;
		reim.closetabs(this.num);
	};
	this.clicktools=function(o1){
		var o    = $(o1);
		var lx = o.attr('tools');
		if(lx=='emts')this.getemts(o);
		if(lx=='file')this.sendfile(o);
		if(lx=='paste')this.pasteimg();
		if(lx=='crop')this.cropScreen();
	};
	this.getemts=function(o){
		if(!get('aemtsdiv')){
			var s = '<div id="aemtsdiv" style="width:400px;height:200px;overflow-y:auto;border:1px #cccccc solid;background:white;box-shadow:0px 0px 5px rgba(0,0,0,0.3);left:3px;top:5px;position:absolute;display:none;z-index:6">';
			s+='<div style="padding:5px">';
			s+=this.getemtsbq('qq',0, 104, 11, 24);
			s+='</div>';
			s+='</div>';
			$('body').append(s);
			js.addbody('emts','hide','aemtsdiv');
		}
		var o1  = $('#aemtsdiv');
		o1.toggle();
		var off = o.offset();
		o1.css({'top':''+(off.top-205)+'px','left':''+(off.left)+'px'});
	};
	
	this.getemtsbq=function(wj, oi1,oi2, fzd, dx){
		var i,oi=0,j1 = js.float(100/fzd);
		var s = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
		for(i=oi1; i<=oi2; i++){
			oi++;
			s+='<td width="'+j1+'%" title="'+strformat.emotsarr[i+1]+'" align="center"><img onclick="im.backemts(\''+strformat.emotsarr[i+1]+'\')" src="/images/im/emots/'+wj+'/'+i+'.gif" width="'+dx+'" height="'+dx+'"></td>';
			if(oi % fzd==0)s+='</tr><tr>';
		}
		s+='</tr></table>';
		return s;
	};
	this.sendfile=function(){
		uploadobj.changefile({nownum:this.num});
	};
	this.sendfileshow=function(f){
		f.face 	= this.sendinfo.face;
		var fa 	= strformat.showupfile(f);
		var cont= fa.cont;
		this.upfilearr = fa;
		this.addcont(cont);
	};
	this.sendfileok=function(f,sbu){
		if(!sbu){
			js.ajax('/api/we/file_save/'+cnum+'',f, function(bret){
				var bda = bret.data;
				bda.imgpath = baseurl+'/'+bda.thumbpath;
				me.sendfileok(bda,true);
			},'post',function(msg){
				js.msg('msg', str);
				me.senderror(me.upfilearr.nuid);
			});
			return;
		}
		var tm= this.upfilearr,conss='';
		strformat.upsuccess(f);
		if(js.isimg(f.fileext)){
			conss = '[图片 '+f.filesizecn+']';
		}else{
			conss = '['+f.filename+' '+f.filesizecn+']'
		}
		this.sendconts(jm.base64encode(conss), tm.nuid, tm.optdt, f.id);
	};
	this.senderrornss=function(){
		this.senderror(this.upfilearr.nuid);
	};
	
	this.readclip=function(evt){
		var clipboardData = evt.clipboardData;
		if(!clipboardData)return;
		for(var i=0; i<clipboardData.items.length; i++){  
			var item = clipboardData.items[i];  
			if(item.kind=='file'&&item.type.match(/^image\//i)){  
				var blob = item.getAsFile(),reader = new FileReader();  
				reader.onload=function(){  
					var cont=this.result;
					me.readclipshow(cont);
				}  
				reader.readAsDataURL(blob);
			}  
		} 
	};
	
	this.readclipshow=function(snr){
		var fa 	= strformat.showupfile({face:this.sendinfo.face}, snr);
		var cont= fa.cont;
		this._sssnuid  = fa.nuid;
		this._sssoptdt = fa.optdt;
		this.upfilearr = fa;
		this.addcont(cont);	
	};
	this.sendbase64 = function(strnr){
		uploadobj.nownum = this.num;
		uploadobj.sendbase64(strnr);
	};
	this.clipobj = function(){
		if(!this.clipobj1)this.clipobj1 = nw.Clipboard.get();
		return this.clipobj1;
	};
	this.pasteimg=function(){
		var snr  = this.clipobj().get('png');
		//console.log(this.clipobj().readAvailableTypes());
		if(!snr){
			//js.msgerror('剪切板上没有图片');
			return;
		}
		this.readclipshow(snr);
	};
	this.cropScreen=function(){
		this.clipobj().clear();
		jietubool = true;
		im.cropScreen();
	};
	this.filedrop=function(o1){
		uploadobj.nownum = this.num;
		uploadobj.change(o1, 0);
	};
	this.rightqipao=function(o1,e,rnd){
		if(!this.rightqipaoobj)this.rightqipaoobj=$.rockmenu({
			data:[],
			width:130,
			itemsclick:function(d){
				me.rightqipaoclick(d);
			}
		});
		this.randmess = rnd;
		var d=[{name:'复制',lx:0},{name:'删除',lx:1}];
		if(this.type=='group')d.push({name:'@TA',lx:3});
		this.rightqipaoobj.setData(d);
		this.rightqipaoobj.showAt(e.clientX,e.clientY);
	};
	this.rightqipaoclick=function(d){
		var lx=d.lx;
		var ids=this.randmess.replace('mess_','');
		if(lx==0){
			var cont = $('#qipaocont_'+this.randmess+'').text();
			if(cont)this.addinput(cont);
		}
		if(lx==1){
			$('#ltcont_'+this.randmess+'').remove();
			if(!isNaN(ids)){
				js.ajax(reim.getapiurl('reim_delrecord'),{type:this.type,gid:this.gid,ids:ids},false,'post');
			}
		}
		if(lx==3){
			var cont = $('#ltname_'+this.randmess+'').text();
			if(cont)this.addinput('@'+cont+' ');
		}
	};
	this._init();
}

//相关回调
var im = {
	clickqipao:function(o1,e){
		
	},
	rightqipao:function(o1,e,rnd){
		reim.chatobj[reim.nowtabs].rightqipao(o1,e,rnd);
	},
	backemts:function(s){
		reim.chatobj[reim.nowtabs].addinput(s);
		$('#aemtsdiv').hide();
	},
	sendfileshow:function(f){
		var num = uploadobj.nownum;
		reim.chatobj[num].sendfileshow(f);
	},
	upprogresss:function(per){
		var num = uploadobj.nownum;
		strformat.upprogresss(per);
	},
	sendfileok:function(f,bo){
		var num = uploadobj.nownum;
		reim.chatobj[num].sendfileok(f,bo);
	},
	senderror:function(){
		var num = uploadobj.nownum;
		reim.chatobj[num].senderrornss();
	},
	readclip:function(num,e){
		reim.chatobj[num].readclip(e);
	},
	upbase64:function(nuid){
		var o = get('jietuimg_'+nuid+'');
		reim.chatobj[reim.nowtabs].sendbase64(o.src);
	},
	cropScreen:function(){
		if(nwjsgui){
			var oatg = nwjs.getpath();
			nw.Shell.openItem(''+oatg+'/images/reimcaptScreen.exe');
		}
	},
	windowfocus:function(){
		if(jietubool){
			reim.chatobj[reim.nowtabs].pasteimg();
		}
		jietubool = false;
	}
}

//下载文件预览的,glx0预览,1下载
strformat.onopenfile=function(da,glx){
	var url = da.upurl;
	if(glx==0 && da.isimg=='1'){
		strformat.imgview(url);
	}else{
		if(glx==1){
			js.location(url);
		}else{
			js.open(url, 1000,600);
		}
	}
	return true;
}