function initbodys(){
	changejiaban();
	$(form('jtype')).change(function(){
		changejiaban();
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