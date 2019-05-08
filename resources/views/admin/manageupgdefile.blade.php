@extends('admin.public')

@section('content')

<div class="container">
	
	<table width="100%"><tr>
		<td style="padding-right:10px"><button type="button" onclick="js.back();" class="btn btn-default">{{ trans('base.back') }}</button></td>
		<td style="padding-right:10px"><button type="button" onclick="c.init();" class="btn btn-success">{{ trans('base.reloadtext') }}</button></td>
		<td id="tdankeys" style="padding-right:10px"><button type="button" onclick="c.setkey();" class="btn btn-info">设置安装key</button></td>
		<td width="100%" id="modeinfo">
	
			
		
		</td>
		<td align="right">
			<button type="button" onclick="c.update();" id="btnup" disabled class="btn btn-danger"><i class="glyphicon glyphicon-arrow-up"></i> 更新选中行文件</button>
		</td>
	</tr></table>
	
	<table  style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th></th>
			<th><input onclick="js.selall(this,'fileid')" type="checkbox" ></th>
			<th>{{ trans('admin/upgde.filetype') }}</th>
			
			<th>{{ trans('admin/upgde.filepath') }}</th>
			<th>{{ trans('admin/upgde.filesize') }}</th>
			<th>{{ trans('admin/upgde.explain') }}</th>
			<th>{{ trans('admin/upgde.zt') }}</th>
			<th>{{ trans('admin/upgde.opt') }}</th>
		</tr>
		<tbody id="showneidiv">
		</tbody>
	</table>
	
	
	

	
</div>
@endsection

