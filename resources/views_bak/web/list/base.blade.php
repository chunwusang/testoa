<div id="toolbar">
	<table width="100%">
	<tr>
		<td>
			<a onclick="js.reload()" class="btn btn-info"><img src="{{ $agenhinfo->face }}" align="absmiddle"  height="20" width="20"> {{ $agenhinfo->name }}</a>
		</td>
		@if($ltoption['leftstr'])
		<td style="padding-left:10px">
		{!! $ltoption['leftstr'] !!}
		</td>
		@endif
		<td style="padding-left:10px" nowrap id="toolbar_search">
			<div class="input-group">
				<input style="width:130px" class="form-control" type="text" id="keyword"  placeholder="{{ $keywordmsg }}">
				<div class="input-group-btn">
				<button type="button" onclick="search()" class="btn btn-default" >{{ trans('base.searchbtn') }}</button>
				<!--
				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class="caret"></span></button>
				<ul class="dropdown-menu dropdown-menu-right">
				 <li><a href="javascript:;">高级搜索..</a></li>
				 <li><a href="javascript:;">自定义列..</a></li>
				</ul>
				-->
			  </div>
			</div>
		</td>
		
		@if($menuarr)
		<td style="padding-left:10px"  id="toolbar_menu" width="100%" nowrap>
			<div class="btn-group">
			@foreach($menuarr as $k=>$item)
			@if($item->level==0)
				@if($item->stotal==0 && $item->type!='add')
				<button type="button" onclick="changemenu({{ $k }},{{ $k }})" id="dropMenuid{{ $item->id }}" class="btn btn-default @if($k==0)active @endif">
				@if($item->type=='add')<i class="glyphicon glyphicon-plus"></i> @endif
				{{ $item->name }}
				<span class="badge" id="badge_{{ $item->url }}_{{ $item->id }}">{{ $item->wdtotal }}</span>
				</button>
				@else
				
				@foreach($menuarr as $k1=>$item1)
				@if($item1->pid==$item->id)
				<button type="button" id="dropMenuid{{ $item1->id }}" class="btn btn-default" onclick="changemenu({{ $k1 }},{{ $k1 }})">{{ $item1->name }}
				<span class="badge" id="badge_{{ $item1->url }}_{{ $item1->id }}">{{ $item1->wdtotal }}</span>
				</button>
				@endif
				@endforeach
			
				@endif
			
			@endif
			@endforeach
			</div>
		</td>
		@endif
		
		<td style="padding-left:10px" id="toolbar_center" width="100%" align="left">
			
			
			
		</td>
		<td align="right" id="toolbar_right" nowrap>
		@foreach($ltoption['btnarr'] as $btn)
		<button type="button" @if(isset($btn['attr'])){!! $btn['attr'] !!} @endif style="margin-left:8px" onclick="{{ $btn['click'] }}(this)" class="btn btn-{{ $btn['class'] }}">
		@if(isset($btn['icons']))<i class="glyphicon glyphicon-{{ $btn['icons'] }}"></i>  @endif
		{{ $btn['name'] }}
		</button>
		@endforeach
		
		@if($isdaoru==1)
		<input type="button" onclick="c.ondaoru()" style="margin-left:8px" value="{{ trans('base.daorutext') }}" class="btn btn-default">
		@endif
		</td>
		
		@if($isdaochu==1)
		<td>
		<div class="dropdown">
		<button style="margin-left:10px" type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ trans('base.daochutext') }}<span class="caret"></span></button>
		<ul class="dropdown-menu dropdown-menu-right">
		<li onclick="c.daochunow()"><a href="javascript:;">当页</a></li>
		<li onclick="c.daochuqian()"><a href="javascript:;">前1000条</a></li>
		<li onclick="c.daochuauto()"><a href="javascript:;">自定义...</a></li>
		</ul>
		</div>
		</td>
		@endif
		
		@if($addmenu)
		<td>
		<button style="margin-left:10px" {{ $addmenu->disabled }} type="button" onclick="c.addinput('{{ $addmenu->name }}','{{ $addmenu->url }}')" class="btn btn-primary" ><i class="glyphicon glyphicon-plus"></i> {{ $addmenu->name }}</button>
		</td>
		@endif
	</tr>
	</table>
</div>

<div style="height:10px"></div>

