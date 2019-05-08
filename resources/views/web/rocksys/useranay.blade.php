@extends("web.list.toolbar")

@section('yingcontent')
<table width="100%">
<tr valign="top">
	<td width="80%">
		<div id="main_show" style="width:100%;height:500px;"></div>
	</td>
	<td>
		<div style="width:350px" id="viewshow">
		
		<table id="table" data-toggle="table" class="table table-striped table-bordered table-hover">
		<thead>
		<tr>
			<th data-field="xuhao"></th>
			<th data-field="name">名称</th>
			<th data-field="value">数值</th>
			<th data-field="bili">比例</th>
		</tr>
		</thead>
		</table>
		
		</div>
	</td>
</tr>
</table>
@endsection

@section('toolbarleft')
<td nowrap>按照&nbsp;</td>
<td>
	<select class="form-control" id="anaytype" style="width:150px;">
	<option value="deptname">部门</option>
	<option value="gender">性别</option>
	<option value="xueli">学历</option>
	<option value="nian">年龄</option>
	<option value="year">入职年份</option>
	<option value="nianxian">入职年限</option>
	<option value="state">人员状态</option>
	<option value="position">职位</option>
	</select>
</td>
<td  style="padding-left:10px">
	<input placeholder="入职日期" onclick="js.datechange(this)" readonly style="width:110px" class="form-control input_date" id="rudt" >
</td>
<td  style="padding-left:10px">
	<button class="btn btn-default" onclick="yy.anayfen()" type="button">分析</button>
</td>
@endsection

@section('script')
@include('layouts.boottable');
@include('layouts.rockdate');
@include('layouts.echarts');
<script>
var myChart=false;
yy.anayfen = function(){
	this.runurl('anay', {'type':get('anaytype').value,'dt':get('rudt').value}, function(ret){
		var da = ret.data;
		$('#table').bootstrapTable('load', da);
		yy.loadcharts(da);
	});
}

yy.loadcharts=function(rows){
	var v,len=rows.length;
	var xAxis=[],data=[];
	for(i=0;i<len;i++){
		xAxis.push(rows[i].name);
		v = rows[i].value;if(v=='')v=0;
		data.push({value:parseFloat(v),name:rows[i].name});
	}
	var o1 = get('anaytype');
	if(!myChart)myChart = echarts.init(get('main_show'));
	var ss = o1.options[o1.selectedIndex].text;
	var option = {
		title: {
			text: '按'+ss+'分析',
			left: 'center'
		},
		tooltip : {
			trigger: 'item',
			formatter: "{b} : {c}人 ({d}%)"
		},
		series: [{
			name: '数值',
			type: 'pie',
			data: data
		}]
	};
	myChart.setOption(option);
}

function initbody(){
	yy.anayfen();
	var hei = winHb()-100;
	if(hei>1000)hei=1000;
	$('#main_show').height(''+hei+'px');
}
</script>
@endsection