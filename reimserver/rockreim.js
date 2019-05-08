/**
*	nodejs 开发队列服务
*	开发者：雨中磐石(rainrock)
*	网址：http://www.rockoa.com/
*	时间：2018-07-17
*	运行：node rockreim.js
*/

var config= require('./config.js'),
	debug_time = 0,
	Minutes= 0; //当前分钟

var DEUBG	= config.getDebug();	

function debug(str){
	if(DEUBG){
		debug_time++;
		console.log(''+debug_time+'.['+getTimes()+']:'+str);
	}
}

//http访问URL
function httpgetClass(url, id, fun, cnas){
	var me 		= this;
	this.url 	= url.toString();
	this.id 	= id;
	if(!this.url)return;
	if(!fun)fun = function(){};
	this.fun 	= fun;
	this.httpget=function(){
		var qzs		= this.url.substr(0,5);
		var http 	= (qzs=='https') ? require('https') :  require('http');
		debug('runurl:'+this.url+'');
		http.get(this.url, (res) => {
			res.setEncoding('utf8');
			var statusCode 	= res.statusCode, headers = res.headers;
			var rawData 	= '';
			res.on('data', (chunk) => {
				rawData += chunk;
			});
			res.on('end', () => {
				if(me.id){
					reim.runresult(me.id, rawData);
				}
				debug(rawData);
				me.fun(rawData, me.id, cnas);
			});
			
		}).on('error', (e) => {
			debug('errhttp.get'+me.url+'');
		});
	}
	this.httpget();
}

/**
*	运行cmd
*/
function execcmd(cmdStr){
	var processobj = require('child_process');
	processobj.exec(cmdStr, function(err,stdout,stderr){
		if(err) {
			debug('error:'+stderr);
		} else {
			debug(stdout);
		}
	});
}

function getTime(){
	var dt = new Date(),H,i,s;
	H = dt.getHours();
	i = dt.getMinutes();
	s = dt.getSeconds();
	Minutes = i;
	return parseInt(dt.getTime()*0.001);
}

function getTimes(){
	var dt = new Date(),H,i,s;
	H = dt.getHours();
	i = dt.getMinutes();
	s = dt.getSeconds();
	if(H<10)H='0'+H+'';
	if(i<10)i='0'+i+'';
	if(s<10)s='0'+s+'';
	return ''+H+':'+i+':'+s+'';
}


