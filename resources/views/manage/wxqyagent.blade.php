@extends('manage.public')

@section('content')
<div style="padding:0px 15px">
	<div>
		<h3>{{ trans('manage/public.menu.wxqyagent') }}</h3>
		<hr class="head-hr" />
	</div>
    <div>
	
		<table width="100%">
		<tr>
		<td style="padding-right:10px">
			<button type="button" onclick="c.onadd('0')" class="btn btn-default">新增应用</button>
		</td>
		
	
		
		
		<td width="100%"></td>
		<td width="right" nowrap>
			
		</td>
		</tr>
		</table>
		
	</div>	
	
	<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
	<tr>
		<th>应用Logo</th>
		<th>应用名称<i class="glyphicon glyphicon-pencil"></i></th>
		<th>应用Id</th>
		<th>可信任域名<i class="glyphicon glyphicon-pencil"></i></th>
		<th>主页地址<i class="glyphicon glyphicon-pencil"></i></th>
		<th>简介<i class="glyphicon glyphicon-pencil"></i></th>
		<th>可用部门</th>
		<th>排序号<i class="glyphicon glyphicon-pencil"></i></th>
		<th>地理位置上报<i class="glyphicon glyphicon-pencil"></i></th>
		<th>用户进入应用<i class="glyphicon glyphicon-pencil"></i></th>
		<th>关联系统应用</th>
		<th>应用管理</th>
		<th>菜单管理</th>
	</tr>
	@foreach($data as $k=>$item)	
	<tr id="row_{{ $item->id }}">
		<td><img src="{{ $item->square_logo_url }}" width="40"> </td>
		<td edata-fields="name">{{ $item->name }}</td>
		<td>{{ $item->agentid }}</td>
		<td edata-fields="redirect_domain">{{ $item->redirect_domain }}</td>
		<td edata-fields="home_url">{{ $item->home_url }}</td>
		<td edata-fields="description">{{ $item->description }}</td>
		<td>{{ $item->allow_partys }}</td>
		<td edata-fields="sort">{{ $item->sort }}</td>
		<td edata-fields="report_location_flag" edata-value="{{ $item->report_location_flag }}"><img src="/images/checkbox{{ $item->report_location_flag }}.png" height="20">
		</td>
		<td edata-fields="isreportenter" edata-value="{{ $item->isreportenter }}"><img src="/images/checkbox{{ $item->isreportenter }}.png" height="20">
		</td>
		<td>{{ $item->agenhid }}</td>
	
		<td nowrap>
			<a href="javascript:;" onclick="c.geopstee0({{ $item->agentid }})">获取</a> <a href="javascript:;" onclick="c.geopstee1({{ $item->agentid }})">更新</a><br>
			<a href="javascript:;" onclick="c.onadd('{{ $item->id }}','{{ $item->name }}','{{ $item->agentid }}','{{ $item->agenhid }}','{{ $item->secret }}')">编辑</a>
			<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a><br>
			<a href="javascript:;" onclick="c.onsendarr('{{ $item->name }}')">发消息看看</a>
		</td>
		<td nowrap>
			<a href="javascript:;" onclick="c.setmenu({{ $item->id }},'{{ $item->name }}','{{ $item->menujson }}', {{ $item->agentid }})">设置</a> <a href="javascript:;" onclick="c.getmenu({{ $item->agentid }})">获取</a> <br>
			<a href="javascript:;" onclick="c.menuupdate({{ $item->agentid }})">更新</a> <a href="javascript:;" onclick="c.menuclear({{ $item->agentid }})">清空</a>
			@if($item->agenhid>0)
			<br><a href="javascript:;" onclick="c.menuse({{ $item->agentid }})">使用系统应用菜单</a>
			@endif
		</td>
	</tr>
	@endforeach 	
	</table>
</div>
@endsection


@section('script')

<script>

