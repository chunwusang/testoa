
function initbodys(){
	
}
c.onselectdata=function(fid,da){
	if(fid=='ordernum'){
		form('custname').value=da.custname;
		form('custid').value=da.custid+'';
	}
}
c.onselectdatabefore=function(fid){
	if(fid=='custname'){
		if(form('ordernum').value)return '关联了订单，不能允许在重新选择';
	}
}

function changesubmit(da){
	if(da.startdt>da.enddt)return '生效日期不能大于截止日期';
}