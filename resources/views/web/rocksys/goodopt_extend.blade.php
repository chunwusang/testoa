
<div class="blank20"></div>
<table width="500">
	
	<tr>
	<td align="right" ><font color=red>*</font>类型：</td>
	<td style="padding:5px 0px">
		<select id="opttype" onchange="schangetype(this)" style="width:200px" class="form-control">
		@foreach($typearr as $k1=>$item1)
		<option value="{{ $k1 }}">{{ $item1 }}</option>
		@endforeach
		</select>
	</td>
	</tr>
	
	<tr>
	<td width="120" align="right" ><font color=red>*</font>日期：</td>
	<td style="padding:5px 0px">
		<input readonly style="width:200px" value="{{ nowdt('dt') }}" onclick="js.changedate(this)" class="form-control input_date" id="dt1" >
	</td>
	</tr>
	<tr>
	<td align="right" ><font color=red>*</font><span id="kindstr">入库</span>类型：</td>
	<td style="padding:5px 0px">
		<select id="optkind" style="width:200px" class="form-control"><option value="">-请选择-</option></select>
	</td>
	</tr>
	<tr>
	<td align="right" ><font color=red>*</font>选择仓库：</td>
	<td style="padding:5px 0px">
		<select  id="depotid" style="width:200px" class="form-control">
		<option value="">-请选择-</option>
		@foreach($godepot as $item)
		<option value="{{ $item['value'] }}">{{ $item['name'] }}</option>
		@endforeach
		</select>
	</td>
	</tr>
	<tr>
	<td align="right" >说明：</td>
	<td style="padding:5px 0px">
		<textarea id="explain" class="form-control" style="height:60px"></textarea>
	</td>
	</tr>
	
	<tr>
		<td  align="right"></td>
		<td style="padding:15px 0px" colspan="3" align="left"><button class="btn btn-success" onclick="submitopt()" id="savebtn" type="button"><i class="icon-save"></i>&nbsp;确认提交</button>&nbsp; <span id="msgview"></span>
	</td>
	</tr>
</table>
<div class="blank10"></div>
<script>
var kindarr = {!! json_encode($kindarr) !!},goodmid={{ $goodmid }};
function schangetype(){
	var rk = '入库';
	var olx = get('opttype').value;
	if(olx=='1')rk='出库';
	$('#kindstr').html(rk);
	var o = get('optkind');
	o.length = 1;
	o.value  = '';
	for(var i in kindarr){
		if(i<20 && olx==0)o.options.add(new Option(kindarr[i],i));
		if(i>=20 && olx==1)o.options.add(new Option(kindarr[i],i));
	}
}
function initbodys(){
	schangetype();
}
function submitopt(){
	var obj = $('input[tempid]'),i,len=obj.length,o1,val,sid;
	var adstr = '';
	for(i=0;i<len;i++){
		o1 = $(obj[i]);
		val=o1.val();
		sid= o1.attr('tempid');
		if(val && val>0)adstr+=','+sid+'|'+val+'';
	}
	if(adstr==''){
		js.setmsg('没有选择要出入库数量');
		return;
	}
	var da = {
		'type' : get('opttype').value,
		'dt' : get('dt1').value,
		'kind' : get('optkind').value,
		'depotid' : get('depotid').value,
		'explain' : get('explain').value,
		'adstr' : adstr.substr(1),
		'goodmid' : goodmid
	};
	if(da.dt==''){
		js.setmsg('请选择日期');
		get('dt1').focus();
		return;
	}
	if(da.kind==''){
		js.setmsg('请选择出入库类型');
		get('optkind').focus();
		return;
	}
	if(da.depotid==''){
		js.setmsg('请选择仓库');
		get('depotid').focus();
		return;
	}
	var o1 = get('savebtn');
	o1.disabled=true;
	js.setmsg('处理中...');
	runurls('optgoods', da,'post', function(ret){
		js.msgok(ret.data);
		js.setmsg();
	}, function(msg){
		o1.disabled=false;
		js.setmsg(msg);
	});
}
</script>