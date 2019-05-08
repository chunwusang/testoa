c.searchparams=function(){
	return {'month':get('soumonth').value};
}

function changekqxxsj(){
	paramsquery.pid 	 = get('fenquselse').value;
	c.search();
}

function weouxiugd1(){
	var pid = get('fenquselse').value;
	var month = get('soumonth').value;
	if(pid==''){
		js.msgerror('请先选择时间规则');
		return;
	}
	iframeobj = openlu('添加休息日','kqxxsj','0?def_pid='+pid+'');
}

function weouxiugd2(){
	var pid = get('fenquselse').value;
	var month = get('soumonth').value;
	if(pid==''){
		js.msgerror('请先选择时间规则');
		return;
	}
	if(month==''){
		js.msgerror('请先选择月份');
		return;
	}
	runurl('weouxiugd2',{pid:pid,month:month},'post');
}

function weouxiugd3(){
	js.msgerror('暂无此接口');
}