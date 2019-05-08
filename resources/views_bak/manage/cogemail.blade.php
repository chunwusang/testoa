@extends('manage.public')

@section('content')
<style>
.tdinput {
    padding: 5px 0px;
    text-align: left;
    padding-right: 15px;
}
.inputtitle{text-align:center;padding:5px; background:#eeeeee}
</style>
<div class="container" align="center">
    <div align="left" style="max-width:600px">
	
		<div>
			<h3>{{ trans('manage/cog.emailtitle') }}</h3>
			<div>{{ trans('manage/cog.emailtitle_msg') }}</div>
			<hr class="head-hr" />
		</div>


		<table cellspacing="0" width="100%" border="0" cellpadding="0">
		
		<tr>
			<td align="right">邮件服务商：</td>
			<td class="tdinput"><select id="sendfwshan" class="form-control"><option value="">其他自定义</option><option value="teng">腾讯企业邮箱</option><option value="wang">网易企业邮箱</option><option value="ali">阿里企业邮箱</option></select></td>
		</tr>
		
		<tr>
			<td width="150" align="right">SMTP服务器：</td>
			<td class="tdinput"><input id="sendhost" class="form-control"></td>
		</tr>
		
		<tr>
			<td  align="right">SMTP服务器端口：</td>
			<td class="tdinput"><input id="sendport" onfocus="js.focusval=this.value" onblur="js.number(this)" type="number" class="form-control"></td>
		</tr>
		<tr>
			<td align="right">发送方式：</td>
			<td class="tdinput"><select id="sendsecure" class="form-control"><option value="ssl">ssl</option><option value="">默认</option></select></td>
		</tr>
		
		<tr>
			<td  colspan="2"><div align="center" class="alert alert-info">系统发邮件帐号</div></td>
		</tr>
		<tr>
			<td  align="right">名称：</td>
			<td class="tdinput"><input id="sysname" class="form-control"><font color="#888888">用于发送系统邮件的名称</font></td>
		</tr>
		<tr>
			<td  align="right">发邮件邮箱帐号：</td>
			<td class="tdinput"><input id="sysuser" class="form-control"></td>
		</tr>
		<tr>
			<td  align="right">发邮件邮箱密码：</td>
			<td class="tdinput"><input id="syspass" class="form-control">
			</td>
		</tr>
		
		<tr>
			<td  align="right"></td>
			<td class="tdinput"><button click="test" class="btn btn-default" type="button">测试发邮件</button>
			</td>
		</tr>
		
		
		
		
		
		<tr>
			<td  align="right"></td>
			<td style="padding:15px 0px" colspan="3" align="left"><button name="submitbtn" class="btn btn-success" type="button" onclick="saveemeil()"><i class="icon-save"></i>&nbsp;保存</button>&nbsp;{!! c('help')->show('email')	 !!}
			</span>
		</td>
		</tr>
		
		</table>
	

		
	</div>	
</div>
@endsection


@section('script')
<script src="/res/plugin/jquery-rockvalidate.js"></script>

<script>
function saveemeil(){
	js.msgok('待完善');
}
</script>
@endsection