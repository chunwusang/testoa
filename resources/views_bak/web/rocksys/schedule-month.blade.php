@include("web.list.toolbar")
<div id="mainbody"></div>
<script type="text/javascript" src="/res/calendar/jquery-rockcalendar.js"></script>
<script type="text/javascript" src="/res/calendar/jquery-rocklunar.js"></script>
<script type="text/javascript">
function initbody(){
	yingyonginit();
}
yingyonginit = function(){
	monthobj = $('#mainbody').rockcalendar({
		height:yy.getheight(-2),headerbgcolor:'#dddddd',
		selbgcolor:'#DEF7F2',bordercolor:'#cccccc',
		overShow:false,
		changemonth:function(y, m){
			var dt = ''+y+'年'+xy10(m)+'月';
			yy.settitle(dt);
			yy.month = ''+y+'-'+xy10(m)+'';
			yy.loadschedule(yy.month);
		},
		renderer:function(dt, s, s1,s2,col1,col2){
			return ''+s+'<div style="font-size:12px;padding:5px" id="sshow'+s1+'"></div>';
		}
	});
}
yy.clickevent=function(d){
	if(d.url=='nowmonth')monthobj.nowmonth();
	if(d.url=='prevmonth')monthobj.fanmonth(-1);
	if(d.url=='nextmonth')monthobj.fanmonth(1);
}
yy.loadschedule=function(mon){
	this.runurl('montylist',{month:mon},function(ret){
		var da = ret.data;
		for(var d1 in da)$('#sshow'+d1+'').html(da[d1]);
	});
}
yy.callback=function(){
	this.loadschedule(this.month);
}
function addschedule(){
	yy.openinput('新增日程','schedule');
}
function guanloi(){
	js.location('/list/'+cnum+'/schedule');
}
</script>