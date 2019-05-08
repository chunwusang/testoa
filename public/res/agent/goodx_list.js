c.searchparams=function(){
	return {'classid':get('souclassid').value,'month':get('soumonth').value};
}

c.onload = function(da){
	$('#toehwexe').remove();
	var arr = da.lujarr;
	var s = '';
	for(var i=0;i<arr.length;i++){
		if(i>0)s+=' / ';
		s+='<a onclick="opentype('+arr[i].id+')" href="javascript:;" href="javascript:;">'+arr[i].name+'</a>';
	}
	$('#toolbar_menu').append('<span id="toehwexe">'+s+'</span>');
}


c.ondblclickrow=function(){
	
}