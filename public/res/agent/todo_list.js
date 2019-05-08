//通知提醒的列表js
function biaoshiyud(){
	var sid = getchecked();
	if(sid==''){
		$.rockmodelmsg('msg','没有选中行');
		return;
	}
	runurl('biaoshiyud',{sid:sid},'post');
}

function deldetebti(){
	var sid = getchecked();
	if(sid==''){
		$.rockmodelmsg('msg','没有选中行');
		return;
	}
	$.rockmodelconfirm('确定要删除选中的提醒记录吗？', function(jg){
		if(jg=='yes')runurl('deldetebti',{sid:sid},'post');
	});
}

c.ondblclickrow=function(row){
	if(!row.agenhnumshow)return true;
	openxiangzhu(row.typename, row.agenhnumshow, row.mid);
	if(row.status.indexOf('未读')>-1)runurls('biaoshiyud',{sid:row.id},'post'); //标识已读
	return false;
}