<table id="table" 
data-toggle="table" 
data-height="500" 
data-url="{{ route('listdata', [$cnum, $agenhinfo->num]) }}?bagstr={{ $bagstr }}" 
data-pagination="true"
data-sort-stable="true"
data-side-pagination="server"
data-undefined-text=""
data-page-size="15"
data-cache="false"
data-query-params="tparamsquery"
data-row-style="rowStyle"
class="table table-striped table-bordered table-hover">
<thead>
<tr>
	@if($ltoption['checkcolums'])
	<th data-checkbox="true" data-field="checkcolums"></th>
	@endif
	@foreach($fieldsrows as $item)
		@if($item->islb==1 && $item->status==1 && $item->iszb==0)<th data-field="{{ $item->fields }}" @if($item->ispx==1)data-sortable="true" @endif>{{ $item->name }}</th>@endif
	@endforeach
	@if($ltoption['optcolums'])
	<th data-field="optcolums" data-formatter="optformatter"></th>
	@endif
</tr>
</thead>
</table>

<script>
var menuarr = {!! json_encode($menuarr) !!};
var $table = $('#table'),atype='',agenhnum='{{ $agenhinfo->num }}',agenhname='{{ $agenhinfo->name }}',dataurl='',iframeobj=false,paramsquery={},adminid={{$adminid}},pager={'page':1},pnum='{{ $agenhinfo->pnum }}';
function initbodys(){}

function initbody(){
	c.reheight();
	if(menuarr.length>0){
		var d = menuarr[0];
		if(d.stotal>0){
			d = menuarr[1];
		}
		atype = d.url+'_'+d.id;
	}
	initbodys();
}

function changetab(alx){
	if(atype!=alx)$('#keyword').val('');
	atype= alx;
	runaction('refresh', {'pageNumber':1});
}
function changemenu(i,i1){
	var d = menuarr[i];
	if(!c.onchangemenu(d))return;
	if(d.type=='add'){
		iframeobj = $.rockmodeliframe(d.name,'/input/'+cnum+'/{{ $agenhinfo->num }}');
	}else{
		var lxs = d.url;if(!lxs)lxs = d.type;
		$('button[id^="dropMenuid"]').removeClass('active');
		$('#dropMenuid'+menuarr[i1].id+'').addClass('active');
		changetab(lxs+'_'+d.id);
	}
}
function tparamsquery(cans){
	cans.atype=atype;
	var key,i,ocan,soual,o1;
	key = $('#keyword').val();
	if(key)cans.key= jm.base64encode(key);
	for(i in paramsquery)cans[i]=paramsquery[i];
	soual= $('[data-soukey]');
	for(i=0;i<soual.length;i++){
		o1 = $(soual[i]);
		if(o1.val())cans[o1.data('soukey')]=o1.val();
	}
	ocan = c.searchparams();
	if(ocan)for(i in ocan)cans[i]=ocan[i];
	return cans;
}


$table.on('load-error.bs.table',function(res){
	js.msg('msg', 'load-error');
});

$table.on('dbl-click-row.bs.table',function(e1,row, e){
	c.dblclickrow(row);
});


//数据读取成功处理
$table.on('load-success.bs.table',function(_da, d){
	var bagarr = d.bagarr,i,val;
	for(i in bagarr){
		val = bagarr[i];
		if(val==0)val = '';
		$('#badge_'+i+'').html(val);
	}
	pager 	= d.pager;
	dataurl = d.dataurl;
	c.onload(d);
});

//刷新
function refreshdata(){
	runactions('refresh');
}

//操作列表
function optformatter(value, row, index, field){
	return '<a onclick="showmenu('+index+', this, false);return false;" href="javascript:;">{{ trans('base.opttext') }}</a>';
}

function rowStyle(row, index) {
	var css = {};
	if(row.ishui==1)row.rowcolor='#aaaaaa';
	if(row.isred==1)row.rowcolor='red';
	if(row.isblue==1)row.rowcolor='blue';
	if(row.rowcolor)css.color=row.rowcolor;
	if(row.isbold==1)css['font-weight']='bold';
	if(row.rowbgcolor)css['background']=row.rowbgcolor;
	if(css)return {'css':css};
	return {};
}

