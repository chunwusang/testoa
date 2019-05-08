function initbodys(){
	var orderid = form('orderid').value;
	if(mid==0 && orderid>0){
		c.runacturl('moneys',{orderid:form('orderid').value},function(ret){
			var da = ret.data;
			form('money').value=da.money;
			form('custname').value=da.custname;
			form('custid').value=da.custid;
			form('ordernum').value=da.ordernum;
		});
	}
}
c.onselectdata=function(fid,da){
	if(fid=='ordernum'){
		form('custname').value=da.custname;
		form('custid').value=da.custid+'';
		form('money').value=da.money+'';
		
		//Ajax读取还剩多少没有生成
		c.runacturl('moneys',{orderid:form('orderid').value},function(ret){
			form('money').value=ret.data.money+'';
		});
	}
}
c.onselectdatabefore=function(fid){
	if(fid=='custname'){
		if(form('ordernum').value)return '关联了订单，不能允许在重新选择';
	}
	//if(mid>0 && form('ordernum').value)return '已保存过的，不允许在切换订单';
}

function changesubmit(da){
	if(da.money<=0)return '金额不能小于0';
	if(da.ispay=='1' && !da.paydt)return '收款时间不能为空';
}