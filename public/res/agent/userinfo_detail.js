function initbodys(){
	js.inittabs(); //初始化选择卡
}
var loadbo = [];
js.ontabsclicks=function(ind,tid,o,ho){
	if(ind==3 && !loadbo[ind]){
		var aid = o.attr('aid');
		if(aid=='0' || aid==''){
			ho.html('<font color=red>此档案没有关联用户，无法查找对应合同</font>');
			return;
		}
		ho.html('<img src="/images/mloading.gif">');
		c.runacturl('gethetong',{aid:aid},function(ret){
			ho.html(ret.data);
		});
	}
	loadbo[ind] = true;
}