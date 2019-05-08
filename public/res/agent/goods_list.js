function fenquguan(){
	var url = '/list/'+cnum+'/classify?num=goodstype';
	js.location(url);
}

c.searchparams=function(){
	return {'classid':get('souclassid').value};
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

function opentype(id){
	get('souclassid').value = id+'';
	c.search();
}