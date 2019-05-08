function reloadanay(){
	var dt = get('startsou').value;
	if(dt==''){
		js.msgerror('请先选择日期月份');
		return;
	}
	runurl('reloadanay',{'atype':atype,'dt':dt},'get');
}

c.ondblclickrow=function(){
	
}