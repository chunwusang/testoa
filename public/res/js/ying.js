/**
*	公共应用主js操作
*/

var yy = {
	init:function(){
		this.showfirstxu();
	},
	showfirstxu:function(){
		var i,len=menuarr.length,xu=-1;
		if(menuarr.length==0)return;
		if(menuarr[0].stotal==0){
			xu = 0;
		}else{
			xu = 1;
		}
		this.clickmenus(xu,true);
	},
	clickmenu:function(xu){
		var d = menuarr[xu];
		if(d.stotal==0)this.clickmenus(xu, false);
	},
	clickmenus:function(xu){
		var d = menuarr[xu],type=d.type;
		if(type=='add'){
			if(!d.url)d.url = agenhnum;
			var url = '/input/'+cnum+'/'+d.url+'';
			js.location(url);
			return;
		}
		if(type=='url'){
			js.location(d.url);
			return;
		}
		var lx = type;
		if(d.url)lx = d.url;
		this.getdata(lx, 1);
	},
	getdata:function(lx,p){
		this.page  = p;
		this.atype = lx;
		js.loading();
		js.ajax('api/ying/data',{page:p,atype:lx,agenhnum:agenhnum,cnum:cnum}, function(ret){
			yy.showdata(ret.data);
		},'get',function(){
			
		});
	},
	nextdata:function(){
		this.getdata(this.atype, this.page+1, true);
	},
	showdata:function(da){
		var i,rows = da.rows;
		var s = '',pager=da.pager;
		
		if(pager.page==1)$('#data_view').html('');
		$('#wujilu').hide();
		if(pager.count>0){
			s = '共'+pager.count+'条记录';
			if(pager.maxpage>1)s+=',当前'+pager.maxpage+'/'+pager.page+'';
			if(pager.maxpage!=pager.page)s+=',<a href="javascript:;" onclick="yy.nextdata()">点击加载</a>';
		}else{
			$('#wujilu').show();
		}
		$('#pager_view').html(s);
		for(i=0;i<rows.length;i++)this.showdatastr(rows[i]);
	},
	showdatastr:function(d){
		var s='';
		s+='<a href="/detail/'+cnum+'/'+agenhnum+'/'+d.id+'" class="weui_media_box weui_media_appmsg weui_media_text">';
		s+='	<div class="weui_media_bd">';
		if(d.title)s+='		<h4 class="weui_media_title">'+d.title+'</h4>';
		s+='		<p class="weui_media_desc">'+d.cont+'</p>';
		s+='		<ul class="weui_media_info">';
		s+='			<li class="weui_media_info_meta">'+d.author+'</li>';
		s+='			<li class="weui_media_info_meta">'+d.optdt+'</li>';
		s+='		</ul>';
		s+='	</div>';
		s+='</a>';
		//<li class="weui_media_info_meta weui_media_info_meta_extra"><font color=red>未读</font></li>
		$('#data_view').append(s);
	}
};