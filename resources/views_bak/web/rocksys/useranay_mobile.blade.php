<div>
<style>
body,html{ background:white}
.table td,.table th{height:30px;text-align:center;border:1px #f1f1f1 solid}
</style>
<div class="r-border-b">
<select id="anaytype" onchange="yy.anayfen()" style="width:100%;border:none;height:40px;font-size:16px;padding:5px">
<option value="deptname">部门</option>
	<option value="gender">性别</option>
	<option value="xueli">学历</option>
	<option value="nian">年龄</option>
	<option value="year">入职年份</option>
	<option value="nianxian">入职年限</option>
	<option value="state">人员状态</option>
	<option value="position">职位</option>
</select>
</div>

<div style="margin:10px">

<div class="blank10"></div>

<table width="100%"  class="table">
<thead>
<tr>
	<th width="30" data-field="xuhao"></th>
	<th data-field="name">名称</th>
	<th data-field="value">数值</th>
	<th data-field="bili">比例</th>
</tr>
</thead>
<tbody id="showtable">
</tbody>
</table>

<div id="main_show" style="width:100%;height:500px;margin-top:10px"></div>

</div>



<script>
yingyonginit = function(){
	yy.anayfen();
}
var myChart=false;
yy.anayfen = function(){
	this.runurl('anay', {'type':get('anaytype').value}, function(ret){
		var da = ret.data;
		if(!myChart){
			js.importjs('/res/echarts/echarts.common.min.js', function(){
				yy.loadcharts(da);
			});
		}else{
			yy.loadcharts(da);
		}
	});
}
yy.loadcharts=function(rows){
	var v,len=rows.length;
	var xAxis=[],data=[],s='',d;
	for(i=0;i<len;i++){
		xAxis.push(rows[i].name);
		d = rows[i];
		v = rows[i].value;if(v=='')v=0;
		data.push({value:parseFloat(v),name:rows[i].name});
		s+='<tr><td>'+d.xuhao+'</td><td>'+d.name+'</td><td>'+d.value+'</td><td>'+d.bili+'</td></tr>';
	}
	$('#showtable').html(s)
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

</script>