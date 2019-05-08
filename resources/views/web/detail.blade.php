<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="yes" />
<title>{{ $pagetitle }}</title>
<link rel="stylesheet" type="text/css" href="/css/weui.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/rui.css"/>
@if($ischeck==0)	
<script type="text/javascript" src="/js/jquery.js"></script>
@else
<script type="text/javascript" src="/js/jquery.1.9.1.min.js"></script>	
@endif
<script type="text/javascript" src="/js/jsm.js"></script>
<script type="text/javascript" src="/res/agent/input.js"></script>
<style>
body,html{background:white;font-size:14px}
@if($ismobile==1)
body{font-size:16px}	
@endif
.ys0{border:1px #dddddd solid;text-align:left;}
.ys1{border:1px #dddddd solid;color:#555555;text-align:right;padding:5px}
.ys2{padding:5px; border:1px #dddddd solid;text-align:left;}
.ys3{text-align:left;}
.ysb{width:100%}

.stitle{padding:5px;border-bottom:1px #dddddd solid;}
@media (min-width:450px){.ys0div{width:120px;}}

.pcont{line-height:25px;}
.pcont p{text-indent:2em;margin:5px 0px}
.pcont a{color:blue}

.pinglun td{padding:10px 0px}
.pinglun .dt,.pinglun .act{font-size:12px;color:#888888;padding-top:5px}
.pinglun .name{color:#555555}
.pinglun tr{border-bottom: 1px solid #eeeeee;}
.pinglun .sm{padding-top:8px;font-size:14px}
.faces{height:30px;width:30px;border-radius:50%;margin-right:10px}

.ydullist{display:inline-block;width:100%;}
.ydullist li{float:left;width:60px;text-align:center;padding:5px 0px;font-size:12px;display:block;line-height:25px;padding-top:10px}
.ydullist li:active{ background-color:#eeeeee}
.ydullist li img{height:30px;width:30px;border-radius:50%}
.ydullist li span{font-size:12px;color:#888888;}


.face{height:24px;width:24px;border-radius:50%}

.coursejt{width:4px;height:100%;background:#dddddd;display:inline-block;margin-left:2px;}
.coursejta{height:3px;width:30px;background:#f5f5f5;display:inline-block;}
.coursejts{width:0px;height:0px; overflow:hidden;border-width:4px;border-style:solid;border-color:#dddddd transparent transparent transparent}
.checksm{font-size:12px;color:#555555;padding-top:5px}


.upload_items{border:1px #cccccc solid;height:60px;overflow:hidden;float:left;margin-top:5px;margin-bottom:5px;margin-right:10px;cursor:pointer;position:relative}
.upload_items:active{border:1px #1389D3 solid}
.upload_items img.imgs{width:50px;height:50px;margin:5px}
.upload_items .close{position:absolute;right:2px;top:2px;z-index:5;}
.upload_items_items{padding:5px;text-align:center}
.upload_items_meng{ background:rgba(0,0,0,0.5);position:absolute;left:0px;top:0px;height:60px;overflow:hidden;line-height:60px;text-align:center;width:100%;color:white}

a.webbtn:link,a.webbtn:visited,.webbtn,.btn,.input_btn{color:#ffffff;opacity:0.8; background-color:#1389D3; padding:8px 10px; border:none; cursor:pointer;font-size:14px}
.webbtn:disabled{background-color:#aaaaaa; color:#eeeeee}
.webbtn:hover,.btn:active,.input_btn:active{box-shadow:0px 0px 5px rgba(0,0,0,0.3);opacity:1}
.btn{padding:5px 10px; }

.menulls{position:absolute;left:5px;top:20px;z-index:50}
.menullss{position:absolute;left:5px;top:45px; background-color:white; border:1px #cccccc solid;border-bottom:0px;display:none;z-index:50}
.menullss li{padding:5px 10px;border-bottom:1px #dddddd solid;cursor:pointer}
.menullss li:hover{ background-color:#f1f1f1}


.inputs{height: 36px;line-height:24px; border: 1px #cccccc solid; padding: 0px 2px;border-radius:5px;width:99%;font-size:16px}
.inputsel{width:100%}
.input_btn{padding:3px 10px;height:38px;cursor:pointer;border:none;background:#75c1f0;opacity:0.8;color:white}
.input_btn:hover,.input_btn:active{opacity:1}

.subtable td{border:1px #cccccc solid;padding:5px;text-align:center}
.input_date{background:url(/images/date.png) no-repeat right;cursor:pointer}
.filebg{border:1px #dddddd solid;padding:0px 5px;border-radius:5px;background:#f5f5f5;margin-top:5px;display:inline-block}
	
</style>
<script>
var fieldsarr = {!! json_encode($flowinfo['checkfarr']) !!},isedit=1,mid = {{ $mid }},cnum='{{ $cnum }}',agenhnum='{{ $agenhnum }}',isinput=0,qmimgstr='',ismobile={{ $ismobile }};

function showchayue(opt, op1, st){
	js.alert('总查阅:'+st+'次<br>第一次查阅：'+op1+'<br>最后查阅：'+opt+'');
}

function initbodys(){}
function changesubmit(d){};
function changesubmitbefore(){};

function initbody(){
	c.init();
	initbodys();
}
</script>
</head>
<body>
<div align="center">
<div style="max-width:{{ $bodywidth }};position:relative" align="left">

@if(!$ismobile)
<!--menu-->
<div class="menulls"><a href="javascript:;" id="showmenu" style="background-color:#888888;font-size:12px;border-radius:5px" class="webbtn">操作V</a></div>
<div class="menullss">
<ul>
<li lx="0">打印全部...</li>
<li lx="6">只打印内容...</li>
<li lx="4">刷新</li>
@if($isedit==1)<li lx="1">编辑</li>@endif
</ul>
</div>
@endif

@if($detailtitle)
<div id="headertop">
	<div class="r-header" style="background:none;color:#000000;position:relative">
		<div onclick="js.reload()" id="header_title" class="r-header-text"><b>{{ $detailtitle }}</b></div>
		
		@if($ismobile)
		<span onclick="js.back()" class="r-position-left r-header-btn">
			<div style="height:30px;overflow:hidden;margin-top:9px"><img height="32" src="/images/back.png"></div>
		</span>
		<!--
		<span onclick="js.back()" class="r-position-right r-header-btn">
			<div style="height:30px;overflow:hidden;margin-top:9px"><img height="32" src="/images/jia.png?"></div>
		</span>
		-->
		@endif
	</div>
</div>
@if($ismobile && $showheader==1)
<div class="r-border-t"></div>
@endif
<div class="blank10"></div>	
@endif

<div style="padding:0px 0px">

	<!--cont-->
	<div class="pcont"  style="padding:0px 10px">
	@include($tplpath)
	</div>
	<!--end cont-->
	
	@if($tplpaths)
	@include($tplpaths)
	@endif

	
	<!--log-->
	@if(count($logarr)>0)
	<div id="recordss">	
	<div class="blank10"></div>
	<div class="r-subtitle" onclick="c.changeshow(0)" style="cursor:pointer">处理记录({{ count($logarr) }}) <img align="absmiddle" height="16" width="16" src="/images/xiangyou1.png"> <a temp="clo" href="javascript:;" onclick="$('#recordss').remove();">×</a></div>
	<div class="r-border-t"></div>
	<div id="showrecord0" class="pinglun" style="background:white;display:none;">
	<table width="100%">
	@foreach($logarr as $item)
	<tr>
		<td align="right" valign="top" width="50"><img src="{{ $item->face }}"  onclick="c.imgview(this.src)" class="faces"></td>
		<td >
		<div class="name">{{ $item->checkname }}<span class="act">({{ $item->actname }})</span><font color="{{ $item->color }}">{{ $item->statusname }}</font><span class="dt">{{ $item->optdt }}</span></div>
		@if($item->explain || $item->qmimg)
			<div class="sm">
			@if($item->qmimg)
				<img onclick="c.imgview(this.src)" src="{{ Rock::replaceurl($item->qmimg) }}" height="30" align="absmiddle">
			@endif
			{{ $item->explain }}
			</div>
			@if($item->filestr)
			<div><span class="filebg" style="">{!! $item->filestr !!}</span></div>
			@endif
		@endif
		</td>
	</tr>
	@endforeach
	</table>
	</div>
	</div>
	@endif
	<!--end log-->
	
	
	<!--flow-->
	@if($flowinfo['isflow']>0)
	<div align="center" id="checktablediv">	
	<div style="padding:0px 10px;max-width:600px">
		<div class="blank10"></div>
		
		<div align="center" style="padding-bottom:5px"><b>流程处理</b> <a temp="clo" href="javascript:;" onclick="$('#checktablediv').remove();">×</a></div>
		
		<div align="center">
			<form name="myform">
			<table width="100%" >
			<tr height="40" bgcolor="#E1F4F0">
				<td class="ys1" nowrap><div align="right" class="ys0div">状态</div></td>
				<td class="ys2" width="90%"><div align="left">{!! $flowinfo['nowstatus'] !!}</div></td>
			</tr>
			<tr height="40">
				<td class="ys1" nowrap><div align="right" style="color:#555555">流程信息</div></td>
				<td class="ys2"><div style="padding:5px" align="left">
				@foreach($flowinfo['flowarr'] as $k1=>$carr)
				<div style="padding-bottom:5px;{{ $carr['crs']['style'] }}">{{ $carr['crs']['name'] }}</div>
				@if($carr['crs']['id']!=-999)
				<table height="100%" style="margin-left:20px">
					<tr><td height="100%"><div class="coursejt">&nbsp;</div></td>
					<td style="padding-left:10px">
						@foreach($carr['steparr'] as $k=>$rs)
						<div style="padding:5px 0px;{{ $carr['crs']['style'] }}">
						<table><tr valign="top">
						<td width="30"><img  onclick="c.imgview(this.src)" src="{{ $rs['ars']['face'] }}" class="face"></td>
						<td>
						
						<div>{{ $rs['checkname'] }}({!! $rs['checkstatustext'] !!})</div>
						<div class="checksm">
							@if($rs['checkqmimg'])
								<img onclick="c.imgview(this.src)" src="{{ Rock::replaceurl($rs['checkqmimg']) }}" height="20" align="absmiddle">
							@endif
							{{ $rs['checksm'] }} {{ $rs['checkdate'] }}
						</div>
						
						</td>
						</tr></table>
						</div>
						@endforeach
					</td>
					</tr>
					<tr><td><div class="coursejts"></div></td></tr>
				</table>
				@endif
				@endforeach
				</div></td>
			</tr>
			@if($flowinfo['ischeck']==1)
			@if($nowcourse = $flowinfo['nowcourse'])@endif
			<tr height="40">
				<td class="ys1" nowrap><div align="right" style="color:#555555"><font color=red>*</font>处理动作</div></td>
				<td class="ys2"><div align="left">
					@foreach($flowinfo['checkact'] as $zt=>$ztrs)
					<label><input name="check_status" onclick="c.changecheck_status(this)" type="radio" value="{{ $zt+1 }}">{{ $ztrs['act'] }}</label> &nbsp; 
					@endforeach
					
					@if($nowcourse['id']==0)
					<input value="不完整" onclick="c.onbuwanzhg(this)" class="r-btn" style="background-color:#ff0000;height:30px" type="button">
					@endif
					</div>
				</td>
			</tr>
			
			@if($checkfarr = $flowinfo['checkfarr'])
		
				@include('web.detail.baseinput',['fieldsarr'=>$checkfarr, 'data'=>$ydata,'store'=>$flowinfo['store']])
				
			@endif
			
	
		
			@if($nowcourse['isqm']>0)
			<tr height="40">
				<td class="ys1"><input name="isqmlx" type="hidden" value="{{ $nowcourse['isqm'] }}"><div align="right" style="color:#555555">手写签名</div></td>
				<td class="ys2"><div id="qianmingshow" style="padding:5px 0px" align="left"><input type="button" onclick="c.qianming(this)" style="padding:2px" value="写签名"><!--&nbsp;&nbsp;<input type="button" onclick="c.qianyin(this)" style="padding:2px" value="引用签名">--></div></td>
			</tr>
			@endif
			
			
			@if($nextcourse = $flowinfo['nextcourse'])
			@if($nextcourse['checktype']=='change')
			<tr style="display:none" id="nextxuandiv" height="40">
				<td class="ys1" nowrap><div align="right" style="color:#555555"><font color=red>*</font>下步处理人</div></td>
				<td class="ys2"><div align="left">
				<table width="98%" cellpadding="0" border="0"><tr><td width="100%"><input placeholder="选择下一步[{{ $nextcourse['name'] }}]处理人" class="r-input" style="width:99%" id="change_nextname" readonly type="text" name="nextname"><input name="nextnameid" id="change_nextname_id" type="hidden"></td><td nowrap><a href="javascript:;" onclick="c.changeclear('nextname')" class="webbtn">×</a><a href="javascript:;" onclick="c.changeuser('nextname','changeusercheck','选择下一步处理人','{{ $nextcourse['checktypeid'] }}')"  class="webbtn">选择</a></td></tr></table>
				</div></td>
			</tr>
			@endif
			@endif
			
			@if($nowcourse['checksmlx']!=3)
			<tr>
				<td class="ys1"><div align="right" style="color:#555555">处理说明</div></td>
				<td class="ys2"><div align="left"><input value="{{ $nowcourse['checksmlx'] }}" type="hidden" name="check_explainlx"><textarea class="inputs" name="check_explain" style="height:60px"></textarea></div></td>
			</tr>
			@endif
			
			<tr>
				<td class="ys1">&nbsp;</td>
				<td class="ys2"><div align="left"><input class="r-btn" onclick="c.check(0)" id="check_btn" style="padding:0px 20px" value="提交处理" type="button">&nbsp;<span id="msgview"></span></div></td>
			</tr>
			@endif
			</table>
			</form>
		</div>
	</div>
	</div>
	@endif
	<!--end flow-->
	
	
	<!--read-->
	@if(count($readarr)>0)
	<div id="recordsss">	
	<div class="blank10"></div>
	<div class="r-subtitle" onclick="c.changeshow(1)" style="cursor:pointer">查阅记录({{ count($readarr) }}) <a temp="clo" href="javascript:;" onclick="$('#recordsss').remove();">×</a></div>
	<div class="r-border-t" style="background:white"><ul class="ydullist">
	@foreach($readarr as $k=>$item)
	@if($k<40)
	<li onclick="showchayue('{{ $item->optdt }}','{{ $item->adddt }}',{{ $item->stotal }})"><img src="{{ $item->face }}" align="absmiddle"><br><span>{{ $item->name }}</span></li>
	@endif
	@endforeach
	</ul>
	</div>
	</div>
	@endif
	<!--end read-->
	
</div>

</div>
</div>

@if(($ischeck==1 && $checkfarr) || $tplpaths)
<link rel="stylesheet" href="/res/plugin/jquery-rockdatepicker.css"/>
<script src="/res/plugin/jquery-rockdatepicker.js"></script>
<script src="/res/plugin/jquery-rockdatepicker-mobile.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
@endif

<script src="/res/js/jquery-changeuser.js"></script>
<script src="/res/js/jquery-imgview.js"></script>
<script src="/res/plugin/jquery-signature.js"></script>
@if($jspath!='')
<script src="/{{ $jspath }}"></script>
@endif
</body>
</html>