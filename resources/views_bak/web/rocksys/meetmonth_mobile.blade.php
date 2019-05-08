<script type="text/javascript" src="/res/calendar/jquery-rockcalendar.js"></script>
<script type="text/javascript" src="/res/calendar/jquery-rocklunar.js"></script>
<script type="text/javascript">
yingyonginit = function(){
	gtype = 'my';
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
	if(d.type=='add'){
		js.location('/input/'+cnum+'/meet');
		return;
	}
	if(d.url=='nowmonth')monthobj.nowmonth();
	if(d.url=='prevmonth')monthobj.fanmonth(-1);
	if(d.url=='nextmonth')monthobj.fanmonth(1);
	if(d.url=='all'){
		gtype = 'all';
		yy.loadschedule(yy.month);
	}
	if(d.url=='my'){
		gtype = 'my';
		yy.loadschedule(yy.month);
	}
}
yy.loadschedule=function(mon){
	this.runurl('datameetshow',{'month':mon,'gtype':gtype},function(ret){
		var da = ret.data;
		var d1,s='';
		for(d1 in da){
			s=da[d1];
			if(!s)s='';
			$('#s'+d1+'').html('<div style="border-top:1px #eeeeee solid;margin-top:3px;">'+s+'</div>');
		}
	});
}
</script>