function initbodys(){
	changejiaban();
	$(form('jtype')).change(function(){
		changejiaban();
	});
	
	$(form('startdt')).blur(function(){
		changetotals();
	});
	$(form('enddt')).blur(function(){
		changetotals();
	});
}

//加班切换
function changejiaban(){
	if(!form('jtype') || !form('jiafee'))return;
	var val = form('jtype').value;
	if(val=='1'){
		c.getmobj('jiafee').show();
	}else{
		c.getmobj('jiafee').hide();
		form('jiafee').value='0';
	}
}

//统计时间
function changetotals(){
	var dt1 = form('startdt').value;
	var dt2 = form('enddt').value;
	if(!dt1 || !dt2)return;
	c.runacturl('gettotals',{dt1:dt1,dt2:dt2},function(ret){
		form('totals').value=ret.data.totals;
	},'post');
}