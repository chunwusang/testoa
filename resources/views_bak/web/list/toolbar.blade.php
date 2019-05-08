<script>
var menuarr = {!! json_encode($menuarr) !!},agenhnum='{{ $agenhinfo->num }}',agenhname='{{ $agenhinfo->name }}',iframeobj=false;
var yy = {
	settitle:function(tit){
		$('#toolbarcenter').html(tit);
	},
	clickevent:function(d){
		
	},
	clickmenus:function(oi){
		var d = menuarr[oi];
		this.clickevent(d);
	},
	getheight:function(ss){
		var hei = 30;if(!ss)ss=0;
		if(get('toolbar'))hei+=$('#toolbar').height();
		return $(window).height()-hei+ss;
	},
	runurl:function(act,cans, fun, lxs){
		var url = '/api/agent/'+cnum+'/'+agenhnum+'/flow_yunact';
		js.loading();
		if(!cans)cans={};
		cans.act = act;
		if(!lxs)lxs='get';
		if(!fun)fun=function(){}
		js.ajax(url, cans, function(ret){
			js.unloading();
			fun(ret);
		},lxs, function(msg){
			js.msg();
			js.msgerror(msg);
		});
	},
	openinput:function(name, num, id){
		var url = '/input/'+cnum+'/'+num+'';
		if(id)url+='/'+id+'';
		iframeobj = $.rockmodeliframe(name,url);
	},
	callback:function(){
	}
}
function showcallback(ts){
	iframeobj.close();
	yy.callback();
	$.rockmodelmsg('ok', ts);
}
</script>
<div id="toolbar">
	<table width="100%">
	<tr>
		<td>
		<a onclick="js.reload()" style="margin-right:10px" class="btn btn-info"><img src="{{ $agenhinfo->face }}" align="absmiddle"  height="20" width="20"> {{ $agenhinfo->name }}</a>
		</td>
		
		@yield('toolbarleft')

		@foreach($menuarr as $k=>$item)
		@if($item->stotal==0)
		<td style="padding-left:10px">
			<button type="button" class="btn btn-default" onclick="yy.clickmenus({{ $k }})" >{{ $item->name }}</button>
		</td>
		@endif
		@endforeach
		<td style="padding-left:10px" id="toolbarcenter" align="left" width="100%">
			
		</td>
		<td align="right" id="toolbarright" nowrap>
		
		@foreach($ltoption['btnarr'] as $btn)
		<button type="button" style="margin-left:8px" onclick="{{ $btn['click'] }}(this)" class="btn btn-{{ $btn['class'] }}">
		@if(isset($btn['icons']))<i class="glyphicon glyphicon-{{ $btn['icons'] }}"></i>  @endif
		{{ $btn['name'] }}
		</button>
		@endforeach
		
		</td>
	</tr>
	</table>
</div>
<div style="height:10px;overflow:hidden"></div>
@yield('yingcontent')