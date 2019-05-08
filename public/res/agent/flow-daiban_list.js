function initbodys(){
	//注册事件
	js.eventmessage(agenhnum,function(lx){
		if(lx=='checkok'){
			showcallback('审核处理成功');
		}
	});
}

function chulttongy(){
	var sid = getchecked();
	if(sid==''){
		js.msgerror('没有选中行');
		return;
	}
	arrida = sid.split(',');
	js.prompt('处理说明','请输入处理同意说明(选填)', function(jg,txt){
		if(jg=='yes'){
			explain = txt;
			chulttongystart(0);
		}
	});
}
function chulttongystart(oi){
	if(oi>=arrida.length){
		js.msgok('全部处理完成');
		refreshdata();
		return;
	}
	if(!get('chuxu'))js.loading('处理中('+arrida.length+'/<span id="chuxu">1</span>)...');
	$('#chuxu').html(''+(oi+1)+'');
	
	js.ajax('/api/agent/'+cnum+'/'+agenhnum+'/flow_checkpie',{id:arrida[oi],sm:explain},function(ret){
		chulttongystart(oi+1);
		
	},'post', function(msg){
		js.msgerror(msg);
	});
}