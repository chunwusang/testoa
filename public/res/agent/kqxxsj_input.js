
function changesubmit(da){
	if(da.pid>'0' && da.dt==''){
		return '请选择休息日期';
	}
	if(da.pid=='0' && da.name==''){
		return '请输入规则名称';
	}
}