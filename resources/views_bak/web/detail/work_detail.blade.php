@if(!in_array($data->ztvalue, [1,4,5]) && $isflow==0)
<div align="center" style="padding:10px">
	<div style="max-width:500px">
	<form name="myform">
	<table border="0" width="100%" cellspacing="0" cellpadding="0">
		<tr>
			<td colspan="2"><div align="center" style="background-color:#f1f1f1;line-height:30px">任务评论处理</div></td>
		</tr>
		
		<tr>
			<td class="ys3" nowrap><div align="right" class="ys0div"><font color=red>*</font>状态标记</div></td>
			<td class="ysb" style="padding:8px"><div align="left">{!! $inputobj->showinput('ztvalue','select', $data, $ztvaluestore) !!}</div></td>
		</tr>
		
		<tr>
			<td class="ys3" nowrap><div align="right" class="ys0div"><font color=red>*</font>分配给</div></td>
			<td class="ysb" style="padding:8px"><div align="left">
			{!! $inputobj->showinput('dist,distid','changeusercheck', $data) !!}
			</div></td>
		</tr>
		
		<tr>
			<td class="ys3" nowrap><div align="right" class="ys0div">预计截止</div></td>
			<td class="ysb" style="padding:8px"><div align="left">
			{!! $inputobj->showinput('enddt','datetime', $data) !!}
			</div></td>
		</tr>
		
		<tr>
			<td class="ys3" nowrap><div align="right" class="ys0div">说明</div></td>
			<td class="ysb" style="padding:8px"><div align="left">
			{!! $inputobj->showinput('explain','textarea') !!}
			</div></td>
		</tr>
		
		<tr>
			<td class="ys3" nowrap><div align="right" class="ys0div">相关文件</div></td>
			<td class="ysb" style="padding:8px"><div align="left">
			{!! $inputobj->showinput('fileid','uploadfile') !!}
			</div></td>
		</tr>
		
		<tr>
			<td></td>
			<td>
				<div align="left" style="padding:10px">
				<input id="spage_btn" style="width:100px;border-radius:5px" class="webbtn" onclick="submittijiao(this)" type="button" value="提交">
				&nbsp;<span id="msgview"></span>
				</div>
			</td>
		</tr>
	</table>
	</form>
	</div>	
</div>
<script>
function submittijiao(o1){
	var da = js.getformdata();
	js.setmsg();
	if(da.ztvalue==''){
		js.setmsg('请选择状态');
		return;
	}
	if(da.distid==''){
		js.setmsg('请选择分配给谁');
		return;
	}
	js.loading();
	da.mid = mid;
	o1.disabled=true;
	c.runacturl('chuli', da, function(ret){
		c.chuliok(o1, '处理成功');
	},'post', function(){
		o1.disabled=false;
	});
}
</script>
@endif