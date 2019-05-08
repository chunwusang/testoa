function initbodys(){
	js.inittabs(); //初始化选择卡
}
var loadbo = [];
js.ontabsclicks=function(ind,tid,o,ho){
	if(!loadbo[ind]){
		if(ind==1 || ind==2 || ind==3 || ind==4 || ind==5){
			var mokui = 'custgen';
			if(ind==2)mokui = 'custorder';
			if(ind==3)mokui = 'custract';
			if(ind==4)mokui = 'custfina';
			if(ind==5)mokui = 'custfinb';
			ho.html('<img src="/images/mloading.gif">');
			c.runacturl('custgen',{mid:mid,mokui:mokui},function(ret){
				ho.html(ret.data);
			});
		}
	}
	loadbo[ind] = true;
}