<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
<title>401</title>
<script>
function clostback(){
	try{
		parent.iframeobj.close();
	}catch(e){
		history.back();
	}
}
</script>
</head>


<body>


<div align="center">
	<div style="border:0px #cccccc solid;min-width:180px;max-width:300px;padding:20px;margin-top:8%;">
		<div><h2>401</h2></div>
		<div style="font-size:18px">{{ $exception->getMessage() }}</div>
		<div style="padding-top:20px"><a href="javascript:;" onclick="clostback();" class="weui_btn weui_btn_warn">{{ trans('base.close') }}</a> &nbsp;<a href="/users/login?backurl=back" class="weui_btn weui_btn_warn">{{ trans('base.logintext') }}</a></div>
	</div>
</div>

</body>
</html>
