
function initbodys(){
	var aid = form('aid').value;
	if(aid=='0'||aid=='')form('name').readOnly=false;
	if(aid>0){
		var fiane = ['position','mobile','deptname','email','tel'];
		for(var i=0;i<fiane.length;i++){
			if(form(fiane[i]))form(fiane[i]).readOnly=true;
		}
	}
}

c.onchangeuserbefore=function(fid){
	if(mid==0){
		return '';
	}else{
		var aid = form('aid').value;
		if(fid=='name'){
			if(aid=='0')return '';
			return '此档案已绑定了用户不能在操作了'
		}
	}
}