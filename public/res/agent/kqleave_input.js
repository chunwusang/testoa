
//子表判断
changesubtable=function(i,d){
	if(d.stime>=d.etime)return '请假开始时间不能大于截止时间';
	
	if(d.stime.substr(0,7)!=d.etime.substr(0,7))return '请假时间不允许跨月';
}

//选择日期计算请假时间
function changedatetime(o1){
	var na = o1.name;
	if(na.indexOf('stime')==0 || na.indexOf('etime')==0){
		var start 	= c.getsubvalue(na,'stime');
		var end 	= c.getsubvalue(na,'etime');
		if(start && end)c.runacturl('total', {'start':start,'end':end,'aid':form('aid').value},function(ret){
			var da = ret.data;
			c.setsubvalue(na,'totals', da[0]);
			c.setsubvalue(na,'totday', da[1]);
			c.changegongsi('totals'); //重新处理公式
			c.changegongsi('totday'); //重新处理公式
		},'post');
	}
}

c.onchangeuser=function(fid){
	if(fid=='applyname'){
		c.runacturl('changsheng', {'aid':form('aid').value,'aname':form('applyname').value},function(ret){
			var da = ret.data;
			$('#shengdiv').replaceWith(da);
		},'post');
	}
}