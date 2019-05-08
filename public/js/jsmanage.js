/**
*	添加选择卡
*/
function addtabs(num, url, name){
	var bo;
	try{
		bo = parent.addtabs(num, url, name)
	}catch(e){
		bo = js.location(url);
	}
	return bo;
}

/**
*	打开详情
*/
function openxiangzhu(name,num,mid){
	var bo;
	try{
		bo = parent.openxiangind(name,num,mid);
	}catch(e){
		bo = openxiang(name,num,mid);
	}
	return bo;	
}
function openxiang(name,num,mid,ndbo){
	var bo;
	var url = '/detail/'+cnum+'/'+num+'/'+mid+'';
	if(!ndbo){
		bo = $.rockmodeliframe(name,url);
	}else{
		bo = window.open(url);
	}
	return bo;	
}

/**
*	打开主录入页
*/
function openluzhu(name, num,mid){
	var bo;
	try{
		bo = parent.openluind(name,num,mid);
	}catch(e){
		bo = openlu(name,num,mid);
	}
	return bo;	
}

/**
*	打开录入页
*/
function openlu(name, num, mid, ndbo){
	var url = '/input/'+cnum+'/'+num+'';
	if(mid)url+='/'+mid+'';
	var bo;
	if(!ndbo){
		bo = $.rockmodeliframe(name,url);
	}else{
		bo = window.open(url);
	}
	return bo;
}

/**
*	预览图片
*/
function imgviewzhu(url){
	try{
		parent.imgviewind(url);
	}catch(e){
		$.imgview({url:url,ismobile:false,iconpath:'glyphicon glyphicon'});
	}
	return true;
}

/**
*	打开主页面窗口
*/
function openurlzhu(name,url,op1){
	var bo;
	try{
		bo = parent.openurlind(name,url,op1);
	}catch(e){
		bo = $.rockmodeliframe(name,url,op1);
	}
	return bo;	
}

/**
*	打开文件
*/
function openfiles(num,glx,funs){
	js.loading('处理中...');
	if(!funs)funs=function(){};
	js.ajax('/api/we/file_down/'+cnum+'', {'num':num,'glx':glx},function(ret){
		var da = ret.data;
		if(glx==1){
			js.location(da.upurl);
			return;
		}
		if(glx==0 && da.isimg=='1'){
			imgviewzhu(da.upurl);
		}else{
			openurlzhu(da.filename,da.upurl,{width:'1100px',height:'max'});
		}
		funs(ret);
	},'post');
}