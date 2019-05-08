/**
*	rockdatepicker 模式窗口
*	caratename：rainrock
*	caratetime：2014-05-13 21:40:00
*	email:admin@rockoa.com
*	homepage:www.rockoa.com
*/

(function ($) {
	
	function rockmodel(options){
		
		var me = this;
		
		this._init=function(opts){
			for(var a in opts)this[a]=opts[a];
			
			if(!this.lang)this.lang = $('html').attr('lang');
			
			$('#rockModal').remove();
			var atts = '',heis='';
			if(this.width)atts='style="width:'+this.width+'"';
			if(this.bodyheight)heis='style="height:'+this.bodyheight+';overflow:auto;"';
			var s = '<div class="modal fade" id="rockModal" tabindex="-1" role="dialog" style="left:0px;top:0px" aria-labelledby="myModalLabel">';
			s+='<div class="modal-dialog" '+atts+' role="document">';
			s+=' 	<div class="modal-content">';
			s+=' 		<div class="modal-header" >';
			if(this.closebool)s+='			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
			s+='			<h4 onmousedown="js.move(\'rockModal\')" class="modal-title">'+this.getlang('title')+'</h4>';
			s+='		</div>';
			s+='		<div class="modal-body" '+heis+' id="rockModal_body"></div>';
			if(this.footerbool){
				s+='		<div class="modal-footer">';
				s+='			<span id="msgviewmodel"></span>';
				if(this.type!=0)s+='<button type="button" id="rockModal_allcheke" class="btn btn-info">'+this.getlang('allchekebtn')+'</button> &nbsp; ';
				s+='			<button type="button" id="rockModal_ok" class="btn btn-success">'+this.getlang('okbtn')+'</button>';
				if(this.type!=0)s+=' &nbsp; <button id="rockModal_close" type="button" class="btn btn-default">'+this.getlang('closebtn')+'</button>';
				s+='		</div>';
			}
			s+=' 	</div>';
			s+=' </div>';
			s+='</div>';
			$('body').append(s);
			this.modalobj = $('#rockModal').modal({'keyboard':false,'show':true,'backdrop':'static'});
			
			this.modalobj.on('hidden.bs.modal',function(){
				$('#rockModal').remove();
			});
			
			$('#rockModal_ok').click(function(){
				me._onok(this);
			});
			$('#rockModal_close').click(function(){
				if(me.onclose())me.close();
			});
			$('#rockModal_allcheke').click(function(){
				$(".modal-body").find(":checkbox").prop("checked", true);
			});
			this.setbody(this.body);
			if(this.bodypadding)$('#rockModal_body').css('padding',this.bodypadding);
			this.delbool = 0;
			if(this.type==3){
				this.setbody('<i class="glyphicon glyphicon-info-sign"></i> '+this.getlang('delmsg'));
			}
			
			if(this.type==4){
				this.setbody('<iframe src="" name="openmodeliframe" width="100%" height="'+this.height+'" frameborder="0"></iframe>');
				openmodeliframe.location.href=this.url;
			}
			
			if(this.type==2){
				this.setbody('<div>'+this.body+'</div><textarea id="rockmodelpromptcont" style="height:70px" class="form-control">'+this.inputtxt+'</textarea>');
			}
			
			if(this.type==5){
				this.setbody(this._createinput());
			}
			
			this.loadbody();
			this.onshow();
		};
		this.setmsg=function(txt,col){
			js.setmsg(txt,col,'msgviewmodel');
		};
		
		//按钮禁用
		this.btndisabled=function(bo, bos){
			var o2 = get('rockModal_close');
			if(o2)o2.disabled=bo;
			if(!bos)get('rockModal_ok').disabled=bo;
		}
	
		this._onok=function(o1){
			if(!this.onok())return;
			if(this.type!=5)this.btndisabled(true);
			if(this.delbool==2){
				this.close();
				return;
			}
			if(this.delbool==3){
				this.ondelok();
				this.close();
				return;
			}
			if(this.type==5){
				var da = js.getformdata('inputmodelform');
				var a=this.columns,len=a.length,i;
				for(i=0;i<len;i++){
					d = a[i];
					if(d.required && da[d.name]==''){
						this.setmsg(''+d.labelText+'不能为空');
						form(d.name,'inputmodelform').focus();
						return;
					}
				}
				this.setmsg('保存中...');
				this.btndisabled(true);
				$.ajax({
					type:'post',dateType:'json',
					url:this.saveurl,data:$.extend(da,this.saveparams),
					success:function(ret){
						if(ret.success){
							var msg = ret.data;
							if(!msg)msg='保存成功';
							js.msgok(msg);
							me.onsuccess(ret);
							me.close();
						}else{
							me.setmsg(ret.msg);
							me.btndisabled(false);
						}
					},
					error:function(){
						me.btndisabled(false);
					}
				});
				return;
			}
			if(this.type==3 && this.delurl){
				this.setbody(js.getmsg(this.getlang('delwait')));
				this.delbool = 1;
				this.delparams.id = this.delid;
				$.ajax({
					type:'post',dateType:'json',
					url:this.delurl,data:this.delparams,
					success:function(ret){
						if(ret.success){
							me.delbool = 3;
							var msg = me.getlang('delok');
							me.setbody(js.getmsg(msg,'green'));
							var delrow = ''+me.delrow+'_'+me.delid+'';
							if(get(delrow)){
								$('#'+delrow+'').remove();
								me.close();
								$.rockmodelmsg('ok', msg);
							}
						}else{
							me.delbool = 2;//失败
							me.setbody(js.getmsg(ret.msg));
						}
						$('#rockModal_close').remove();
						me.btndisabled(false);
					},
					error:function(){
						me.delbool = 2;
						me.setbody(js.getmsg(me.getlang('delfail')));
						me.btndisabled(false);
					}
				});
			}else{
				this.close();
			}
		}
		
		this.close=function(){
			this.modalobj.modal('hide');
		};
		this.setbody=function(s){
			$('#rockModal_body').html(s);
		};
		
		this.getlang=function(k){
			var str = this[k];
			if(str)return str;
			var zh_cnarr 	= {
				'closebtn':'取消',
				'allchekebtn':'全选',
				'okbtn':'确定',
				'title': '系统提示',
				'delmsg':'确定要删除此记录吗？',
				'delwait':'删除中...',
				'delok':'删除成功',
				'delfail':'删除失败',
				'loading':'加载中...'
			};
			var enarr 	= {
				'closebtn':'close',
				'allchekebtn':'all cheke',
				'okbtn':'ok',
				'title': 'System hints',
				'delmsg' :'Are you sure you want to delete this record?',
				'delwait':'delete ing...',
				'delok':'delete success',
				'delfail':'delete failure',
				'loading':'loading...'
			};
			var langarr 	= {'zh-CN':zh_cnarr,'en':enarr};
			return langarr[this.lang][k];
		};
		
		this.loadbody=function(){
			if(!this.loadurl)return;
			this.setbody(js.getmsg(this.getlang('loading')));
			this.btndisabled(true);
			js.ajax(this.loadurl,false,function(ret){
				me.onloadsuccess(ret.data);
				me.btndisabled(false);
			},'get',function(msg){
				me.setbody(js.getmsg(msg));
				me.btndisabled(false);
			});
		};
		
		this.defaultfields={type:'text',blankText:'',labelText:'',required:false,readOnly:false,labelBox:'',attr:'',value:''};
		this._createinput = function(){
			var s = '',a=this.columns,i,i1,style='padding:10px';
			s+='<div align="center">';
			s+='<form name="inputmodelform" style="padding:0px;maring:0px">';
			s+='<input name="id" value="'+this.saveid+'" type="hidden">';
			s+='<table width="98%">';
			for(i=0; i<a.length; i++){
				this.columns[i]	= js.applyIf(a[i], this.defaultfields);
				a[i]			= this.columns[i];
				var bl=a[i].blankText,bt='',attr = a[i].attr;
				if(!bl)bl='';if(!attr)attr='';
				if(a[i].required)bt='<font color="red">*</font>';
				if(a[i].readOnly)attr+=' readonly';
				if(a[i].repEmpty)attr+=' onblur="this.value=strreplace(this.value)"';
				if(a[i].type=='number')attr+=' onfocus="js.focusval=this.value" onblur="js.number(this)"';
				var inp = '<input placeholder="'+bl+'" '+attr+' type="'+a[i].type+'" value="'+a[i].value+'" name="'+a[i].name+'" width="95%" class="form-control">';
				if(a[i].type=='checkbox'){
					if(a[i].checked)attr+=' checked';
					inp = '<label><input name="'+a[i].name+'" '+attr+'  value="1" type="checkbox"> '+a[i].labelBox+'</label>';
				}else if(a[i].type=='textarea'){
					inp = '<textarea placeholder="'+bl+'" '+attr+' name="'+a[i].name+'" class="form-control" style="height:'+a[i].height+'px">'+a[i].value+'</textarea>';
				}else if(a[i].type=='select'){
					inp	= '<select name="'+a[i].name+'" class="form-control">';
					var sto = a[i].store;
					for(i1=0;i1<sto.length;i1++){
						inp+='<option value="'+sto[i1][a[i].valuefields]+'">'+sto[i1][a[i].displayfields]+'</option>';
					}
					inp += '</select>';
				}else if(a[i].type=='changeuser'){
					inp	= '<div class="input-group"><input readonly class="form-control" name="'+a[i].name+'" >';
					inp+= '<span class="input-group-btn">';
					if(a[i].clearbool)inp+= '<button class="btn btn-default" changeclear="'+a[i].name+'" type="button"><i class="icon-remove"></i></button>';
					inp+= '<button class="btn btn-default" changeuser="'+a[i].name+'" type="button"><i class="icon-search"></i></button>';
					inp+= '</span></div>';
				}else if(a[i].type=='date'){
					inp	= '<div class="input-group"><input readonly class="form-control" id="'+a[i].name+'-'+rand+'-inputid" name="'+a[i].name+'" >';
					inp+= '<span class="input-group-btn">';
					inp+= '<button class="btn btn-default" '+attr+' changedate="'+a[i].view+'" inputid="'+a[i].name+'-'+rand+'-inputid" type="button"><i class="icon-calendar"></i></button>';
					inp+= '</span></div>';	
				}else if(a[i].type=='html'){
					inp = a[i].html;
				}
				if(a[i].type == 'hidden'){
					s+='<tr><td></td><td>'+inp+'</td></tr>';
				}else{
					s+='<tr na="'+a[i].name+'">';
					s+='<td align="right" style="padding-right:5px" nowrap>'+bt+''+a[i].labelText+'</td>';
					s+='<td width="100%" style="padding:5px" align="left">'+inp+'</td>'
					s+='</tr>';
				}
			}
			s+='</table>';
			s+='</form>';
			s+='</div>';
			return s;
		};
		this.setValues=function(){
			
		};
		
		
		this._init(options);
	};
	
	$.rockmodel = function(options){
		var defaultVal = {
			lang :'',
			body:'wait...',
			delurl:'',
			delid:'',
			delparams:{},
			delrow:'row',
			width:'',
			bodynone:false,
			onclose:function(){return true;},
			onshow:function(){},
			onok:function(){return true;},
			ondelok:function(){location.reload()},
			footerbool:true,
			closebool:false,
			type:0,//0alert,1confirm,2prompt,3del,4iframe
		};
		var can		= $.extend({}, defaultVal, options);
		var aobj	= new rockmodel(can);
		return aobj;
	};
	
	$.rockmodeldel = function(opts){
		if(!opts)return;
		opts.type=3;
		return $.rockmodel(opts);
	};
	
	$.rockmodelinput=function(opts){
		var vev = {
			type:5,
			data:{},
			saveurl:'',
			saveid:'0',
			saveparams:{},
			okbtn:'保存',
			columns:[],
			onsuccess:function(){return true}
		};
		return $.rockmodel($.extend(vev, opts));
	};
	
	//模式提示
	$.rockmodelmsg  = function(lx, txt, sj){
		clearTimeout($.rockmodelmsgtime);
		$('#rockmodelmsg').remove();
		js.msg('none');
		if(lx=='none')return;
		var s = '<div id="rockmodelmsg" onclick="$(this).remove()" align="center" style="position:fixed;left:45%;top:30%;z-index:9999;border-radius:10px;padding:30px; background:rgba(0,0,0,0.7);color:white;font-size:18px">';
		if(lx=='wait'){
			if(!txt)txt='处理中...';
			s+='<div><img src="/images/mloading.gif"></div>';
			s+='<div style="padding-top:5px">'+txt+'</div>';
			if(!sj)sj= 60;
		}
		if(lx=='ok'){
			if(!txt)txt='处理成功';
			s+='<div style="font-size:40px"><i class="glyphicon glyphicon-ok"></i></div>';
			s+='<div>'+txt+'</div>';
		}
		if(lx=='msg' || !lx){
			if(!txt)txt='提示';
			s+='<div style="font-size:40px"><i class="glyphicon glyphicon-info-sign"></i></div>';
			s+='<div style="color:red">'+txt+'</div>';
		}
		s+='</div>';
		$('body').append(s);
		if(!sj)sj = 3;
		var le = (winWb()-$('#rockmodelmsg').width())*0.5-20;
		$('#rockmodelmsg').css('left',''+le+'px');
		$.rockmodelmsgtime = setTimeout("$('#rockmodelmsg').remove()", sj*1000);
	}
	js.msgok	= function(msg,sj){
		$.rockmodelmsg('ok', msg,sj);
	};
	js.msgerror	= function(msg,sj){
		$.rockmodelmsg('msg', msg,sj);
	};
	js.loading	= function(msg,sj){
		$.rockmodelmsg('wait', msg,sj);
	};
	js.unloading= function(){
		$.rockmodelmsg('none');
	};
	
	//iframe的
	$.rockmodeliframe = function(tit,url,opts1){
		if(!opts1)opts1={};
		var opts={};
		var wid = 900,hei = winHb()-150;
		if(wid>winWb())wid = winWb()-20;
		if(hei>600)hei = 600;
		opts.type	= 4;
		opts.url 	= url;
		opts.title 	= tit;
		opts.width 	= ''+wid+'px';
		opts.height = ''+hei+'px';
		opts.footerbool=false;
		opts.closebool=true;
		opts.bodypadding='0px';
		opts = $.extend(opts, opts1);
		if(opts.height=='max')opts.height=''+(winHb()-150)+'px';
		return $.rockmodel(opts);
	};
	
	$.rockmodelconfirm=function(msg,fun){
		var opts={};
		if(!fun)fun=function(){return true};
		opts.type	= 1;
		opts.body	= msg;
		opts.onok	= function(){fun('yes');return true;};
		opts.onclose= function(){fun('no');return true;};
		return $.rockmodel(opts);
	};
	js.confirm = $.rockmodelconfirm;
	
	$.rockmodelprompt=function(tit,msg,fun, txt){
		var opts={};
		if(!fun)fun=function(){return true};
		if(!txt)txt='';
		opts.type	= 2;
		opts.title	= tit;
		opts.body	= msg;
		opts.inputtxt	= txt;
		opts.onok	= function(){fun('yes', $('#rockmodelpromptcont').val());return true;};
		opts.onclose= function(){fun('no');return true;};
		return $.rockmodel(opts);
	};
	js.prompt = $.rockmodelprompt;
	
	//选择人员用的
	$.rockmodeuser = function(opts){
		if(!opts)return;
		opts.type=1;
		opts.footerbool=false;
		opts.onshow=function(){
			var hei = winHb()-150;if(hei>650)hei=650;
			this.setbody('<div id="showlistview" style="height:'+hei+'px;overflow:auto;border:1px #cccccc solid;"></div>');
			$('#showlistview').parent().css('padding','0');
			var me=this;
			$('body').chnageuser({
				'showview':'showlistview',
				'titlebool':false,
				'changetype':this.changetype,
				'oncancel':function(){
					me.close();
				},
				'onselect':function(sna,sid){
					me.onselect(sna,sid);
					me.close();
				}
			});
		}
		return $.rockmodel($.extend({}, {
			changetype:'user',
			onselect:function(){},
			width:'400px'
		}, opts));
	};
	
	
	/**
	*	双击编辑组件
	*/
	function rockmodelediter(options){
		var me = this;
		
		this._init=function(opts){
			for(var a in opts)this[a]=opts[a];
			this._create();
		};
		
		this._create = function(){
			var rand	= 'rockediter';
			this.mobj	= $(this.obj);
			var o 		= this.mobj,
				fields  = o.attr('edata-fields'),val='';
			val			= o.attr('edata-value');
			if(!fields)fields = this.fields;
			if(typeof(this.value)!='undefined' && typeof(val)=='undefined')val = this.value;
			var a		= {},i,len,sel,
				b		= this.columns[fields],
				l		= o.offset(),
				w		= this.obj.clientWidth,
				h		= this.obj.clientHeight,
				at		= '';
			if(isempt(val))val = this.mobj.html();
			if(!b || !fields){
				js.msg('msg','没有设置'+fields+'字段的信息');
				return;	
			}
			if(b.onediterbefore){
				var bo1 = b.onediterbefore(val);
				if(bo1===false)return;
			}
			this.oldval	= val;
			this.nowfields = b;
			$('#edittable_'+rand+'').remove();
			var s	= '<div id="edittable_divstr" style="position:absolute;z-index:2;left:'+(l.left)+'px;top:'+(l.top+h)+'px">';
			s+='<div style="border:1px #cccccc solid;background:white;padding:10px;box-shadow:0px 0px 10px rgba(0,0,0,0.3); border-radius:10px">';
			s+='	<div><span id="msgteita_thise"><b>'+b.name+'</b>：</span></div>';
			s+='	<div class="blank10"></div>';
			
			var wss = 200,attr='';
			var flx = b.type,ftype = 'text';
			if(flx=='number'){
				ftype = 'number';
				attr += ' onfocus="js.focusval=this.value" onblur="js.number(this)"';
			}
			if(flx=='email'){
				this.nowfields.onsavebefore=function(vl1){
					var bo = js.email(vl1);
					if(!bo){
						js.msg('msg','不是正确的邮箱格式');
						return false;
					}
				}
			}
			if(b.repEmpty)attr+=' onblur="this.value=strreplace(this.value)" ';
			if(flx=='select'){
				s+='<div><select style="width:'+wss+'px" id="inputedit_'+rand+'" '+attr+' class="form-control">';
				for(i=0;i<b.store.length;i++){
					sel = '';
					if(b.store[i].value==val)sel='selected';
					s+='<option value="'+b.store[i].value+'" '+sel+'>'+b.store[i].name+'</option>';
				}
				s+='</select></div>';
			}else if(flx=='checkbox'){
				if(val=='1')at='checked';
				s+='<div><label><input type="checkbox" id="inputedit_'+rand+'" '+at+' value="1"> '+b.name+'</label></div>';
			}else if(flx=='textarea'){
				s+='<div><textarea type="text" style="width:'+wss+'px;height:100px" '+attr+' id="inputedit_'+rand+'" class="form-control">'+val+'</textarea></div>';	
			}else{
				s+='<div><input type="'+ftype+'" style="width:'+wss+'px" id="inputedit_'+rand+'" '+attr+' class="form-control" value="'+val+'"></div>';
			}
			
			s+='	<div class="blank10"></div>';
			s+='</div>';
			s+='<div align="center"><div style="width: 0;height: 0;border-left: 10px solid transparent; border-right: 10px solid transparent;border-top: 10px solid #cccccc; font-size: 0;line-height: 0;"></div></div>';
			s+='</div>';
			$('body').append(s);
			
			var o2 = get('edittable_divstr');
			var jg = (w-o2.clientWidth)*0.5,t12=l.top-o2.clientHeight;
			if(t12<5)t12=5;
			$(o2).css({left:''+(l.left+jg)+'px',top:''+t12+'px'});
			var o3 = get('inputedit_'+rand+'');
			o3.focus();
			if(typeof(this.chuliid)=='undefined'){
				var id = o.attr('edata-id');
				if(!id){
					id = o.parent().attr('id').replace('row_','');
				}
			}else{
				var id = this.chuliid;
			}
			this.id 			= id;
			this.params.fields 	= fields;
			this.params.id 		= id;
			this.params.mtable 	= this.mtable;
			$(o3).blur(function(){
				me._editforcuschen(this);
			});
		}
		
		this._editforcuschen = function(o1){
			var v = o1.value;
			if(!this.params.id){
				this.remove();
				return;
			}
			if(this.nowfields.type=='checkbox'){
				if(!o1.checked)v='0';
			}
			if(this.oldval!=v){
				var fun1 = this.nowfields.onsavebefore;
				if(fun1){
					var bo1 = fun1(v);
					if(bo1===false){
						this.remove();
						return false;
					}
				}
				var url = this.saveurl;
				if(url=='0')url = '/api/unit/'+cnum+'/option_saveediter';
				if(url=='1')url = '/webapi/admin/agent_saveediter';
				if(url=='2')url = '/api/agent/'+cnum+'/'+this.agenhnum+'/flow_saveediter';
				this.bool=true;
				js.setmsg('处理中...','','msgteita_thise');
				this.params.value = v;
				js.ajax(url, this.params, function(){
					me.remove();
					me.mobj.attr('edata-value', v);
					var fun1 = me.nowfields.renderer;
					if(fun1)v = fun1(v, me.nowfields, me);
					me.mobj.html(v);
				},'post', function(msg){
					me.remove();
				});
			}else{
				this.remove();
			}
		}
		this.remove=function(){
			$('#edittable_divstr').remove();
		};
		this._init(options);
	}
	$.rockmodelediter	= function(options){
		if(get('edittable_divstr'))return false;
		var defaultVal = {
			saveurl:'0',
			obj:false,
			fields:'',
			columns:{},
			params:{}
		};
		var can		= $.extend({}, defaultVal, options);
		var aobj	= new rockmodelediter(can);
		return aobj;
	};
	
})(jQuery); 