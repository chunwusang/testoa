<script type="text/javascript" src="/res/calendar/jquery-rockcalendar.js"></script>
<script type="text/javascript" src="/res/calendar/jquery-rocklunar.js"></script>
<script type="text/javascript">
yingyonginit = function(){
	monthobj = $('#mainbody').rockcalendar({
		height:yy.getheight(-2),headerbgcolor:'#dddddd',
		selbgcolor:'#DEF7F2',
		overShow:false,
		bordercolor:'#cccccc',
		changemonth:function(y, m){
			var dt = ''+y+'年'+xy10(m)+'月';
			yy.settitle(dt);
			yy.month = ''+y+'-'+xy10(m)+'';
			yy.loadschedule(yy.month);
		},
	
		renderer:function(dt, s, s1,s2,col1,col2){
			return ''+s+'<div style="font-size:12px;padding:5px" id="s'+dt+'"></div>';
		}
	});
}
yy.clickevent=function(d){
	if(d.url=='nowmonth')monthobj.nowmonth();
	if(d.url=='prevmonth')monthobj.fanmonth(-1);
	if(d.url=='nextmonth')monthobj.fanmonth(1);
}
yy.loadschedule=function(mon){
	this.runurl('myanaykq',{month:mon,aid:js.request('aid')},function(ret){
		var da = ret.data;
		var d1,s='';
		for(d1 in da){
			s=da[d1];
			if(s!='')$('#s'+d1+'').html('<div style="border-top:1px #eeeeee solid;margin-top:3px;">'+s+'</div>');
		}
		s='';var toarr = da['total'];
		for(d1 in toarr)s+='，'+d1+':'+toarr[d1]+'';
		if(s!='')s=s.substr(1);
	});
}
</script>