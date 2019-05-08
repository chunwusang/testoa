
function fenquguan(){
	var url = '/list/'+cnum+'/worc';
	js.location(url);
}
function initbodys(){
	paramsquery.allfqid = get('allfqid').value; //设置分区
	changefenqu(false);
	$.getScript('/base/upfilejs');
}
function changefenqu(bo,olx){
	var o  = get('fenqusel');
	var o1 = o.options[o.selectedIndex],o2 = $(o1);
	fqid 	 = o.value;
	paramsquery.fqid 	 = fqid;
	
	if(!olx){
		folderid = '0';
		paramsquery.folderid = folderid;
	}
	var sne  = '所有分区';
	isup 	 = o2.attr('isup');
	isguan 	 = o2.attr('isguan');
	uptype 	 = o2.attr('uptype');
	if(fqid>0){
		sne = ''+o1.text+'';
	}
	var qswt = '';
	if(isguan=='0'){
		if(isup=='1'){
			qswt = '<span class="label label-info">仅上传</span> ';
		}else{
			qswt = '<span class="label label-default">只读</span> ';
		}
	}else{
		if(isup=='1'){
			qswt = '<span class="label label-success">可管理上传</span> ';
		}else{
			qswt = '<span class="label label-info">仅管理</span> ';
		}
	}
	$('#toolbar_center').html(''+qswt+'<span id="showpid0"><a href="javascript:;" onclick="changefenqu(true)">'+sne+'</a></span>');
	if(isup=='1'){
		get('folderbtn').disabled=false;
		get('upbtn').disabled=false;
		get('delbtn').disabled=false;
	}else{
		get('folderbtn').disabled=true;
		get('upbtn').disabled=true;
		get('delbtn').disabled=true;
	}
	if(isguan=='1'){
		get('delbtn').disabled=false;
	}else{
		get('delbtn').disabled=true;
	}
	
	if(bo)c.search();
}

function optformatter(value, row, index, field){
	var str = '<a onclick="openfloder('+row.fqid+', '+row.id+', '+row.folderid+',\''+row.filename+'\')" href="javascript:;">打开</a>';
	if(row.type=='0'){
		if(row.isdel==1){
			str= '已删除';
		}else{
			str = '<a onclick="c.openfiles(\''+row.filenum+'\',0,'+row.id+')" href="javascript:;">预览</a><a onclick="c.openfiles(\''+row.filenum+'\',1,'+row.id+')" href="javascript:;"><i class="glyphicon glyphicon-arrow-down"></i></a>';
		}
	}
	return str;
}
c.ondblclickrow=function(){
	
}
c.onload = function(da){
	var arr = da.lujarr;
	var s = '';
	for(var i=0;i<arr.length;i++){
		if(i>0)s+=' / ';
		s+='<a onclick="openfloder('+arr[i].fqid+', '+arr[i].folderid+')" href="javascript:;" href="javascript:;">'+arr[i].name+'</a>';
	}
	$('#showpid0').html(s);
}
c.openfiles=function(num,lx,sid){
	openfiles(num,lx, function(ret){
		if(ret.success)setTimeout(function(){runurls('adddownci',{sid:sid})},1000);
	});
}


//创建文件夹
function createfloder(){
	js.prompt('创建文件夹','请输入文件夹名称', function(jg,txt){
		if(jg=='yes' && txt){
			runurl('createfloder',{name:txt,'fqid':fqid,'folderid':folderid});
		}
	});
}

//打开文件夹
function openfloder(fid,foid,pid,na){
	folderid = foid;
	paramsquery.folderid = folderid;
	get('fenqusel').value = fid;
	changefenqu(true,true);
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

function deldetebti(){
	if(isguan!='1')return;
	var sid = getchecked();
	if(sid==''){
		$.rockmodelmsg('msg','没有选中行');
		return;
	}
	js.confirm('确定要删除选中的文件/文件吗？', function(jg){
		if(jg=='yes')runurl('delfile',{sid:sid},'post');
	});
}

//双击单元格编辑
c.dblclickcell= function(fid,val,row, o1){
	var columns = {};
	columns['filename'] = {"name":"名称"};
	columns['sort'] = {"name":"排序号","type":"number"};
	if(!columns[fid] || row.isdel==1)return;
	if(row.aid != adminid && isguan=='0')return;
	$.rockmodelediter({
		'obj'  :o1,
		'fields':fid,
		'agenhnum':agenhnum,
		'chuliid':row.id,
		'columns':columns,
		'saveurl':'2'
	});
}