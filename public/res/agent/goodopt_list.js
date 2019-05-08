function shuaxinstock(){
	runurl('reloadstock',false, 'get');
}

c.searchparams=function(){
	var gids = js.request('goodmid');
	return {'classid':get('souclassid').value,'goodmid':gids};
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

c.reheight=function(){
	$table.data('height',450);
}

c.ondblclickrow=function(){
	
}

c.onchangemenu=function(d){
	if(d.url=='daichurku'){
		
		var objs = $.rockmodel({
			'loadurl':c.getacturl('optbill'),
			'title':'选择要操作的单据','type':0,'bodyheight':'300px','width':'600px',
			'okbtn':'取消','bodypadding':'1px',
			'onloadsuccess':function(data){
				var s='<table class="table table-bordered" id="showtable"></table>';
				this.setbody(s);
				$('#showtable').bootstrapTable({
					data:data,
					columns:[{
						field:'type',
						title:'单据类型'
					},{
						field:'applydt',
						title:'申请日期'
					},{
						field:'explain',
						title:'说明'
					},{
						field:'optname',
						title:'操作人'
					},{
						field:'state',
						title:'出入库状态'
					},{
						field:'xxx',
						title:'',
						formatter:function(v, d){
							return '<a href="/detail/'+cnum+'/'+d.agenhnum+'/'+d.id+'" target="_blank">详情</a>';
						}
					},{
						field:'opt',
						title:'',
						formatter:function(v, d){
							return '<a class="btn btn-info btn-sm" href="/list/'+cnum+'/goodopt?goodmid='+d.id+'">选择</a>';
						}
					}]
				});
			},
			'onok':function(){
				return true;
			}
		});

		
		return false;
	}
	return true;
}