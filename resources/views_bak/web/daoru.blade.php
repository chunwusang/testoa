<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $agenhinfo->name }}{{ trans('base.daorutext') }}</title>
<link href="{{ $userinfo->bootstyle }}" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/jsmanage.js"></script>
<script src="/js/base64-min.js"></script>
</head>
<body>
<div style="padding:10px">
	<div style="font-size:24px;font-weight:bold;padding-bottom:10px">{{ $agenhinfo->name }}{{ trans('base.daorutext') }}</div>
	<div align="left">
	<div>请按照下面表格格式在Excel中添加数据，并复制到下面文本框中，也可以手动输入，<a onclick="c.downxz()" href="javascript:;">[下载Excel模版]</a>。<br>多行代表多记录，整行字段用	分开，<a onclick="c.insrtss()" href="javascript:;">插入间隔符</a></div>
	<div style="padding:5px 0px"><input type="button" id="upbtn" onclick="c.addfile()" class="btn btn-primary" value="选择Excel文件..."></div>
	<div><textarea style="height:250px;" id="maincont" class="form-control"></textarea></div>

	<div style="margin-top:10px;border:0px #cccccc solid" id="showview"></div>
	<div style="padding:10px 0px"><a onclick="c.yulan()" href="javascript:;">[预览]</a>&nbsp; &nbsp; <button class="btn btn-success" onclick="c.saveadd(this)" type="button">确定导入</button>&nbsp; <span id="msgview"></span></div>
	<div class="tishi">请严格按照规定格式添加，否则数据将错乱哦，带<font color=red>*</font>是必填，导入的字段可到单位管理后台下设置，更多可查看<a href="<?=config('rock.urly')?>/view_daoru.html" target="_blank">[帮助]</a>。</div>
	</div>
	

</div>
<script>
var cnum='{{ $companyinfo->num }}',agenhnum='{{ $agenhinfo->num }}';
var fieldsarr = {!! json_encode($fieldsarr) !!};

var c = {
	headers:'',
	init:function(){
		mobjs = $('#maincont');
		mobjs.keyup(function(){
			c.yulan();
		});
		this.initshow(fieldsarr);
	},
	initshow:function(ret){
		this.bitian='';
		this.headers='';
		var i,len=ret.length,d;
		for(i=0;i<len;i++){
			d=ret[i];
			this.headers+='<th>';
			if(d.isbt=='1'){
				this.bitian+=','+d.fields+'';
				this.headers+='<font color=red>*</font>';
			}
			this.headers+=''+d.name+'</th>';
		}
		this.yulan();
	},
	yulan:function(){
		var cont = mobjs.val(),s='',a,a1,i,j,oi=0;
		s+='<table class="table table-striped table-bordered table-hover">';
		s+='<tr><th></th>'+this.headers+'</tr>';
		a = cont.split('\n');
		for(i=0;i<a.length;i++){
			if(a[i]){
				oi++;
				a1 = a[i].replace(/[ ]/g,'').split('	');
				s+='<tr>';
				s+='<td>'+oi+'</td>';
				for(j=0;j<a1.length;j++)s+='<td>'+a1[j]+'</td>';
				s+='</tr>';
			}
		}
		s+='</table>';
		$('#showview').html(s);
	},
	saveadd:function(o1){
		var val = mobjs.val();
		if(isempt(val)){
			js.setmsg('没有输入任何东西');
			return;
		}
		js.setmsg('导入中...');
		o1.disabled=true;
		js.ajax('/api/agent/'+cnum+'/'+agenhnum+'/flow_yunact',{act:'daorudata',importcont:val}, function(ret){
			js.setmsg(ret.data,'green');
		},'post', function(msg){
			js.setmsg(msg);
			o1.disabled=false;
		});
	},
	addfile:function(){
		$.rockmodelmsg('msg','暂不支持选择Excel文件来导入');
	},
	downxz:function(){
		var url = '/daorudown/'+cnum+'/'+agenhnum+'';
		js.location(url);
	},
	insrtss:function(){
		var val = mobjs.val();
		mobjs.val(val+'	');
		mobjs.focus();
	}
};
c.init();
</script>
	<script src="/bootstrap/js/bootstrap.min.js"></script>
	<script src="/res/plugin/jquery-rockmodel.js"></script>
</body>
</html>