@section('script')
<script src="/js/base64-min.js"></script>
<script>
var urly = '{{ config('rock.urly') }}';
function initbody(){
	c.init();
}
var c = {
	init:function(){
		var mode = js.request('mode');
		if(mode==''){
			$.rockmodelmsg('msg','有错误');return;
		}
		this.mode = mode;
		var url = '{{ route('apiadmin','upgde_getfile') }}';
		$.rockmodelmsg('wait', '{{ trans('base.loading') }}');
		js.ajax(url,{'mode':mode},function(ret){
			c.showdata(ret.data);
			$.rockmodelmsg('none');
		},'get',function(msg){
			$.rockmodelmsg('msg', msg);
		});
		
	},
	update:function(){
		if(!this.rs)return;
		var sid = js.getchecked('fileid'),len=0;
		if(sid)len		= sid.split(',').length;
		js.confirm('确定要更新选中的'+len+'个文件吗？如对应文件你有修改，将会被覆盖哦。',function(jg){
			if(jg=='yes'){
				c.updates(sid,'');
			}
		});
	},
	updates:function(sid,kys){
		var sida = sid.split(',');
		js.loading('升级中请不要关闭('+sida.length+'/<span id="xuhao">1</span>)...',200);
		this.sida= sida;
		if(!kys)kys = '';
		this.key= kys;
		this.updatess(0);
	},
	updatess:function(oi){
		var len = this.sida.length
		if(oi>=len){
			js.msgok('升级更新完成');
			setTimeout('c.init()',1000);
			return;
		}
		var ida = this.sida[oi].split('|');
		var id  = ida[0],mid=ida[1];
		var obj = $('#up'+id+'')
		obj.html(js.getmsg('升级中...'));
		$('#xuhao').html(''+(oi+1)+'');
		if(!mid)mid='0';
		js.ajax('/webapi/admin/upgde_upfile',{len:len,oi:oi,uplx:0,fileid:id,key:this.key,mid:mid,zmid:this.rs.id}, function(ret){
			obj.html(js.getmsg('升级完成','green'));	
			c.updatess(oi+1);
		},'post', function(msg){
			js.msgerror(msg);
			obj.html(js.getmsg(msg,'red'));	
		});
	},
	setkey:function(){
		js.prompt('设置安装key','免费模块是不需要设置的，不写点确定即清空。',function(jg,txt){
			if(jg=='yes'){
				c.setkeys(txt);
			}
		});
	},
	setkeys:function(txt){
		js.loading('设置中...');
		js.ajax('/webapi/admin/upgde_setkey',{mode:this.mode,key:txt}, function(ret){
			js.msgok('设置成功');	
		},'post', function(msg){
			js.msgerror(msg);
		});
	},
	updatepix:function(id,sm,o1){
		js.confirm('确定要更新'+sm+'的配置吗？更新就会覆盖你修改的哦',function(jg){
			if(jg=='yes'){
				$(o1).remove();
				c.updatepixs(id,'');
			}
		});	
	},
	updatepixs:function(id){
		var obj = $('#up'+id+'')
		obj.html(js.getmsg('更新中...'));
		js.ajax('/webapi/admin/upgde_upfile',{len:50,oi:1,uplx:1,fileid:id,mid:this.rs.id}, function(ret){
			obj.html(js.getmsg('更新成功','green'));	
		},'post', function(msg){
			obj.html(js.getmsg(msg,'red'));	
		});
	},
	updatdb:function(id,o1){
		js.confirm('确定要更新此模块的数据库嘛？更新数据库不会删除数据，只是对比字段是否一直而已。',function(jg){
			if(jg=='yes'){
				$(o1).remove();
				c.updatdbs(id);
			}
		});	
	},
	updatdbs:function(id){
		var obj = $('#up'+id+'')
		obj.html(js.getmsg('更新中...'));
		js.ajax('/webapi/admin/upgde_upfile',{len:50,oi:1,uplx:2,fileid:id,mid:this.rs.id}, function(ret){
			obj.html(js.getmsg('更新成功','green'));	
		},'post', function(msg){
			obj.html(js.getmsg(msg,'red'));	
		});
	},
	
	showdata:function(da){
		get('btnup').disabled=false;
		var i,len=da.rows.length,s='',s1='',s2='',s3='',s4,s5,d,col,zt1;
		$('#modeinfo').html('【'+da.rs.name+'】');
		this.rs = da.rs;
		if(this.rs.price=='0')$('#tdankeys').hide();
		for(i=0;i<len;i++){
			d = da.rows[i];
			if(isempt(d.explain))d.explain='';
			col = '';
			zt1 = '<font color="red">需更新</font>';
			if(d.state==0){
				col='#aaaaaa';
				zt1='已最新';
			}
			if(d.ishl==1){
				col='#aaaaaa';
				zt1='已忽略';
			}
			s='<tr style="color:'+col+'">';
			s+='<td>'+(i+1)+'</td>';
			if(d.state==1 && d.ishl==0){
				s+='<td><input type="checkbox" value="'+d.id+'|'+d.mid+'" name="fileid"></td>';
			}else{
				s+='<td></td>';
			}
			s4 = '文件';
			s5 = '';
			if(d.type=='10'){
				s4='应用配置';
				s5='<input type="button" onclick="c.updatepix('+d.id+',\''+d.explain+'\', this)" class="btn btn-success btn-xs" value="更新配置" />';
			}
			if(d.type=='1'){
				s4='数据库';
				s5='<input type="button" onclick="c.updatdb('+d.id+',this)" class="btn btn-danger btn-xs" value="更新数据库" />';
			}
			if(d.ishl==1){
				s5+=' <a href="javascript:;" onclick="c.onhulue('+d.id+','+d.mid+',0, this)">取消忽略</a>';
			}else if(d.state==1){
				s5+=' <a href="javascript:;" onclick="c.onhulue('+d.id+','+d.mid+',1, this)">忽略</a>';
			}
			s+='<td>'+s4+'</td>';
			s+='<td>'+d.filepath+'</td>';
			s+='<td>'+d.filesize+'</td>';
			s+='<td>'+d.explain+'</td>';
			s+='<td>'+zt1+'</td>';
			s+='<td><span id="up'+d.id+'"></span>'+s5+'</td>';
			s+='</tr>';
			
			if(d.type=='10'){
				s3+=s;
			}else{
				if(d.state==1){
					s1+=s;
				}else{
					s2+=s;
				}
			}				
		}
		$('#showneidiv').html(s3+s1+s2);
	},
	onhulue:function(id,mid,lx,o1){
		var obj = $(o1).parent()
		obj.html(js.getmsg('处理中...'));
		js.ajax('/webapi/admin/upgde_hulue',{lx:lx,id:id,mid:mid}, function(ret){
			obj.html(js.getmsg('处理成功','green'));	
		},'post', function(msg){
			obj.html(js.getmsg(msg,'red'));	
		});
	}
};
</script>
@endsection