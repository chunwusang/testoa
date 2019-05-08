<style>
.progresscls{height:24px;overflow:hidden;line-height:24px;border:0px #eeeeee solid; position:relative;;width:100%;background-color:#f1f1f1;margin-top:3px}
.progressclssse{background-color:#B0D6FC;height:24px;overflow:hidden;width:5%;position:absolute;z-index:0;left:0px;top:0px}
.progressclstext{font-size:10px;color:#0556A8;height:24px;overflow:hidden;line-height:24px;text-align:left;position:absolute;z-index:1;left:5px;top:0px}
.workheader span{float:left;display:block;padding:0px 10px; border-right:1px #dddddd solid;cursor:pointer;}
.workheader span:active{background:#e1e1e1}
</style>
<script type="text/javascript">
var allfq = '{{ $allfq }}';
yingyonginit = function(){
	c.init();
	yy.params.allfqid = allfq;
	changefenqu(false);
	$.getScript('/base/upfilejs');
}
function changefenqu(bo,olx){
	var o  = get('fenqusel');
	var o1 = o.options[o.selectedIndex],o2 = $(o1);
	fqid 	 = o.value;
	yy.params.fqid = fqid;
	if(!olx){
		folderid = '0';
		yy.params.folderid = folderid;
	}
	var sne  = '所有分区&gt;';
	isup 	 = o2.attr('isup');
	isguan 	 = o2.attr('isguan');
	uptype 	 = o2.attr('uptype');
	if(fqid>0){
		sne = ''+o1.text+'';
	}
	qswt = '';
	var ys = 'font-size:12px;color:white;padding:2px;border-radius:2px;background-color:';
	if(isguan=='0'){
		if(isup=='1'){
			qswt = '<font style="'+ys+'#75caeb">仅上传</font> ';
		}else{
			qswt = '<font style="'+ys+'#888888">只读</font> ';
		}
	}else{
		if(isup=='1'){
			qswt = '<font style="'+ys+'green">可管理上传</font> ';
		}else{
			qswt = '<font style="'+ys+'#75caeb">仅管理</font> ';
		}
	}
	$('#workheader').html('<span onclick="changefenqu(true)">'+qswt+''+sne+'</span><font id="showpid0"></font>');
	if(isup=='1'){
		c.disabedgl(false);
	}else{
		c.disabedgl(true);
	}
	c.getfile();
}
var c = {
	init:function(){
		
	},
	getfile:function(){
		yy.getdata('all',1, false);
	},
	data:[],
	showlist:function(d){
		if(!d.id)d.id=d.fileid;
		if(!d.fileext)d.fileext='folder';
		var oi = this.data.push(d),s1='',atr='';
		var s='';
		if(d.isdel==1)atr=';color:#aaaaaa';
		s+='<tr style="height:60px;border-top:1px #f1f1f1 solid'+atr+'" onclick="c.clicksse('+oi+',event,this)" >';
		s1='<div class="r-wrap" id="filename_'+d.id+'">'+d.filename+'</div>';
		if(d.type==0){
			s1+='<div style="font-size:12px;color:#888888">创建者：'+d.optname+'&nbsp;大小：'+d.filesizecn+'';
			if(d.downci>0)s1+='&nbsp;&nbsp;查看：'+d.downci+'';
			s1+='</div>';
		}
	
		s+='<td align="center" width="40">'+d.fileext+'</td><td>'+s1+'</td>';
		s+='<td align="right">';
		if(d.type==1 && d.downci>0)s+='<div style="color:#888888">'+d.downci+'&nbsp;</div>';
		s+='</td>';
		s+='</tr>';
		return s;
	},
	disabedgl:function(lx){
		var clas = 'weui_navbar_item_disabled';
		if(lx){
			$('div[tempxu=0]').addClass(clas);
			$('div[tempxu=2]').addClass(clas);
		}else{
			$('div[tempxu=0]').removeClass(clas);
			$('div[tempxu=2]').removeClass(clas);
		}
	},
	chentcolor:function(oi,e,o1){
		if(this._olodwet)$(this._olodwet).css('background','');
		$(o1).css('background','#f8f8f8');
		this._olodwet = o1;
	},
	clicksse:function(oi,e,o1){
		this.chentcolor(oi,e,o1);
		var d = this.data[oi-1];
		this.tempoi = oi;
		this.tempda = d;
		var a = [];
		if(d.type==0){
			if(d.isdel==0){
				a.push({name:'预览',lx:1});
				a.push({name:'下载',lx:2});
			}
		}else{
			a.push({name:'打开',lx:0});
		}
		if(isguan==1 || d.aid==adminid)a.push({name:'重命名',lx:3});
		if(isguan==1){
			a.push({name:'删除',lx:4});
		}
		js.showmenu({data:a,onclick:function(d){c.clickmenuss(d)}});
	},
	clickmenuss:function(da){
		var lx = da.lx,d = this.tempda;
		if(lx==0)this.openfolder(d.fqid, d.id);
		if(lx==1)yy.openfiles(d.filenum, 0);
		if(lx==2)yy.openfiles(d.filenum, 1);
		if(lx==3)this.rename();
		if(lx==4)this.delfile();
		if(lx==1 || lx==2){
			setTimeout(function(){runurls('adddownci',{sid:d.id})},1000);
		}
	},
	openfolder:function(fid, fq1){
		folderid = fq1;
		yy.params.folderid = folderid;
		get('fenqusel').value = fid;
		changefenqu(true,true);
	},
	delfile:function(){
		js.confirm('确定要删除选中的文件/文件吗？', function(jg){
			if(jg=='yes')runurl('delfile',{sid:c.tempda.id},'post');
		});
	},
	rename:function(){
		var oldname = this.tempda.filename;
		if(this.tempda.isdel==1){
			js.alert('文件已删除了，不需要重命名');
			return;
		}
		js.prompt('重命名','请输入新的名称：',function(jg,txt){
			if(jg=='yes' && txt && txt!=oldname){
				$('#filename_'+c.tempda.id+'').html(txt);
				runurl('editname',{id:c.tempda.id, name:txt},'post');
			}
		},oldname);
	}
}
yy.showdata=function(da){
	$('#showblank').remove();
	var rows = da.rows;
	var i,len=rows.length,s;
	s='<table id="wordlisttable" style="width:100%; background-color:white">';
	for(i=0;i<len;i++){
		s+=c.showlist(rows[i]);
	}
	s+='</table>';
	a = da.pager;
	s+='<div style="margin-top:10px" class="showblank" id="showblank">共'+a.count+'条记录';
	if(a.maxpage>1)s+=',当前'+a.maxpage+'/'+a.page+'页';
	if(a.page<a.maxpage){
		s+=', <a id="showblankss" onclick="yy.nextdata(false)" href="javascript:;">点击加载</a>';
	}
	s+='</div>';
	if(a.page<=1){
		$('#mainbody_show').html(s);
	}else{
		$('#mainbody_show').append(s);
	}
	
	var arr = da.lujarr;
	var s = '';
	for(var i=0;i<arr.length;i++){
		s+='<span onclick="c.openfolder('+arr[i].fqid+', '+arr[i].folderid+')" >';
		if(i==0)s+=qswt;
		s+=''+arr[i].name+'&gt;</span>';
	}
	$('#workheader').html(s);
}

yy.clickevent=function(d){
	if(d.url=='all')upfilestart();
	if(d.url=='createfolder')createfloder();
	if(d.url=='worc')js.location('/ying/'+cnum+'/worc');
}

//创建文件夹
function createfloder(){
	js.prompt('创建文件夹','请输入文件夹名称', function(jg,txt){
		if(jg=='yes' && txt){
			runurl('createfloder',{name:txt,'fqid':fqid,'folderid':folderid});
		}
	});
}

function upfilestart(){
	if(typeof(upobj)=='undefined')upobj = $.rockupfile({
		onsuccess:function(ret){
			ret.fqid = fqid;
			ret.folderid = folderid;
			runurl('savefile',ret);
		},
		onchange:function(f){
			js.loading('上传中['+f.filesizecn+'](<span id="loadss">0%</span>)');
		},
		onprogress:function(f,bl){
			if(!get('loadss'))js.loading('上传中['+f.filesizecn+'](<span id="loadss">0%</span>)');
			$('#loadss').html(''+bl+'%');
		},
		onerror:function(msg){
			js.msgerror(msg);
		}
	});
	upobj.changefile({'uptype':uptype});
}

</script>
<div><select id="fenqusel" onchange="changefenqu(true)" style="width:100%;border:none;height:40px;font-size:16px;padding:5px"><option uptype="" isup="0" isguan="0" value="0">所有分区</option><?php
foreach($fqarr as $k=>$rs){
	echo '<option uptype="'.$rs['uptype'].'" isup="'.$rs['isup'].'" isguan="'.$rs['isguan'].'" value="'.$rs['id'].'">'.$rs['name'].'</option>';
}
?></select></div>
<div class="workheader" id="workheader" style="line-height:50px; background-color:#ffffff;overflow:hidden;border-top:1px #dddddd solid"></div>
<div id="mainbody_show"></div>