function delconfirm(id){
	$.rockmodeldel({
		delid:id,
		delurl:'/api/unit/'+cnum+'/wxqy_agentdel'
	});
}
function initbody(){
	$("td[edata-fields]").dblclick(function(){
		var columns = {};
		columns['sort'] = {"name":"排序号(越大越靠后)","type":"number"};
		columns['name'] = {"name":"应用名称"};
		columns['redirect_domain'] = {"name":"可信任域名"};
		columns['home_url'] = {"name":"主页地址"};
		columns['description'] = {"name":"简介",type:"textarea"};
		columns['report_location_flag']  	 = {"name":"是否打开地理位置上报","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		columns['isreportenter']  	 = {"name":"是否上报用户进入应用事件","type":"checkbox",
			renderer:function(v, fa){
				return '<img src="/images/checkbox'+v+'.png" height="20">';
			}
		};
		$.rockmodelediter({
			'obj':this,
			'columns':columns,
			'mtable':'{{ $mtable }}',
			'params':{'cnum':cnum}
		});
	});
	var ost = js.getoption('createwxagent');
	if(ost){
		var aost = ost.split(',');
		c.onadd('0',aost[1],'',aost[0],'');
	}
	js.setoption('createwxagent')
}
var c = {
	optchul:function(act,can){
		js.loading();
		js.ajax('/api/unit/'+cnum+'/wxqy_'+act+'', can, function(){
			js.msgok();
			js.reload();
		},'get', function(msg){
			js.msgerror(msg);
		});
	},
	menuupdate:function(id){
		this.optchul('menuupdate',{agentid:id});
	},
	menuclear:function(id){
		this.optchul('menuclear',{agentid:id});
	},
	geopstee0:function(id){
		this.optchul('getagent', {agentid:id});
	},
	geopstee1:function(id){
		this.optchul('setagent', {agentid:id});
	},
	getmenu:function(id){
		this.optchul('getmenu', {agentid:id});
	},
	menuse:function(id){
		this.optchul('menuse', {agentid:id});
	},
	onsendarr:function(name){
		js.prompt('消息内容','请输入消息内容', function(jg,txt){
			if(jg=='yes' && txt)c.optchulss('sendagent', {name:name,msg:txt});
		});
	},
	optchulss:function(act,can){
		js.loading();
		js.ajax('/api/unit/'+cnum+'/wxqy_'+act+'', can, function(ret){
			js.msgok('发送成功');
		},'post', function(msg){
			js.msgerror(msg);
		});
	},
	onadd:function(id,name,tid,hid,sec){
		$.rockmodelinput({
			'title':'添加应用',
			'width':'500px',
			'saveid': id,
			'saveurl':'/api/unit/'+cnum+'/wxqy_agentsave',
			columns:[{
				labelText:'应用名称',name:'name',required:true,repEmpty:true,value:name
			},{
				labelText:'应用ID',name:'agentid',blankText:'从企业微信后台中获取',required:true,repEmpty:true,value:tid
			},{
				labelText:'secret管理密钥',blankText:'从企业微信后台中获取',name:'secret',required:true,type:'textarea',height:80,repEmpty:true,value:sec
			},{
				labelText:'关联系统应用ID',blankText:'可以从应用管理中获取',name:'agenhid',repEmpty:true,value:hid
			}],
			onsuccess:function(){
				setTimeout('js.reload()',1000);
			}
		});
	},
	getagentlist:function(){
		c.optchul('getagentlist', false);
	},
	setmenu:function(id,name,menu,tid){
		var d = {},i,lxs;
		this.id = id;
		this.agentid = tid;
		var str = '<table width="100%"><tr><td align="center"  height="30" nowrap>菜单名称</td><td>菜单类型</td><td>对应值(URL/key)</td></tr>';
		for(i=1;i<=12;i++){
			var lxs = 'style="padding-left:30px"';
			if(i==1 || i==5 || i==9)lxs='';
			str+='<tr><td width="25%" '+lxs+'><input maxlength="8" id="abc_xtname'+i+'" class="form-control"></td><td width="30%"><select class="form-control"  id="abc_xttype'+i+'"><option value=""></option><option value="view">URL地址跳转</option><option value="click">点击推事件</option><option value="scancode_push">扫码推事件</option><option value="pic_sysphoto">弹出系统拍照发图</option><option value="pic_photo_or_album">弹出拍照或者相册发图</option><option value="pic_weixin">弹出企业微信相册发图器</option><option value="location_select">弹出地理位置选择器</option></select></td><td><input id="abc_xturl'+i+'" class="form-control"></td></tr>';
		}
		str+='</table>';
		js.tanbody('wxmenu','设置['+name+']菜单',650,300,{
			html:'<div style="height:460px;overflow:auto;padding:5px">'+str+'</div>',
			btn:[{text:'确定'}]
		});
		$('#msgview_wxmenu').html('3个一级菜单，有凹进去的行是二级菜单');
		if(!menu){
			menu={};
		}else{
			menu=js.decode(menu);
		}
		var btna = menu.button,oi,d1,d2,oj;
		if(btna)for(i=0;i<btna.length;i++){
			oi = i+1;
			if(oi==2)oi=5;
			if(oi==3)oi=9;
			d1 = btna[i];
			get('abc_xtname'+oi+'').value=d1.name;
			get('abc_xttype'+oi+'').value=d1.type;
			get('abc_xturl'+oi+'').value=(d1.url) ? d1.url: d1.key;
			d2 = d1.sub_button;
			if(d2)for(var j=0;j<d2.length;j++){
				oj = oi+1+j;
				d1 = d2[j];
				get('abc_xtname'+oj+'').value=d1.name;
				get('abc_xttype'+oj+'').value=d1.type;
				get('abc_xturl'+oj+'').value=(d1.url) ? d1.url: d1.key;
			}
		}
		$('#wxmenu_btn0').click(function(){
			c.menusetok();
		});
	},
	menusetok:function(){
		var ne,ty,url,str='',es,sustr;
		for(var i=1;i<=12;i++){
			ne = get('abc_xtname'+i+'').value;
			ty = get('abc_xttype'+i+'').value;
			url = get('abc_xturl'+i+'').value;
			if(i==1 || i==5 || i==9)if(ne && ty && url){
				es = (ty=='view')?'url':'key';
				str+=',{"name":"'+ne+'","type":"'+ty+'","'+es+'":"'+this.menugetur(ty,url)+'"';
				sustr = this.menusetoksb(i);
				if(sustr)str+=',"sub_button":'+sustr+'';
				str+='}';
			}
		}

		if(str!=''){
			str='{"button":['+str.substr(1)+']}';
		}else{
			str= '[]';
		}
	
		js.msg('wait','菜单保存中...');
		js.ajax('/api/unit/'+cnum+'/wxqy_savemenu',{id:this.id,menujson:str}, function(s){
			js.msg('success', '菜单设置保存成功，请点[更新]');
			js.reload();
			js.tanclose('wxmenu');
		},'post');
	},
	menugetur:function(ty,url){
		if(ty=='view'){
			if(url.indexOf('http')!=0)url='http://'+url+'';
			if(url.indexOf('agentid')<0){
				var lx = url.indexOf('?')<0 ? '?' : '&';
				url+=''+lx+'agentid='+this.agentid+''
			}
			return url;
		}else{
			return url;
		}
	},
	menusetoksb:function(j){
		var ne,ty,url,str='',es,sustr;
		for(var i=j+1;i<=j+3;i++){
			ne = get('abc_xtname'+i+'').value;
			ty = get('abc_xttype'+i+'').value;
			url = get('abc_xturl'+i+'').value;
			if(ne && ty && url){
				es = (ty=='view')?'url':'key';
				str+=',{"name":"'+ne+'","type":"'+ty+'","'+es+'":"'+this.menugetur(ty,url)+'"}';
			}
		}
		if(str!='')str='['+str.substr(1)+']';
		return str;
	}
};
</script>
@endsection