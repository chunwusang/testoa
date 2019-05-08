<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>{{ $pagetitle }}</title>
<link rel="stylesheet" href="/css/css.css" />
<link rel="stylesheet" href="/res/kindeditor/themes/default/default.css" />
<script type="text/javascript" src="/js/jquery.js"></script>
<script type="text/javascript" src="/js/js.js"></script>
<script type="text/javascript" src="/res/kindeditor/kindeditor-min.js"></script>
<script type="text/javascript">
var id  = '2',adminid='1';
var modenum = 'meet',editor,atype='0';
var content = {!! json_encode($content) !!};
function initbody(){
	resizes();
	$(window).resize(resizes);
	
	var cans  = {
		resizeType : 0,
		allowPreviewEmoticons : false,
		allowImageUpload : true,
		formatUploadUrl:false,
		allowFileManager:true,
		minWidth:'300px',
		items : [
			'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','fontsize','hr',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|','table', 'link','unlink','|','source','clearhtml','fullscreen'
		]
	};
	editor = KindEditor.create('#content', cans);
	editor.html(content[0]);
}
function resizes(){
	var s = winHb();
	$('#page_left').css('height',''+(s-80)+'px');
	$('#page_conent').css('height',''+s+'px');
	$('#content').css('height',''+(s-70)+'px');
}
var subdata={};
var c={
	insert1:function(o1,lx){
		var o  = $(o1).parent();
		var fid = o.attr('fields'),fss = o.attr('fname'),isbt=parseFloat(o.attr('isbt'));
		if(lx==0){
			if(isbt==1)fss='*'+fss+'';
			editor.insertHtml(fss);
		}
		if(lx==1){
			editor.insertHtml('{'+fid+'}');
		}
	},
	save:function(){
		js.msg('wait','保存中...');
		var nr= editor.html();
		nr = nr.replace(/\n/gi,'');
		nr = nr.replace(/[	]/gi,'');
		var d = {content:nr,agentnum:'{{ $agentinfo->num }}',lx:{{ $lx }}};
		js.ajax('/webapi/admin/agent_savebuju',d,function(ret){
			if(ret.success){
				js.msg('success','保存成功');
			}else{
				js.msg('msg', ret.msg);
			}
		},'post');
		return false;
	},
	addmobo:function(){
		var s = '<table width="100%" bordercolor="#000000" border="0"><tbody><tr><td height="34" width="15%" align="right" class="ys1">申请日期</td><td class="ys2" width="35%">{applydt}</td><td align="right" class="ys1" width="15%">操作时间</td><td class="ys2" width="35%">{optdt}</td></tr><tr><td height="34" align="right" class="ys1">说明</td><td colspan="3" class="ys2">{explain}</td></tr><tr><td height="34" align="right" class="ys1">相关文件</td><td colspan="3" class="ys2">{file_content}</td></tr><tr><td  height="34" align="right" class="ys1">申请人</td><td  class="ys2">{base_name}</td><td class="ys1" align="right">申请人部门</td><td  class="ys2">{base_deptname}</td></tr></tbody></table>';
		editor.html(s);
	}
}	
</script>

<style>
select{font-size:12px}
.yangss{height:40px;line-height:40px;background:#e1e1e1;overflow:hidden}
#page_left div{text-align:left;padding:5px 10px;cursor:pointer}
#page_left div:hover{ background-color:#f1f1f1;color:#225DE8}
</style>
</head>
<body>
<div align="center">
<table width="100%">
<tr>
	<td  bgcolor="#f5f5f5">
		<div style="width:270px;">
			<div class="yangss"><h1>&nbsp;{{ $agentinfo->name }}(元素)</h1></div>
			<div id="page_left" style="overflow:auto">
				@foreach($fieldsarr as $k=>$item)
				<div fields="{{ $item->fields }}" fname="{{ $item->name }}" isbt="{{ $item->isbt }}">{{ $k+1 }}. 
				@if($item->isbt==1)<font color="red">*</font>@endif{{ $item->name }}({{ $item->fields }}) 
				<a href="javascript:" onclick="c.insert1(this,0)">＋</a> <a href="javascript:" onclick="c.insert1(this,1)">⊥</a></div>
				@endforeach
			</div>
			<div class="yangss" align="left">&nbsp; &nbsp;<a href="javascript:" onclick="return c.save()" class="webbtn">保存</a>&nbsp; 
			<a href="javascript:" onclick="return c.addmobo()" style="background-color:#888888" class="webbtn">引用模版</a>&nbsp; 
			</div>
		</div>
	</td>
	<td width="100%">
		<div style="overflow:auto" id="page_conent">
			<div style="padding:10px"><textarea style="width:650px;" id="content"></textarea></div>
			<div align="left" style="font-size:12px;padding:0px 10px">注：凡任何软件系统都不可能完美，即使自定义的表单元素，但页面上的交互作用还是需要在开发的。<br>可在{{ $jspath }}来写交互代码，模版保存在：{{ $patsh }}</div>
		</div>
	</td>
</tr>
</table>
	
</div>
</body>
</html>