//显示菜单
function showmenu(i,o1, bo,sha){
	if(typeof(optmenuobj)=='object')optmenuobj.remove();
	optmenuobj=$.rockmenu({
		data:[],
		itemsclick:function(d){showmenucliek(d,i);},
		width:140
	});
	var da = [{name:'详情',lx:'xiang'}];
	if(!nwjsgui)da.push({name:'详情(新窗口)',lx:'xiango'});
	if(!bo)da.push({name:'<img src="/images/loadings.gif" align="absmiddle"> 加载中...',lx:-1});
	if(bo && sha)for(var i1=0;i1<sha.length;i1++)da.push(sha[i1]);
	var off=$(o1).offset();
	optmenuobj.setData(da);
	setTimeout(function(){optmenuobj.showAt(off.left-120,off.top+20);},10);
	if(bo)return;
	
	var a 	= $table.bootstrapTable('getData')[i];
	var mid = a.id,num = agenhnum;
	if(a.mid && a.agenhnum){
		mid = a.mid;
		num = a.agenhnum;
	}
	var url = '/api/agent/'+cnum+'/'+num+'/data_getoptmenu';
	js.ajax(url,{'mid':mid}, function(ret){
		showmenu(i,o1,true, ret.data);
	});
}


var c = {
	onchangemenu:function(){return true;},
	//打开详情
	openxiang:function(a){
		var num = agenhnum,mid = a.id,name=agenhname;
		if(a.mid && a.agenhnum){
			num = a.agenhnum;
			mid = a.mid;
		}
		if(a.agenhname)name = a.agenhname;
		var url = '/detail/'+cnum+'/'+num+'/'+mid+'';
		iframeobj=openxiangzhu(name,num,mid);
	},
	ondblclickrow:function(){return true},
	onload:function(){},
	dblclickrow:function(row){
		if(!this.ondblclickrow(row))return;
		this.openxiang(row);
	},
	getupgurl:function(str){
		if(str.substr(0,4)=='http')return str;
		var a1 = str.split('|'),lx = a1[0],mk = a1[1],cs=a1[2];
		var url= '';
		if(lx=='add'){
			url='/input/'+cnum+'/'+mk+'';
		}
		if(lx=='xiang'){
			url='/detail/'+cnum+'/'+mk+'/'+cs+'';
			cs = a1[3];
		}
		if(cs)url+='?'+cs;
		return url;
	},
	addinput:function(na,mk){
		if(!mk)mk=agenhnum
		var url='/input/'+cnum+'/'+mk+'';
		iframeobj = $.rockmodeliframe(na,url);
	},
	searchparams:function(){
		return false;
	},
	search:function(can){
		if(!can)can={};
		for(var i in can)paramsquery[i]=can[i];
		runaction('refresh', {'pageNumber':1});
	},
	
	daochuauto:function(){
		var objs = $.rockmodel({
			'loadurl':'/api/agent/'+cnum+'/'+agenhnum+'/data_daofields',
			'title':'自定义导出','type':2,
			'onloadsuccess':function(data){
				var s = '',i,len=data.length,d,sel;
				for(i=0;i<len;i++){
					d = data[i];
					if(d.status==1 && d.iszb==0){
						sel = '';
						if(d.islb==1)sel='checked';
						s+='<label><input name="daochufields" value="'+d.fields+'" '+sel+' type="checkbox">'+d.name+'</label>&nbsp;';
					}
				}
				s+='<br>导出前<input type="number" id="daolimit" style="width:100px" min="1" value="1000">条记录';
				this.setbody(s);
			},
			'onok':function(){
				c.daochu(1,get('daolimit').value);
				return true;
			}
		});

	},
	ondaoru:function(){
		var url = '/daoru/'+cnum+'/'+agenhnum+'';
		addtabs(''+agenhnum+'daoru', url,''+agenhname+'导入');
	},
	daochunow:function(){
		this.daochu(pager.page);
	},
	daochuqian:function(){
		this.daochu(1,1000);
	},
	daochu:function(p,lim){
		var field= js.getchecked('daochufields');
		var durl = dataurl+'&page='+p+'&field='+jm.base64encode(field)+'&daochu=true';
		if(lim)durl+='&limit='+lim+'';
		js.location(durl);
	},
	reheight:function(){
		$table.data('height',winHb()-$('#toolbar').height()-30);
	},
	getacturl:function(act){
		var url= '/api/agent/'+cnum+'/'+agenhnum+'/flow_yunact';
		if(act)url+='?act='+act+'';
		return url;
	}
}

