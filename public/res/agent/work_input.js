function initbodys(){
	
}
function changesubmit(da){
	if(da.startdt>=da.enddt && da.enddt)return '预计截止时间不能小于开始时间';
}