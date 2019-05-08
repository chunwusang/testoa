
c.ondblclickrow=function(){
	
}

//双击单元格编辑
c.dblclickcell= function(fid,val,row, o1){
	var columns = {},store=[{"value":"0","name":"否"},{"value":"1","name":"是"}];
	var sfun = function(v){
		var s1 = '';
		if(v==1)s1='<font color=green>√</font>';
		return s1;
	}
	columns['iskq'] = {"name":"是否需要考勤","type":"select","store":store, 'renderer':sfun};
	columns['isdaily'] = {"name":"是否需要写日报","type":"select","store":store, 'renderer':sfun};
	columns['isdwdk'] = {"name":"是否可定位打卡","type":"select","store":store, 'renderer':sfun};
	columns['dkip'] = {"name":"打卡IP,多个,分开,不限制写*","type":"textarea"};
	columns['finger'] = {"name":"考勤机人员编号(为空默认人员ID)","onsavebefore":function(v){
		if(v && isNaN(v)){
			js.msg('msg','考勤机人员编号必须使用数字');
			return false;
		}
	}};
	columns['dkmac'] = {"name":"打卡MAC地址,多个,分开,不限制写*","type":"textarea"};
	var vals = ''+row[fid+'val']+'';
	if(!columns[fid])return;
	if(fid=='dkip' || fid=='dkmac' || fid=='finger')vals='';
	$.rockmodelediter({
		'obj'  :o1,
		'fields':fid,
		'value':vals,
		'agenhnum':agenhnum,
		'chuliid':row.id,
		'columns':columns,
		'saveurl':'2'
	});
}