function showmenucliek(d,i){
	var a 	= $table.bootstrapTable('getData')[i];
	var num = agenhnum,mid = a.id,agenhname = '{{ $agenhinfo->name }}';
	if(a.mid && a.agenhnum){
		num = a.agenhnum;
		mid = a.mid;
	}
	if(a.agenhname)agenhname = a.agenhname;
	var lx= d.lx,url = '/detail/'+cnum+'/'+num+'/'+mid+'';
	if(lx=='xiang'){
		iframeobj = openxiangzhu(agenhname,num,mid);
	}
	if(lx=='xiango'){
		window.open(url);
	}
	if(lx=='edit'){
		url = '/input/'+cnum+'/'+num+'/'+mid+'';
		iframeobj = $.rockmodeliframe(''+agenhname+' 编辑',url);
	}
	if(lx=='del'){
		$.rockmodeldel({
			'delurl':'/api/agent/'+cnum+'/'+num+'/data_delbill',delid:mid,
			delparams:{mid:mid},
			ondelok:function(){
				refreshdata();
			}
		});
	}
	
	//单据操作菜单的
	if(lx=='optm'){
		function optmenu(txt){
			js.loading();
			var url = '/api/agent/'+cnum+'/'+num+'/data_optmenu';
			js.ajax(url,{optmid:d.optmid,sm:txt,mid:mid}, function(ret){
				js.msgok(ret.data);
				refreshdata();
			},'post', function(msg){
				js.msgerror(msg);
			});
		}
		//要写说明
		if(d.type==0){
			var sms = (d.issm==1) ? '必填' : '选填';
			$.rockmodelprompt(d.name, '请输入['+d.name+']的说明('+sms+')', function(jg,txt){
				if(jg!='yes')return;
				if(d.issm==1 && !txt){
					js.msgerror('没有输入说明');
					return;
				}
				optmenu(txt);
			});
		}
		//不用写说明
		if(d.type==1){
			optmenu('');
		}
		//打开新窗口
		if(d.type==4){
			var upg = d.upgcont;
			if(isempt(upg)){
				js.msgerror('没有设置打开的操作地址');
			}else{
				iframeobj = $.rockmodeliframe(
					d.name,
					c.getupgurl(upg)
				);
			}
		}
	}
	
	//定时提醒
	if(lx=='remind'){
		url = '/input/'+cnum+'/remind/'+d.remindid+'';
		if(d.remindid==0)url+='?num='+num+'&mid='+mid+'';
		iframeobj = $.rockmodeliframe(d.name,url);
	}
}

function runaction(act, ans){
	return $table.bootstrapTable(act,ans);
}
function runactions(act){
	return $table.bootstrapTable(act);
}
function runurl(act,cans,lx){
	js.loading();
	if(!cans)cans={};
	cans.act = act;
	if(!lx)lx='post';
	js.ajax(c.getacturl(), cans, function(ret){
		var msg = ret.data;
		if(msg.length==0)msg='{{ trans('base.succtext') }}';
		js.msgok(msg);
		refreshdata();
	},lx, function(msg){
		js.msg();
		js.msgerror(msg);
	});
}
function runurls(act,cans,lx, fun1, efun){
	if(!cans)cans={};
	if(!lx)lx='get';
	cans.act = act;
	js.ajax(c.getacturl(), cans,fun1,lx, efun);
}

//搜索
function search(){
	runaction('refresh', {'pageNumber':1});
}

function showcallback(ts){
	try{iframeobj.close();}catch(e){}
	refreshdata();
	$.rockmodelmsg('ok', ts);
}

//获取选中的
function getchecked(fid){
	var arr = runactions('getAllSelections');
	var sid = '',i;
	if(!fid)fid = 'id';
	for(i=0;i<arr.length;i++){
		sid+=','+arr[i][fid]+'';
	}
	if(sid!='')sid = sid.substr(1);
	return sid;
}



</script>


@include('layouts.boottable')
@include('layouts.rockdate')
@if($jspath1)
<script src="/{{ $jspath1 }}"></script>
@endif
@if($jspath)
<script src="/{{ $jspath }}"></script>
@endif


<script>
//双击单元格
if(c.dblclickcell)$table.on('dbl-click-cell.bs.table',function(e1, fid,val,row, e){
	c.dblclickcell(fid,val,row, e[0]);
});
</script>
@if($extendpath)
@include($extendpath)	
@endif