function rockReimServer(){
	var me = this;
	
	//需要运行的队列服务
	this.queuelist	= {};
	
	this.run = function(){
		this.config 	= config.getConfig();
		this.getqueuelist();
		this.udpserver();
		this.startreimserver();
		this.lasttime = getTime();
		this.lastMinutes = Minutes;
		setInterval(function(){me.startrunurl();},1000);
	};
	
	//开启即时
	this.startrunurl = function(){
		var time = getTime();
		this.queuerun(time);
		if(time!=this.lasttime+1)this.queuerun(this.lasttime+1); //防止跳过秒
		this.lastMinutes = Minutes;
		this.lasttime = time;
	};
	
	this.queuerun	= function(time){
		var key = 'a'+time+'';
		var rar = this.queuelist[key],tag,d;
		if(rar){
			for(tag in rar){
				d = rar[tag];
				if(d.type=='url')new httpgetClass(d.url, d.id); //运行URL
				if(d.type=='cmd')execcmd(d.url);
			}
			this.queuelist[key] = false;//注销
		}
	};
	
	this.writeLog=function(lx,time, cont){
		return;
		if(!this.fs)this.fs = require('fs');
		this.fs.writeFile(''+lx+'_'+time+'.log', cont,function(err){});
	};
	
	this.urlencode=function(url){
		var str = url.toString().toLowerCase();
		str = str.replace('://','[HTTP]');
		str = str.replace('?','[WEN]');
		str = str.replace(/\&/gi,'[AND]');
		str = str.replace(/\//gi,'[XIE]');
		return str;
	};
	
	//获取未执行的队列
	this.getqueuelist=function(){
		
	};
	this.getqueuelistshow=function(bstr){
		var data,rows,i,len;
		data = JSON.parse(bstr);
		
		rows = data.queue;
		len  = rows.length;
		debug('queuelist:('+len+')');
		for(i=0;i<len;i++){
			me.addqueue(rows[i]);
		}
	};
	
	
	//添加到队列中
	this.addqueue	= function(d){
		var key = 'a'+d.runtime+'';
		var tags= 'tags_'+d.id+'';
		if(!this.queuelist[key])this.queuelist[key] = {};
		this.queuelist[key][tags] = d;
	};
	
	//运行结果更新
	this.runresult = function(id, msg){
		
	};
	
	
	//---udp- 服务器，用来推送---
	this.udpserver	= function(){
		var dgram 	= require("dgram");
		var server  = dgram.createSocket("udp4");
		server.on("open", function (err) {
			console.log('open');
		});
		server.on("error", function (err) {
			debug("server error:\n" + err.stack);
			server.close();
		});

		server.on("message", function (msg, rinfo){
			var msg	= msg.toString();
			debug(msg);
			try{
				var data = JSON.parse(msg);
				me.udpmessage(data);
			}catch(e){}
		});

		server.on("listening", function () {
			var address = server.address();
			debug("rockqueue udp listening " + address.address + ":" + address.port);
		});
		
		server.bind(this.config.port, this.config.ip);
	}
	
	/**
	*	接收到数据处理[{type:"cmd",url:''}]
	*/
	this.udpmessage=function(ret){
		var type,i,len=ret.length,d,cont;
		for(i=0;i<len;i++){
			d 	 = ret[i];
			type = d.type;
			var runtime = d.runtime,ntime = getTime();
			if(!runtime || runtime<=ntime){
				if(type=='url'){
					new httpgetClass(d.url, d.id); //运行URL
				}
				if(type=='cmd'){
					cont = d.url;
					if(cont.indexOf('artisan')>0 && cont.indexOf('php')>-1 )execcmd(d.url);
				}
				if(type=='reim'){
					this.wsreimpush(d);
				}
			}else{
				this.addqueue(d); //添加到队列中
			}
		}
	};
	
	
	
	this.startreimserver= function(){
		this.wsclients	= {};
		const WebSocket = require('ws');
		const wss 		= new WebSocket.Server({
			'port': this.config.reimport,
			'host': this.config.reimip,
			'verifyClient':this.verifyClient
		},function(){
			debug('reimserver start success listening:'+me.config.reimip+':'+me.config.reimport+'');
		});
		
		//客户端连接
		wss.on('connection', function connection(ws, req) {
			ws.on('message', function incoming(message){
				me.wsonmessage(this, message);
			});
			ws.on('close', function incoming(code){
				me.wsonclose(this);
			});
		});
	}
	
	/**
	*	验证是否可以连接
	*/
	this.verifyClient=function(info){
		var headers,cookie,origin,useragent,clientkey;
		headers		= info.req.headers;
		cookie 		= headers.cookie;
		origin 		= headers.origin; 
		useragent 	= headers['user-agent'];
		clientkey	= me.getcookie('clientkey', cookie);
		var cori	= me.config.reimorigin,bo;
		if(cori && cori!='*'){
			bo = false;
			var coria = cori.split(',');
			for(var i=0;i<coria.length;i++){
				if(origin.indexOf(coria[i])>-1){
					bo = true;
					break;
				}
			}
			if(!bo){
				debug('linkerr:'+origin+' not in('+cori+')');
				return false;
			}
		}
		if(!clientkey){
			//debug('linkerr:clientkey is empty');
			//return false;
		}
		if(me.md5(useragent) != clientkey){
			//debug('linkerr:clientkey is error');
			//return false;
		}
		return true;
	}
	
	this.md5=function(str){
		if(!str)return '';
		const md5obj = require('crypto').createHash('md5');
		var result = md5obj.update(str).digest('hex');
		return result;
	}
	
	this.getcookie=function(na,cokis){
		var s = '',i,cokia,cokia1;
		if(!cokis || cokis==null)return s;
		cokia = cokis.split('; ');
		for(i=0;i<cokia.length;i++){
			cokia1 = cokia[i].split('=');
			if(na==cokia1[0]){
				s  = cokia1[1];
				break;
			}
		}
		return s;
	}
	
	this.runcmd=function(str){
		if(!str)return;
		var cmd = config.getSyspath()+''+str;
		execcmd(cmd);
	}
	
	/**
	*	收到信息
	*/
	this.wsonmessage=function(ws, msg){
		var msg	= msg.toString();
		debug('received: '+msg+'');
		var da = JSON.parse(msg);
		if(da.atype=='connect'){
			ws.adminid  	= da.adminid;
			ws.adminname  	= da.adminname;
			ws.recefrom  	= da.from;
			var ows			= this.getclient(da.from, da.adminid);
			if(ows && ows.adminname != da.adminname)this.wssendstype(ows, 'offoline'); //告诉原来在别的地方登录
			this.wsclients[da.from][da.adminid] = ws;
			this.runcmd('online,'+da.adminid+',1');
		}
		if(da.atype=='getonline'){
			
		}
	}
	
	/**
	*	下线
	*/
	this.wsonclose = function(ws){
		this.runcmd('online,'+ws.adminid+',0');
		var fr = ws.recefrom;
		if(!this.wsclients[fr])this.wsclients[fr]={};
		this.wsclients[fr][ws.adminid] = false;
	}
	
	this.getclient = function(fr, uid){
		if(!this.wsclients[fr])this.wsclients[fr]={};
		return this.wsclients[fr][uid];
	}
	
	/**
	*	发送
	*/
	this.wssendstype = function(ws, type){
		var sst = '{"ptype":"'+type+'"}';
		this.wssend(ws,sst);
	}
	this.wssend = function(ws, sst){
		if(typeof(sst)=='object')sst = JSON.stringify(sst);
		ws.send(sst);
	}
	
	/**
	*	收到推送消息
	*/
	this.wsreimpush = function(d){
		if(!d.receid)return;
		var receida= d.receid.split(','),i,ws;
		for(i=0;i<receida.length;i++){
			ws = this.getclient(d.from, receida[i]);
			if(ws){
				this.wssend(ws, d);
			}
		}
	}
}


//开始运行
var reim = new rockReimServer();
reim.run();