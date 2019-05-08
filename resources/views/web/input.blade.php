<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>{{ $pagetitle }}</title>
<link rel="stylesheet" type="text/css" href="/css/weui.min.css"/>
<link rel="stylesheet" type="text/css" href="/css/rui.css"/>
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/jsm.js"></script>
<script type="text/javascript" src="/res/agent/input.js"></script>
<style>
body,html{background:white}

.ys0{text-align:left;}
.ys1{color:#555555;text-align:right;padding:0px 5px;}
.ys2{padding:10px 5px;padding-right:10px;text-align:left;}
.ysb{width:100%;}


.weui_btn:disabled{background:#aaaaaa}
.r-border:hover:after{border-color:#1389D3}
.r-border:after{border-radius:5px}


.upload_items{border:1px #cccccc solid;height:60px;overflow:hidden;float:left;margin-top:5px;margin-bottom:5px;margin-right:10px;cursor:pointer;position:relative}
.upload_items:active{border:1px #1389D3 solid}
.upload_items img.imgs{width:50px;height:50px;margin:5px}
.upload_items .close{position:absolute;right:2px;top:2px;z-index:5;}
.upload_items_items{padding:5px;text-align:center}
.upload_items_meng{ background:rgba(0,0,0,0.5);position:absolute;left:0px;top:0px;height:60px;overflow:hidden;line-height:60px;text-align:center;width:100%;color:white}


.inputs{height: 36px;line-height:24px; border: 1px #cccccc solid; padding: 0px 2px;border-radius:5px;width:99%;font-size:16px}
.inputsel{width:100%}
.input_btn{padding:3px 10px;height:38px;cursor:pointer;border:none;background:#75c1f0;opacity:0.8;color:white}
.input_btn:hover,.input_btn:active{opacity:1}

.subtable .zbys0{border:1px #cccccc solid;padding:5px;text-align:center}
.subtable .zbys1{border:1px #cccccc solid;padding:5px;text-align:center}
.subtable .inputs{border-radius:0;border:none;background:none;height:30px}
.subtable .input_btn{height:30px}

.input_date,.subtable .input_date{background:url(/images/date.png) no-repeat right;cursor:pointer}


@if(!$inputobj)
.ys0,.ys1,.ys2{border:1px #dddddd solid;}
.ys2{padding:5px}	
.inputs{width:97%}
@endif
</style>
<script>
var fieldsarr = {!! json_encode($fieldsarr) !!},mid = {{ $mid }},isedit = {{ $isedit }},cnum='{{ $cnum }}',agenhnum='{{ $agenhnum }}',ismobile={{ $ismobile }},isinput=1;
var data = {!! json_encode($data) !!},filelist = {!! json_encode($filelist) !!};

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

@if ($showheader==1)
<div id="headertop">
	<div class="r-header" style="background:none;color:#000000;position:relative">
		<div onclick="js.reload()" id="header_title" class="r-header-text"><b>{{ $pagetitle }}</b></div>
		@if($ismobile==1)
		<span onclick="js.back()" class="r-position-left r-header-btn">
			<div style="height:30px;overflow:hidden;margin-top:9px"><img src="/images/back.png"></div>
		</span>
		@endif
	</div>
</div>
@endif


<div  align="center">
<div style="max-width:750px;" align="left">

	<form name="myform">
	<input name="isturn" type="hidden" value="1">
	
	@if($inputobj)
	<table style="width:100%;">
		@if($mid==0)
		<tr>
		<td class="ys1">填写人</td>
		<td class="ys2">{{ $useainfo->deptname }}/{{ $useainfo->name }}</td>
		</tr>
		@endif
		
		@include('web.detail.baseinput',['fieldsarr'=>$fieldsarr, 'data'=>$data,'store'=>$store])

	</table>
	@else
	{!! $inputcontent !!}	
	@endif
	
	
	</form>


	<div style="height:60px"></div>
</div>
</div>

<div align="right" style="background:#eeeeee;height:50px;position:fixed;width:100%;bottom:0px;left:0px;border-top:1px #cccccc solid">
	<div style="line-height:50px;">
	@if($isedit==1)
		@if($turnbool)	
		<label id="isturnlabel"><input type="checkbox" checked onclick="c.changeturn(this)" value="1">直接提交</label>
		@endif
		<span onclick="$(this).html('')" id="msgview"></span>&nbsp;&nbsp;
		<button style="width:90px;font-size:16px" class="r-btn" type="button" onclick="c.save()" id="AltS">提交</button>
	@else
		<font color="red">当前单据不允许编辑的</font>，完整格式请用<a href="/detail/{{ $cnum }}/{{ $agenhnum }}/{{ $mid }}">详情页</a>查看。
	@endif
	&nbsp;
	</div>
</div>

<link rel="stylesheet" href="/res/plugin/jquery-rockdatepicker.css"/>
<script src="/res/plugin/jquery-rockdatepicker.js"></script>
<script src="/res/plugin/jquery-rockdatepicker-mobile.js"></script>
<script src="{{ config('rock.baseurl') }}/?m=upfilejs"></script>
<script src="/res/js/jquery-changeuser.js"></script>
<script src="/res/js/jquery-imgview.js"></script>

<link rel="stylesheet" href="/res/kindeditor/themes/default/default.css" />
<script src="/res/kindeditor/kindeditor-min.js"></script>

@if($jspath)
<script src="/{{ $jspath }}"></script>
@endif

</body>
</html>