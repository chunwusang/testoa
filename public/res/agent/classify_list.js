var mpid = 0;

function initbodys(){
	paramsquery.pid = mpid;
	paramsquery.num = js.request('num');
}


function optformatter22(value, row, index, field){
	var str = '<a onclick="opentype('+row.id+')" href="javascript:;">打开</a> <a href="javascript:;" onclick="onaddxiaji('+row.id+')">加下级</a>';
	
	return str;
}

function onaddxiaji(pid){
	iframeobj = $.rockmodeliframe('新增分类','/input/'+cnum+'/'+agenhnum+'?def_pid='+pid+'');
}

c.addinput=function(na){
	onaddxiaji(mpid);
}

c.onload = function(da){
	$('#toehwexe').remove();
	var arr = da.lujarr;
	var s = '';
	for(var i=0;i<arr.length;i++){
		if(i>0)s+=' / ';
		s+='<a onclick="opentype('+arr[i].id+')" href="javascript:;" href="javascript:;">'+arr[i].name+'</a>';
	}
	$('#toolbar_menu').append('<span id="toehwexe">'+s+'</span>');
	paramsquery.pid = da.pid;
	if(da.mpid>0)mpid = da.mpid;
	paramsquery.num = '';
}
function opentype(id){
	if(id==0)id = mpid;
	c.search({pid:id});
}

c.onchangemenu=function(d){
	if(d.url=='all')paramsquery.pid = mpid;
	if(d.type=='add'){
		onaddxiaji(paramsquery.pid);
		return false;
	}
	return true;
}

c.ondblclickrow=function(){
	
}

//双击单元格编辑
c.dblclickcell= function(fid,val,row, o1){
	var columns = {};
	columns['sort'] = {"name":"排序号","type":"number"};
	if(!columns[fid]){
		opentype(row.id);
		return;
	}
	$.rockmodelediter({
		'obj'  :o1,
		'fields':fid,
		'agenhnum':agenhnum,
		'chuliid':row.id,
		'columns':columns,
		'saveurl':'2'
	});
}