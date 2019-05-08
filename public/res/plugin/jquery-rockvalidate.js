/**
*	rockdatepicker 表单验证
*	caratename：rainrock
*	caratetime：2014-05-13 21:40:00
*	email:admin@rockoa.com
*	homepage:www.rockoa.com
*/

(function ($) {
	
	function rockvalidate(options){
		
		var me = this;
		
		this._init=function(opts){
			for(var a in opts)this[a]=opts[a];
			this.formobj = document[this.formname];
			this.formobs = $(this.formobj);
			if(!this.lang)this.lang = $('html').attr('lang');
			if(!this.submitmsg)this.submitmsg = this.getlang('subtxt');
			if(this.autovalid)this.valid();
		};
		
		this.getformdata = function(){
			return js.getformdata(this.formname);
		};
		
		
		this.ismobile=function(sjh){
			var partten = /^1[3,5,8,4,6,7]\d{9}$/;
			if(!partten.test(sjh) || sjh.length!=11){
				return false;
			}
			return true;
		};
		
		this.isemail= function(str){
			if(!str || str.indexOf(' ')>-1)return false;
			if(str.indexOf('.')==-1 || str.indexOf('@')==-1)return false;
			if(this.ischinese(str))return false;
			return true;
		};
		
		//判断是否有中文
		this.ischinese=function(str){
			var re=/[\u4e00-\u9fa5]/;
			if (re.test(str))return true;
			return false;
		};
		
		this.ishanen=function(){
			var re=/[0-9a-z]/i;
			if (re.test(str)) return true;
			return false;
		};
		
		//判断是不是数字
		this.isnumber=function(str){
			if(isNaN(str))return false;
			return true;
		};
		
		this.valid	= function(){
			var da	= this.getformdata(),ona='',o,o1,type,val,na,i,obj,req,s2,fna,parrt;
			obj		= this.formobj;
			for(i=0;i<obj.length;i++){
				o 	 = obj[i];
				if(o.disabled)continue;
				o1 	 = $(o);
				type = o1.attr('type'),val = o.value,na = o.name;
				req  = o1.attr('required');
				fna  = o1.data('fields');if(!fna)fna=na;
				this.removeerror(na);
				s2	 = this.oncheck(na,val,da, o1);
				if(s2 || typeof(s2)=='boolean')return this.showerror(na,s2,o1); 
				if(req=='required' && !val){
					return this.showerror(na,''+fna+''+this.getlang('notnull')+'',o1);
				}
				
				if(type=='number' && val){
					if(!this.isnumber(val))
					return this.showerror(na,''+fna+''+this.getlang('onlyint')+'',o1);
				}
				if(type=='mobile' && val){
					if(!this.ismobile(val))
					return this.showerror(na,''+fna+''+this.getlang('format')+'',o1);
				}
				if(type=='email' && val){
					if(!this.isemail(val))
					return this.showerror(na,''+fna+''+this.getlang('format')+'',o1);
				}
				if(type=='onlyen' && val){
					if(this.ischinese(val))
					return this.showerror(na,''+fna+''+this.getlang('onlyen')+'',o1);
				}
				if(type=='onlycn' && val){
					if(this.ishanen(val))
					return this.showerror(na,''+fna+''+this.getlang('onlycn')+'',o1);
				}
				//正则
				if(val){
					parrt = o1.data('pattern');
					if(parrt){
						if(!new RegExp('/'+parrt+'/','i').test(val))
						return this.showerror(na,''+fna+''+this.getlang('format')+'',o1);
					}
				}
			}
			if(this.autosubmit)this.submit(this.formobj['submitbtn']);
		};
		
		this.getlang=function(k){
			var zh_cnarr 	= {
				'subok':'成功',
				'subwait':'中',
				'notnull':'不能为空',
				'format':'格式有错',
				'subtxt':'处理',
				'onlyen':'不能包含中文',
				'onlycn':'只能使用中文',
				'onlyint':'只能使用数字',
			};
			var enarr 	= {
				'subok':' success',
				'subwait':' loading',
				'notnull':' can\'t be empty',
				'format':' format error',
				'subtxt':' process',
				'onlyen':' Cannot contain Chinese',
				'onlycn':' Only use Chinese',
				'onlyint':' Only use numbers'
			};
			var langarr 	= {'zh-CN':zh_cnarr,'en':enarr};
			return langarr[this.lang][k];
		};
		
		/**
		*	提交
		*/
		this.submit=function(o1){
			if(this.submitbool)return;
			var da = this.getformdata();
			if(o1)o1.disabled=true;
			js.setmsg(''+this.submitmsg+''+this.getlang('subwait')+'...','', this.msgview);
			this.submitbool = true;
			this.onsubmitcheck(da);
			for(var i in this.submitparams)da[i]=this.submitparams[i];
			var url = this.url;
			var jg  = url.indexOf('?')>0 ? '&':'?';
			$.ajax({
				data:da,
				url:url,type:'post',dateType:'json',
				success:function(ret){
					me.submitbool = false;
					if(!ret.success){
						o1.disabled=false;
						js.setmsg(ret.msg,'red', me.msgview);
						if(ret.errors){
							for(var k in ret.errors){
								me.showerror(k, ret.errors[k][0]);
								break;
							}
						}
						js.msg('msg', ret.msg);
						me.onsubmiterror(ret);
					}else{
						js.setmsg(''+me.submitmsg+''+me.getlang('subok')+'','green', me.msgview);
						me._onsubmitsuccess(ret);
					}
				},
				error:function(e){
					me.submitbool = false;
					o1.disabled=false;
					js.debug(e);
					js.setmsg('error:'+e.responseText+'','red', me.msgview);
					me.onsubmiterror();
				}
			});
		};
		
		this._onsubmitsuccess=function(ret){
			if($.rockmodel){
				var msg = ret.data.msg;if(!msg)msg = ''+this.submitmsg+''+this.getlang('subok')+'';
				$.rockmodel({
					'body':msg,
					okbtn:this.okbtn,
					onok:function(){
						return me.okback();
					}
				});
			}
			this.onsubmitsuccess(ret);
		};
		
		this.okback=function(){
			if(this.autoback){
				if(!this.backurl){
					js.back();
				}else{
					location.href = this.backurl;
				}
			}
			return true;
		};
		
		this.removeerror=function(na){
			var o2 = this.formobs.find('[inputname='+na+']');
			o2.removeClass('has-error');
			o2.find('.help-block').remove();
		};
		
		this.showerror=function(na,msg, o1){
			if(!o1)o1   = $(this.formobs.find('[name='+na+']')[0]);
			if(!msg)msg = o1.data('error');
			if(!msg)msg = o1.attr('placeholder');
			var o2 = this.formobs.find('[inputname='+na+']');
			o2.addClass('has-error');
			o2.find('.help-block').remove();
			var erid = ''+this.formname+'_'+na+'_errview';
			var erst = '<span class="help-block">'+msg+'</span>';
			if(get(erid)){
				$('#'+erid+'').html(erst);
			}else{
				o1.after(erst);
			}
			o1.focus();
			this.onvaliderror(na,o1);
		};
		
		this._init(options);
	};
	
	$.rockvalidate = function(options){
		var defaultVal = {
			lang		:'', //语言
			formname	:'myform', //默认form
			msgview 	: 'msgview',
			autovalid:true,  //自动验证
			autosubmit:true, //验证通过自动提交
			oncheck:function(){},
			onsubmitsuccess:function(){},
			onsubmitcheck:function(){}, //提交之前处理
			onsubmiterror:function(){},
			onvaliderror:function(){},
			submitparams:{},
			submitmsg:'',
			okbtn:'',
			backurl:'',
			autoback:true
		};
		var can		= $.extend({}, defaultVal, options);
		var aobj	= new rockvalidate(can);
		return aobj;
	};
})(jQuery); 