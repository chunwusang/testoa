/**
*	信呼OA云平台
*	录入页面/详情页面
*/

eventdelsubrows=function(zb){}
eventaddsubrows=function(zb){}
changesubtable=function(zb){return '';}
changedatetime=function(){}

var c = {
	init:function(){
		if(isinput==1)this.initinput();
		if(isinput==0)this.initdetail();
		
		//改变页面时判断是不是移动端
		$(window).resize(this.resizewin);
		this.resizewin();
		
		js.datechange=function(o1,lx){
			function onchangedt(v){
				changedatetime(o1, v);
			}
			if(ismobile==0)$(o1).rockdatepicker({'view':lx,'initshow':true,itemsclick:onchangedt});
			if(ismobile==1)$.rockdatepicker_mobile({'inputobj':o1,'view':lx,itemsclick:onchangedt});
			return false;
		}
		//要计算的公式
		gongsiarr = [];
		var i,len=fieldsarr.length,d;
		for(i=0;i<len;i++){
			d = fieldsarr[i];
			if(d.gongsi)gongsiarr.push({
				'iszb':d.iszb,
				'fields':d.fields,
				'gongsi':d.gongsi,
				'name':d.name,
			});
		}
	},
	resizewin:function(){
		var w = winWb();
		ismobile = (w<430) ? 1 : 0;
		var objd,wid,i,wid1,llq=navigator.userAgent.toLowerCase();
		objd= $("div[lesub='subtable']");
		wid = w-12;
		if(llq.indexOf('linux')>0 || llq.indexOf('iphone')>0){
		}else{
			wid=wid-10;
		}
		for(i=0;i<objd.length;i++){
			wid1 = $('#tablesub'+(i+1)+'').width();
			if(wid1>wid){
				$(objd[i]).css('width',''+wid+'px');
			}else{
				$(objd[i]).css('width','740px');
			}
		}
	},
	initinput:function(){
		var obj = $('textarea[temp="htmlediter"]'),i,fid,f,j,fval,d;
		for(i=0;i<obj.length;i++){
			this.htmlediter(obj[i].name);
		}
		var nfid='';
		for(i=0;i<fieldsarr.length;i++){
			d = fieldsarr[i];
			if(d.iszb==0 && d.islu==1 && d.fields.indexOf('temp_')!=0){
				if(!form(d.fields))nfid+='<br>('+d.fields+'.'+d.name+')';
			}
		}
		if(nfid!=''){
			js.alert('录入页面缺少必要的字段：'+nfid+'');
			this.formdisabled();
			isedit=0;
			return;
		}
		
		//显示相关文件
		if(mid>0 && filelist){
			for(i=0;i<fieldsarr.length;i++){
				fid = fieldsarr[i].fields;
				if(fieldsarr[i].fieldstype=='uploadfile'){
					fval = form(fid).value;
					if(fval){
						fval = ','+fval+',';
						for(j=0;j<filelist.length;j++){
							f = filelist[j];
							if(fval.indexOf(','+f.id+',')>-1){
								this.viewfile(f,fid);
								filelist[j]=false;
							}
						}
					}
				}
			}
			if(form('sysupfile')){
				for(j=0;j<filelist.length;j++){
					f = filelist[j];
					if(f)this.viewfile(f,'sysupfile');
				}
				this.showfile('sysupfile');
			}
		}
		if(isedit==0)this.formdisabled();
		this.olddata = js.getformdata();
	},
	save:function(){
		var btn = get('AltS');
		js.setmsg();
		var d = this.savedata();
		if(!d)return;
		if(typeof(d)=='string'){
			this.showerror(d);return;
		}
		btn.disabled=true;
		var str = btn.value;
		if(!str)str=btn.innerHTML;
		js.setmsg(''+str+'中...');
		var surl = '/api/agent/'+cnum+'/'+agenhnum+'/input';
		d.mainmid = mid;
		js.ajax(surl, d,function(ret){
			isedit = 0;
			var msg = ''+str+'成功';
			$('#isturnlabel').remove();
			js.setmsg(msg,'green');
			js.msg();
			js.msgok(msg);
			btn.remove();
			c.formdisabled();
			try{parent.showcallback(msg)}catch(e){}
		},'post',function(msg){
			js.setmsg(msg+'');
			btn.disabled=false;
		});
	},
	formdisabled:function(){
		$('form').find('*').attr('disabled', true);
		$('.close').remove();
	},
	showerror:function(msg,fid){
		//js.setmsg(msg);
		js.msg('msg', msg);
		if(fid && form(fid))form(fid).focus();
	},
	pandexie:function(da){
		var k,v,bo=false;
		for(k in da){
			if(typeof(this.olddata[k])==undefined || da[k]!=this.olddata[k])
				return true;
		}
		for(k in this.olddata){
			if(typeof(da[k])==undefined || da[k]!=this.olddata[k])
				return true;
		}
		return bo;
	},
	savedata:function(){
		changesubmitbefore();
		var da = js.getformdata(),i,len=fieldsarr.length,fty,j1,fa,fid,attr,zbd;
		var zbname = [],sdtname,edename;
		for(i=0;i<len;i++){
			fa = fieldsarr[i];
			fid= fa.fields;
			fty= fa.fieldstype;
			attr= fa.attr;
			if(fa.iszb!=0)continue;
			if(fa.isbt==1 && isempt(da[fid]) && fty!='subtable'){
				this.showerror(''+fa.name+'不能为空', fid);
				return false;
			}
			if(fa.isbt==1 && fty=='number' &&  parseFloat(da[fid])==0){
				this.showerror(''+fa.name+'不能为0', fid);
				return false;
			}
			if(fty=='subtable'){
				zbname[fa.dev] = fa.name;
			}
			if(fa.fieldstype=='htmlediter')da[fid] = this.editorobj[fid].html();
			if(fid=='startdt')sdtname=fa.name;
			if(fid=='enddt')edename=fa.name;
		}
		
		if(sdtname && edename && da.startdt && da.enddt){
			if(da.startdt>=da.enddt){
				this.showerror(''+sdtname+'必须大于'+edename+'', 'enddt');
				return false;
			}
		}
		
		//子表判断
		var j1,zbd,sda,zbs,zbn;
		for(i=1;i<=$('.subtable').length;i++){
			zbd = this.getsubdata(i);
			zbn = zbname[i];
			if(!zbn)zbn='第'+i+'的';
			if(!form('sub_minrow'+i+'')){
				this.showerror('第'+i+'的子表设计有错');
				return false;
			}
			zbs = parseFloat(form('sub_minrow'+i+'').value);
			if(zbs==0)continue; //至少数
			if(zbs>0 && zbd.length<zbs){
				this.showerror(''+zbn+'子表至少要'+zbs+'行');
				return false;
			}
			
			for(j1=0;j1<zbd.length;j1++){
				for(j=0;j<len;j++){
					sda = fieldsarr[j];
					if(sda.iszb==i && sda.isbt==1){//子表要对应
						flx = sda.fieldstype;
						val = zbd[j1][sda.fields];
						fid = ''+sda.fields+''+i+'_'+zbd[j1]._hang+'';
						if(isempt(val)){
							if(form(fid))form(fid).focus();
							this.showerror(''+zbn+'子表第'+(j1+1)+'行上：'+sda.name+'不能为空',fid);
							this.subshantiss(i, fid,0);
							return false;
						}
						if(flx=='number'&&parseFloat(val)==0){
							if(form(fid))form(fid).focus();
							this.showerror(''+zbn+'子表第'+(j1+1)+'行上：'+sda.name+'不能为0',fid);
							this.subshantiss(i, fid,0);
							return false;
						}
					}
				}
				//整行判断
				var s1 = changesubtable(i, zbd[j1]);
				if(s1 && typeof(s1)=='string'){
					this.showerror(''+zbn+'子表第'+(j1+1)+'行上：'+s1+'');
					this.subshantis(i, j1,0);
					return false;
				}
			}
		}
				
		var s=changesubmit(da);
		if(typeof(s)=='string'&&s!=''){
			this.showerror(s);
			return false;
		}
		if(typeof(s)=='object')da=js.apply(da,s);
		
		if(!this.pandexie(da)){
			this.showerror('没有任何修改，无需保存');
			return false;
		}
		return da;
	},
	subshantis:function(i,j,oi){
		clearTimeout(this.subshantistime);
		if(oi%2==0){
			get('tablesub'+i+'').rows[j+1].style.backgroundColor='red';
		}else{
			get('tablesub'+i+'').rows[j+1].style.backgroundColor='';
			if(oi>10)return;
		}
		this.subshantistime = setTimeout(function(){c.subshantis(i,j,oi+1);},200);
	},
	subshantiss:function(i,fid,oi){
		if(!form(fid))return;
		clearTimeout(this.subshantistime1);
		if(oi%2==0){
			$(form(fid)).parent().css('background','red');
		}else{
			$(form(fid)).parent().css('background','');
			if(oi>10)return;
		}
		this.subshantistime1 = setTimeout(function(){c.subshantiss(i,fid,oi+1);},200);
	},
	changeclear:function(fid){
		if(isedit==0)return;
		var msg = this.onchangeuserbefore(fid);
		if(!msg)msg=this.onselectdatabefore(fid);
		if(msg){
			js.msg('msg', msg);
			return;
		}
		if(fid=='applyname' && mid>0 && form(fid).value!=''){
			js.msg('msg', '保存提交后对应人不允许在切换修改');
			return;
		}
		get('change_'+fid+'').value='';
		var fids = 'change_'+fid+'_id';
		if(get(fids))get(fids).value='';
		get('change_'+fid+'').focus();
	},
	changeturn:function(o){
		if(!get('AltS'))return;
		var str = o.checked ? '提交' : '保存草稿';
		get('AltS').value = str;
		$('#AltS').html(str);
		form('isturn').value = o.checked ? '1' : '0';
	},
	onchangeuserbefore:function(){return ''},
	onchangeuser:function(){},
	changeuser:function(fid, lx, tit,fw){
		if(isedit==0)return;
		var msg = this.onchangeuserbefore(fid);
		if(msg){
			js.msg('msg', msg);
			return;
		}
		if(!fw)fw='';
		if(fid=='applyname' && mid>0 && form(fid).value!=''){
			js.msg('msg', '保存提交后对应人不允许在切换修改');
			return;
		}
		var cans = {
			nameobj:get('change_'+fid+''),
			idobj:get('change_'+fid+'_id'),
			changetype:lx,
			fidabc:fid,
			onselect:function(sna,sid){
				c.onchangeuser(this.fidabc, sna,sid);
			},
			title:tit,
			changerange:fw
		};
		if(ismobile==0){
			cans.showview = 'showuserssvie';
			cans.titlebool= false;
			cans.oncancel=function(){
				js.tanclose('changeaction');
			}
			var hei = 550;
			if(hei-winHb()>0)hei=winHb();
			js.tanbody('changeaction',tit,400,hei,{
				html:'<div id="'+cans.showview+'" style="height:'+(hei-40)+'px"></div>',
				bbar:'none'
			});
		}
		$('body').chnageuser(cans);
	},
	uploadimgclear:function(fid){
		form(fid).value='';
		get(''+fid+'_imgview').src='/images/noimg.jpg';
	},
	uploadimg:function(fid,o1){
		if(isedit==0)return;
		if(typeof(upobj)=='undefined')upobj = $.rockupfile();
		upobj.changefile({
			'fileinput':fid,
			'uptype':'image',
			'onsuccess':function(ret){
				var url = ret.viewpath,fid=this.fileinput;
				get(''+fid+'_imgview').src=url;
				if(form(fid)){
					form(fid).value=ret.imgpath; //用缩略图
				}
				var sidv = ''+fid+'_input';
				if(get(sidv))get(sidv).value=ret.allpath;
				o1.disabled=false;
				var msg = '上传完成';
				o1.value=msg;
				o1.innerHTML=msg;
			},
			'onchange':function(f){
				var msg = '上传中...';
				o1.value=msg;
				o1.innerHTML=msg;
				o1.disabled=true;
			},
			'onprogress':function(f,v){
				var msg = '上传中('+v+')...';
				o1.value=msg;
				o1.innerHTML=msg;
			},
			'onerror':function(msg){
				var msg = '上传失败';
				o1.value=msg;
				o1.innerHTML=msg;
			}
		});
	},
	uploadfile:function(fid,ulx){
		if(isedit==0)return;
		if(typeof(upobj)=='undefined')upobj = $.rockupfile();
		if(this.upfbo){js.msg('msg','请等待上传完成在添加');return;}
		upobj.changefile({
			'fileinput':fid,
			'uptype':ulx,
			'onsuccess':function(ret){
				var fid=this.fileinput;
				ret.fields = fid;
				js.ajax('/api/we/file_save/'+cnum+'',ret, function(bret){
					$('#meng_'+c.uprnd+'').remove();
					$('#close_'+c.uprnd+'').attr('upid_'+fid+'',bret.data.id);
					$('#close_'+c.uprnd+'').show();
					c.upfbo = false;
					c.showfile(fid);
				},'post',function(){
					$('#meng_'+c.uprnd+'').html('<font color=red>上传失败</font>');
					c.upfbo = false;
				});
			},
			'onchange':function(f){
				c.upfbo = true;
				f.id = 0;
				c.viewfile(f, this.fileinput);
			},
			'onprogress':function(f,v){
				$('#meng_'+c.uprnd+'').html(''+v+'%');
			},
			'onerror':function(msg){
				$('#meng_'+c.uprnd+'').html('<font color=red>上传失败</font>');
				js.msg('msg', msg);
				c.upfbo = false;
			}
		});
	},
	viewfile:function(f,fid){
		c.uprnd = js.getrand();
		var s='<div id="up_'+c.uprnd+'" title="'+f.filename+'('+f.filesizecn+')"  class="upload_items">';
		if(js.isimg(f.fileext)){
			if(!f.imgviewurl)f.imgviewurl = showbackurl(f.thumbpath);
			s+='<img class="imgs" onclick="c.openfiles(\''+f.filenum+'\', 0,this)" src="'+f.imgviewurl+'">'
		}else{
			s+='<div class="upload_items_items"><img src="/images/fileicons/'+js.filelxext(f.fileext)+'.gif" alian="absmiddle"> ('+f.filesizecn+')<br>'+f.filename+'</div>';
		}
		s+='<span id="close_'+c.uprnd+'" onclick="c.closeupfile(\''+c.uprnd+'\',\''+fid+'\', this)" upid_'+fid+'="'+f.id+'" class="close r-cursor weui_icon_clear"></span>';
		if(f.id==0)s+='<div id="meng_'+c.uprnd+'" class="upload_items_meng" style="font-size:16px">0%</div></div>';
		$('#'+fid+'_divadd').before(s);
	},
	closeupfile:function(rnd, fid, o1){
		if(isedit==0)return;
		var id = $(o1).attr('upid_'+fid+'');
		if(id=='0'){
			$(o1).parent().remove();
			return;
		}
		js.confirm('确定要删除此上传文件吗？',function(jg){
			if(jg=='yes'){
				$(o1).parent().remove();
				c.showfile(fid);
				js.ajax('/api/we/file_del/'+cnum+'',{id:id}, function(){},'post');
			}
		});
	},
	showfile:function(fid){
		var obj = $('#fileview_'+fid+'').find('span[upid_'+fid+']');
		var sid = '';
		for(var i=0;i<obj.length;i++){
			sid+=','+$(obj[i]).attr('upid_'+fid+'');
		}
		if(sid!='')sid = sid.substr(1);
		form(fid).value = sid;
	},
	
	editorobj:{},
	htmlediter:function(fid){
		var items = [
			'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat','|','fontname', 'fontsize','quickformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|','image', 'link','unlink','|','undo','source','clearhtml','fullscreen'
		];
		if(ismobile==1)items = ['forecolor','hilitecolor','bold', 'italic','image'];
		var cans  = {
			resizeType : 0,
			allowPreviewEmoticons : false,
			allowImageUpload : true,
			formatUploadUrl:false,
			allowFileManager:true,
			uploadJson:'',filePostName:'file',
			minWidth:'100px',height:'250',
			items : items	
		};
		this.editorobj[fid] = KindEditor.create("[name='"+fid+"']", cans);
		if(isedit==0)this.editorobj[fid].readonly(true);
	},
	
	//下拉框触发
	onchangeselect:function(){},
	changeselect:function(o1,num){
		var o2  = o1.options[o1.selectedIndex];
		var val = o1.value,txt=o2.text,na = o1.name;
		if(val==txt && val=='其他..'){
			js.prompt('其他值','请输入其他值...',function(jg,nv){
				if(nv && jg=='yes'){
					o2.text=nv;
					o2.value=nv;
					o1.value=nv;
					c.changeselectsave(nv,num, o1.length);
				}else{
					o1.value='';
				}
				c.onchangeselect(o1);
			});
			return;
		}else{
			this.onchangeselect(o1);
		}
	},
	changeselectsave:function(na,num, sort){
		js.ajax('/api/unit/'+cnum+'/option_saveother',{name:na,num:num,sort:sort},false,'post');
	},
	getacturl:function(){
		var url = '/api/agent/'+cnum+'/'+agenhnum+'/flow_yunact';
		return url;
	},
	runacturl:function(act, da, fun,lxs,fune){
		da.mid = mid;
		da.act = act;
		if(!lxs)lxs='get';
		js.ajax(this.getacturl(), da, fun,lxs, fune);
	},
	imgview:function(url){
		try{
			parent.imgviewind(url);
		}catch(e){
			this.loadicons();
			$.imgview({url:url});
		}
	},
	imgviews:function(o1){
		var lup = o1.src;
		lup = lup.replace('_s.','.');
		this.imgview(lup);
	},
	loacdis:false,
	loadicons:function(){
		if(!this.loacdis){
			$('body').append('<link rel="stylesheet" type="text/css" href="/res/fontawesome/css/font-awesome.min.css">');
			this.loacdis= true;
		}
	},
	openfile:function(num,dx){
		js.confirm('确定要下载此文件吗？大小['+dx+']！<br>你也可以<a href="javascript:;" onclick="c.openfiles(\''+num+'\',0)">[预览]</a>此文件。', function(jg){
			if(jg=='yes')c.openfiles(num, 1);
		});
	},
	openfiles:function(num,glx, o1){
		if(num=='undefined'){
			this.imgview(o1.src);
			return;
		}
		js.alert('none');
		if(!glx)glx=0;
		js.loading('处理中...');
		js.ajax('api/we/file_down/'+cnum+'', {'num':num,'glx':glx},function(ret){
			var da = ret.data;
			if(glx==0 && da.isimg=='1'){
				c.imgview(da.upurl);
			}else{
				js.location(da.upurl);
			}
		},'post');
	},
	getmobj:function(fid){
		return $('[inputname="'+fid+'"]');
	},
	
	
	//子表的
	zbminrow:function(xu,shu){
		form('sub_minrow'+xu+'').value=''+shu+'';
	},
	delrow:function(o,xu){
		if(isedit==0){
			$(o).remove();
			return;
		}
		var o1=get('tablesub'+xu+'').rows;
		var minh  = parseFloat(form('sub_minrow'+xu+'').value);
		if(o1.length<=2){
			js.msg('msg','最后一行不能删除');
			return;
		}
		if(minh>0){
			if(o1.length<=minh+1){
				js.msg('msg','至少要'+minh+'行不能删除');
				return;
			}
		}
		$(o).parent().parent().remove();
		this.repaixuhao(xu);
		
		var i,len=gongsiarr.length,d;
		for(i=0;i<len;i++){
			d = gongsiarr[i];
			if(d.iszb==0)this.changegongsi(d.fields);
		}
		
		eventdelsubrows(xu);//删除一行触发
	},
	repaixuhao:function(xu){
		var o=$('#tablesub'+xu+'').find("input[temp='xuhao']");
		for(var i=0;i<o.length;i++){
			o[i].value=(i+1);
		}
	},
	addrow:function(o,xu,das){
		if(isedit==0){
			$(o).remove();
			return;
		}
		var o2 = get('tablesub'+xu+'');
		var o=$('#tablesub'+xu+'');
		var oi = o2.rows.length-1,i,str='',oba,nas,oj,nna,ax2,d1,nass,d={};
		oi=1;
		var cell = o2.rows[oi].cells.length;
		for(i=0;i<cell;i++)str+='<td  class="zbys1">'+o2.rows[oi].cells[i].innerHTML+'</td>';
		oba = o.find('tr:eq('+oi+')').find('[name]');
		oj  = parseFloat(form('sub_totals'+xu+'').value);
		var narrs=[],wux=''+xu+'_'+oj+'';
		for(i=0;i<oba.length;i++){
			nas=oba[i].name;
			oi = nas.lastIndexOf('_');
			nass= nas.substr(0, oi-1);
			nna=nass+''+wux+'';
			str=str.replace(new RegExp(nas,'gi'), nna);
			narrs.push(nna);
		}
		form('sub_totals'+xu+'').value=(oj+1);
		str=str.replace(/rockdatepickerbool=\"true\"/gi,'');
		o.append('<tr>'+str+'</tr>');
		das.sid = '0';
		for(d1 in das){
			ax2=d1+wux;
			if(form(ax2))form(ax2).value=das[d1];
		}
		this.repaixuhao(xu);
		eventaddsubrows(xu); //新增一行触发
	},
	getsubdata:function(xu){
		var d=[];
		if(!get('tablesub'+xu+''))return d;
		var len=parseFloat(form('sub_totals'+xu+'').value);
		var far = [],i;
		for(i=0;i<fieldsarr.length;i++){
			if(fieldsarr[i].iszb==xu)far.push(fieldsarr[i].fields);
		}
		
		var i1,ji,i2,lens=far.length,fna;
		for(i1=0;i1<len;i1++){
			var a={_hang:i1};i2=0;
			for(j1=0;j1<lens;j1++){
				fna=''+far[j1]+''+xu+'_'+i1+'';
				if(form(fna)){
					a[far[j1]]=form(fna).value;
					i2++;
				}
			}
			if(i2>0)d.push(a);
		}
		return d;
	},
	//根据名称获取第几个子，哪一行[0第几个子表，1第几行,2子表+行,3字段名,4全名]
	getxuandoi:function(fid){
		var naa = fid.substr(fid.lastIndexOf('_')-1);
		var spa = naa.split('_');
		spa[2] = naa;
		spa[3] = fid.replace(naa,'');
		spa[4] = fid;
		return spa;
	},
	//设置子表值
	setsubvalue:function(fid,na,val){
		var spa = this.getxuandoi(fid);
		var sna = na+spa[2];
		if(form(sna))form(sna).value = val+'';
		return spa;
	},
	//获取子表值
	getsubvalue:function(fid,na,dev){
		if(typeof(dev)=='undefined')dev='';
		var val = dev;
		var spa = this.getxuandoi(fid);
		var sna = na+spa[2];
		if(form(sna))val=form(sna).value;
		return val;
	},
	onselectdata:function(){},
	onselectdatabefore:function(){return ''},
	onselectalldata:{},
	selectdata:function(fid,ced, selid, tit,fid1){
		if(isedit==0)return;
		var msg = this.onselectdatabefore(fid)
		if(msg){
			js.msg('msg', msg);
			return;
		}
		if(!tit)tit='请选择...';
		var idobj= false;
		if(fid1)idobj=form(fid1);
		var surl = '/api/agent/'+cnum+'/'+agenhnum+'/input_selectdata?fieldsid='+selid+'&mid='+mid+'&fields='+fid+'';
		var spa = this.getxuandoi(fid);
		var tempfid = spa[3]+spa[0];
		$.selectdata({
			data:this.onselectalldata[tempfid],
			title:tit,tempfid:tempfid,fid:fid,
			ismobile:ismobile,checked:ced,
			url:surl,
			onloaddata:function(a){
				c.onselectalldata[this.tempfid]=a;
			},
			nameobj:form(fid),idobj:idobj,
			onselect:function(seld,sna,sid){
				c.onselectdata(this.fid,seld,sna,sid);
			}
		});
	},
	
	
	//----强大公式计算函数处理start-----
	changegongsi:function(fid,zb){
		this.inputblur(form(fid),zb);
	},
	inputblur:function(o1,zb){
		if(isedit==0 || !o1)return;
		var ans=[],nae,nae2,i,len=gongsiarr.length,d,iszb,iszbs,diszb,gongsi,gs1,gs2,bgsa,lens,blarr,j,val,nams;
		
		if(zb==1){
			ans = this.getxuandoi(o1.name);
			nae = ans[3]; //表单name名称
			nae2= ans[2]; //格式0_0
			iszb= parseFloat(ans[0]);
			iszbs = iszb+1; //第几个子表
		}else{
			nae = o1.name;
		}
		
		for(i=0;i<len;i++){
			d 		= gongsiarr[i];
			gongsi 	= d.gongsi;
			if((gongsi+d.fields).indexOf(nae)<0 || isempt(gongsi))continue;
			diszb   = parseFloat(d.iszb);
			if(diszb==0){
				//主表字段公式计算[{zb0.count}*{zb0.price}] - [{discount}]
				bgsa = this.splitgongs(gongsi);
				lens = bgsa.length;
				gongsi = bgsa[lens-1];
				for(j=0;j<lens-1;j++){
					gs2 = bgsa[j];
					gs1 = this.subtongjisd(gs2);
					if(gs1=='')gs1 = this.zhujisuags(gs2,'','',true);
					gongsi = gongsi.replace(gs2, gs1);
				}
				gongsi = gongsi.replace(/\[/g,'');
				gongsi = gongsi.replace(/\]/g,'');
				this.gongsv(d.fields, gongsi,d.gongsi);
				
			}else if(diszb==iszbs && zb==1){
				this.zhujisuags(gongsi, d.fields, nae2, false);//子表行内计算
			}
		}
		//oninputblur(nae,zb, o1,ans[0],ans[1]);
	},
	splitgongs:function(gongsi){
		if(gongsi.indexOf(']')<0)gongsi = '['+gongsi+']';
		var carr = gongsi.split(']'),i,bd=[],st;
		for(i=0;i<carr.length;i++){
			st = carr[i];
			st = st.substr(st.indexOf('[')+1);
			if(st)bd.push(st);
		}
		bd.push(gongsi);
		return bd;
	},
	zhujisuags:function(gongsi, fid, nae2, blx){
		var blarr,j,nams,val,ogs;
		ogs	  = gongsi+'';
		blarr = this.pipematch(ogs);
		for(j=0;j<blarr.length;j++){
			nams	= ''+blarr[j]+''+nae2+'';
			val 	= form(nams) ? form(nams).value : '0';
			if(val==='')val='0';
			ogs = ogs.replace('{'+blarr[j]+'}', val);
		}
		if(blx)return '('+ogs+')';
		nams	= ''+fid+''+nae2+'';
		return this.gongsv(nams, ogs, gongsi);
	},
	subtongjisd:function(gongsi){
		var str = '',blarr,zb,i,dds,kes,gss,i1;
		if(gongsi.indexOf('zb0.')>-1 || gongsi.indexOf('zb1.')>-1 || gongsi.indexOf('zb2.')>-1){
			blarr = this.pipematch(gongsi);
			zb    = parseInt(blarr[0].split('.')[0].replace('zb',''));//哪个子表
			dds   = this.getsubdata(zb+1);
			for(i=0;i<dds.length;i++){
				gss = gongsi+'';
				for(i1 in dds[i])gss=gss.replace('{zb'+zb+'.'+i1+'}', dds[i][i1]);
				str+= '+('+gss+')';
			}
		}
		if(str!=''){
			str = '('+str.substr(1)+')';
		} 
		return str;
	},
	gongsv:function(ne,vlas,gongss){
		var val = '0',vals,val1;
		if(form(ne)){
			try{
				val = eval(vlas);if(!val)val='0';
				val1= 'a'+val+'';vals= val1.split('.');
				if(vals[1] && vals[1].length>2)val=js.float(val);
				form(ne).value=val;
			}catch(e){
				alert(''+ne+'计算公式设置有错误：'+gongss+'\n\n'+vlas+'');
			}
		}
		return val;
	},
	pipematch:function(str){
		var star = str.match(/\{(.*?)\}/gi),i;
		var b 	 = [];
		if(star)for(i=0;i<star.length;i++){
			b.push(star[i].substr(1, star[i].length-2));
		}
		return b;
	},
	//----公式end -----
	
	
	
	
	//----------以下详情页用到-----------
	initdetail:function(){
		if(ismobile==0){
			$('body').click(function(){
				$('.menullss').hide();
			});
			$('#showmenu').click(function(){
				$('.menullss').toggle();
				return false;
			});
			$('.menullss li').click(function(){
				c.mencc(this);
			});
		}
	},
	changeshow:function(lx){
		$('#showrecord'+lx+'').toggle();
	},
	mencc:function(o1){
		var lx=$(o1).attr('lx');
		if(lx=='4')js.reload();
		if(lx=='0')c.clickprint(false);
		if(lx=='6')c.clickprint(true);
		if(lx=='5')c.daochuword();
		if(lx=='1'){
			js.location('/input/'+cnum+'/'+agenhnum+'/'+mid+'');
		}
	},
	clickprint:function(bo){
		this.hideoth();
		if(bo){
			$('#recordss').remove();
			$('#checktablediv').remove();
			$('#recordsss').remove();
		}
		window.print();
	},
	daochuword:function(){
		
	},
	hideoth:function(){
		$('.menulls').hide();
		$('.menullss').hide();
		$('a[temp]').remove();
	},
	changecheck_status:function(o1){
		var zt = this._getaolvw('check_status');
		if(zt=='1'){
			$('#zhuangdiv').show();
			$('#nextxuandiv').show();
		}else{
			$('#zhuangdiv').hide();
			$('#nextxuandiv').hide();
		}
	},
	check:function(lx){
		js.setmsg();
		changesubmitbefore();
		var da = js.getformdata();
		da.qmimgstr= qmimgstr;
		da.mid= mid;
		da.zt = da.check_status;
		var smlx = '-1';
		if(form('check_explain')){
			smlx = form('check_explainlx').value;
			da.sm = form('check_explain').value;
		}
		if(form('fileid'))da.fileid=form('fileid').value;
		
		if(!da.zt){
			js.setmsg('请选择处理动作');
			return;
		}
		
		var i,len=fieldsarr.length,d;
		if(da.zt=='1')for(i=0;i<len;i++){
			d = fieldsarr[i];
			if(d.isbt==1 && !da[d.fields]){
				js.setmsg(''+d.name+'不能为空');
				if(form(d.fields))form(d.fields).focus();
				return;
			}
		}
		
		//手写签名判断
		if(form('isqmlx')){
			var isqm = form('isqmlx').value;
			var qbp  = true;
			if(isqm=='1' && qmimgstr=='')qbp=false;
			if(isqm=='2' && da.zt=='1' && qmimgstr=='')qbp=false;
			if(isqm=='3' && da.zt=='2' && qmimgstr=='')qbp=false;
			if(!qbp){js.setmsg('此动作必须手写签名');return;}
		}
		
		if(!da.sm){
			if((da.zt=='2' && smlx=='0') || smlx=='1'){
				js.setmsg('此动作必须填写处理说明');
				return;
			}
		}
		
		if(form('zhuanbanname')){
			da.zhuanbanname = form('zhuanbanname').value;
			da.zhuanbannameid = form('zhuanbannameid').value;
		}
		
		if(form('nextname') && !da.zhuanbannameid){
			da.nextnameid 	= form('nextnameid').value;
			da.nextname 	= form('nextname').value;
			if(da.zt=='1' && da.nextnameid==''){
				js.setmsg('下一步审核人必须选择');
				return;
			}
		}

		var ostr=changesubmit(da);
		if(typeof(ostr)=='string'&&ostr!=''){js.setmsg(ostr);return;}
		if(typeof(ostr)=='object')for(var csa in ostr)da[csa]=ostr[csa];
		
		var o1 = get('check_btn');
		o1.disabled = true;
		o1.value = '处理中...';
		js.loading(o1.value);
		var surl = '/api/agent/'+cnum+'/'+agenhnum+'/flow_check';
		js.ajax(surl, da,function(ret){
			c.chuliok(o1, '处理成功');
		},'post',function(msg){
			o1.disabled=false;
			o1.value = '重新提交处理';
			js.loading('none');
			js.setmsg(msg);
		});
	},
	chuliok:function(o1, msg){
		$(o1).remove();
		o1.value = msg;
		js.setmsg(msg,'green');
		js.msgok(msg);
		js.sendmessage('flow-daiban','checkok', msg,function(){
			try{parent.showcallback(msg);}catch(e){}
		});
	},
	_getaolvw:function(na){
		var v = '',i,o=$("input[name='"+na+"']");
		for(i=0;i<o.length;i++)if(o[i].checked)v=o[i].value;
		return v;
	},
	
	//手写签名
	qianming:function(o1){
		this.qianmingbo=false;
		js.tanbody('qianming','请在空白区域写上你的姓名',300,200,{
			html:'<div data-width="280" data-height="120" data-border="1px dashed #cccccc" data-line-color="#000000" data-auto-fit="true" id="qianmingdiv" style="margin:10px;height:120px;cursor:default"></div>',
			btn:[{text:'确定签名'},{text:'重写'}]
		});
		
		$('#qianmingdiv').jqSignature().on('jq.signature.changed', function() {
			c.qianmingbo=true;
		});
		
		get('qianmingdiv').addEventListener('touchmove',function(e){
			e.preventDefault();
		},false);
	
		$('#qianming_btn0').click(function(){
			c.qianmingok();
		});
		$('#qianming_btn1').click(function(){
			$('#imgqianming').remove();
			$('#qianmingdiv').jqSignature('clearCanvas');
			c.qianmingbo = false;
			qmimgstr	 = '';
		});
	},
	qianmingok:function(){
		if(!this.qianmingbo)return;
		$('#imgqianming').remove();
		var dataUrl = $('#qianmingdiv').jqSignature('getDataURL');
		var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
		qmimgstr = dataUrl;
		$('#qianmingshow').append(s);
		js.tanclose('qianming');
	},
	qianyin:function(){
		js.msg('wait','引入中...');
		js.ajax(c.gurl('qianyin'),{},function(a){
			if(a.success){
				js.msg('success', '引入成功');
				$('#imgqianming').remove();
				var dataUrl = a.data;
				var s = '<br><img id="imgqianming" src="'+dataUrl+'"  height="90">';
				qmimgstr = dataUrl;
				$('#qianmingshow').append(s);
			}else{
				js.msg('msg', a.msg);
			}
		},'get,json',function(s){
			js.msg('msg','操作失败');
		});
	},
	onbuwanzhg:function(){
		js.prompt('不完整说明','请输入说明，让申请人从新提交相应资料', function(jg,txt){
			if(jg=='yes' && txt){
				
			}
		});
	}
};