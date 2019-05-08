<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $companyinfo->name }}</title>
<link rel="shortcut icon" href="{{ $companyinfo->logo }}" />
<link href="{{ Auth::user()->bootstyle }}" id="bootstyle" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/jsmanage.js"></script>
<style>


</style>

</head>
<body style="padding:10px;overflow-x:hidden">
<div>我是主题内容</div>
<div style="padding:20px">
<div class="row">
	@foreach($agenharr as $k=>$item)
	<div class="col-md-2 col-xs-6 col-sm-3">
		<div onclick="gotomore('{{ $item->num }}');" align="center" style="cursor:pointer" class="thumbnail">
			<div style="position:relative;width:110px;">
				<div style="padding-top:10px"><img src="{{ $item->face }}" height="50" width="50"></div>
				<div class="caption"><a href="javascript:;">{{ $item->name }}</a></div>
				@if($item->stotal>0) <span class="badge" id="badge_{{ $item->num }}" style="background:red;position:absolute;top:5px;right:5px">{{$item->stotal}}</span>@endif
			</div>
		</div>
	</div>
	@endforeach
	
</div>
</div>
 
<div class="row" style="padding:0px 10px;min-height:400px">
	
	<div>我是主题内容</div>
	
	
	
	<div class="col-md-6 col-sm-6">
		<!-- <div>我是主题内容</div> -->
		@if($noticedata)
		<div align="left"  class="list-group">
			<div class="list-group-item  list-group-item-info">
				<i class="glyphicon glyphicon-volume-up"></i> 通知
				<a style="float:right;cursor:pointer" onclick="gotomore('notice')" >更多&gt;&gt;</a>
			</div>
			@foreach($noticedata['rows'] as $k=>$item)
			<a href="javascript:;" onclick="openxiangzhu('{{ $item->typename }}','notice','{{ $item->id }}')" class="list-group-item" @if($item->isread==1) style="color:#aaaaaa" @endif>○ 【{{ $item->typename }}】{{ $item->title }} ({{ $item->indate }})</a>
			@endforeach
		</div>
		@endif
		
		
		@if($meetdata)
		<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-default">
				<i class="glyphicon glyphicon-glass"></i> 今日会议
				<a style="float:right;cursor:pointer" onclick="gotomore('meet')" >更多&gt;&gt;</a>
			</div>
			@foreach($meetdata['rows'] as $k=>$item)
			<a href="javascript:;" @if($item->stateval==1)style="font-weight:bold" @endif onclick="openxiangzhu('{{ $item->title }}','meet','{{ $item->id }}')" class="list-group-item">○ 【{{ $item->hyname }}】{{ $item->title }}{{ $item->startdtsmall }}({!! $item->state !!})</a>
			@endforeach
		</div>
		@endif
		
		@if($officicdata)
		<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-warning">
				<i class="glyphicon glyphicon-book"></i> 公文查阅
				<a style="float:right;cursor:pointer" onclick="gotomore('officic')" >更多&gt;&gt;</a>
			</div>
			@foreach($officicdata['rows'] as $k=>$item)
			<a href="javascript:;" onclick="openxiangzhu('{{ $item->title }}','officic','{{ $item->id }}')" class="list-group-item" @if($item->isread==1) style="color:#aaaaaa" @endif>○ [{{ $item->num }}]{{ $item->title }} [{{ $item->laidt }}]</a>
			@endforeach
		</div>
		@endif
	</div>
	
	
	
	<div class="col-md-6 col-sm-6">
		
		@if($tododata)
		<div align="left" class="list-group">
			<div class="list-group-item  active">
				<i class="glyphicon glyphicon-bell"></i> 未读提醒({{ $tododata['rowsCount'] }})
				<a style="float:right;cursor:pointer;color:white" onclick="gotomore('todo')" >更多&gt;&gt;</a>
			</div>
			@foreach($tododata['rows'] as $k=>$item)
			<a href="javascript:;" onclick="gotomore('todo')" class="list-group-item" >○ 【{{ $item->typename }}】{!! $item->mess !!} ({{ $item->tododt }})</a>
			@endforeach
		</div>
		@endif
		
		@if($applydata)
		<div align="left" class="list-group">
			<div class="list-group-item  list-group-item-danger">
				<i class="glyphicon glyphicon-align-left"></i> 我的申请({{ $applydata['rowsCount'] }})
				<a style="float:right;cursor:pointer" onclick="gotomore('flow-apply')" >更多&gt;&gt;</a>
			</div>
			@foreach($applydata['rows'] as $k=>$item)
			<a href="javascript:;"  @if(objvalue($item,'ishui')==1) style="color:#aaaaaa" @endif onclick="openxiangzhu('{{ $item->agenhname }}','{{ $item->agenhnum }}','{{ $item->mid }}')" class="list-group-item">○ 【{{ $item->agenhname }}】单号:{{ $item->sericnum }},日期:{{ $item->applydt }}，{!! $item->nowstatus !!}</a>
			@endforeach
		</div>
		@endif
		
	</div>
	
	

	
</div> 
 
 

<script>
var cnum = '{{ $companyinfo->num }}';//获取cnum值
// console.log(cnum);
function gotomore(num){
	var url = '/list/'+cnum+'/'+num+'';
	$('#badge_'+num+'').remove();
	try{
		parent.clickmenusubs(num);
	}catch(e){
		addtabs(num, url);
	}
}
</script>	
<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>
@include('layouts.footer')

</body>
</html>