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
		changemonth:function(y, m){
			var dt = ''+y+'年'+xy10(m)+'月';
			yy.settitle(dt);
		},
		align:'center',
		valign:'center',
		renderer:function(day, s, s1,s2,col1,col2){
			var s = '<div><font color='+col1+'>'+s1+'</font><br><div style="font-size:11px;height:16px;overflow:hidden;color:'+col2+'">'+s2+'</div></div>';
			return s;
		}
	});
}
yy.clickevent=function(d){
	if(d.url=='nowmonth')monthobj.nowmonth();
	if(d.url=='prevmonth')monthobj.fanmonth(-1);
	if(d.url=='nextmonth')monthobj.fanmonth(1);
}
</script>