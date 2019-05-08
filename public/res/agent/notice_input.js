//通知录入页面的js
function initbodys(){
	$(form('mintou')).blur(function(){
		mintouchange();
	});
	var val = form('mintou').value;
	if(val==0)c.zbminrow(1,0);
}

function mintouchange(){
	var val = form('mintou').value;
	if(val>0){
		c.getmobj('maxtou').show();
		c.getmobj('toupianitem').show();
		var zs = 2;
		if(val>3)zs = val;
		c.zbminrow(1,zs);
	}else{
		c.getmobj('maxtou').hide();
		c.getmobj('toupianitem').hide();
		c.zbminrow(1,0);//至少行数
	}
}

function changesubmit(da){
	var val = da.mintou;
	if(val>0 && da.startdt==''){
		return '设置投票了必须选择开始时间';
	}
	if(val>0 && da.enddt==''){
		return '设置投票了必须选择截止时间';
	}
}