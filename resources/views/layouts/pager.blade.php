@if ($pager['total'] > 0)
<div class="row">
	<div class="col-md-8">
	<ul class="pagination" style="margin:0">
		@if($pager['page']<=1)
		<li class="disabled"><a>&laquo;</a></li>
		<li class="disabled"><a>&lt;</a></li>
		@else
		<li><a href="{{ sprintf($pager['url'], 1)}}">&laquo;</a></li>
		<li><a href="{{ sprintf($pager['url'], $pager['lastpage'])}}">&lt;</a></li>
		@endif	
		<li class="disabled"><a>{{ sprintf(trans('base.pagetext'), $pager['page'],$pager['maxpage']) }}</a></li>
		@if($pager['page']>=$pager['maxpage'])
		<li class="disabled"><a>&gt;</a></li>
		<li class="disabled"><a>&raquo;</a></li>
		@else
		<li><a href="{{ sprintf($pager['url'], $pager['nextpage'])}}">&gt;</a></li>
		<li><a href="{{ sprintf($pager['url'], $pager['maxpage'])}}">&raquo;</a></li>
		@endif
	</ul>
	</div>
	<div class="col-md-4" align="right" style="line-height:40px">
		{!! sprintf(trans('base.pagealltext'), $pager['total'],'<input type="number" min="1" value="'.$pager['limit'].'" onkeyup="_onkyeupstr(this,event)" onfocus="js.focusval=this.value" onblur="js.number(this)"  style="width:50px;height:26px;padding:3px;margin:3px">') !!}
	</div>
</div>
<script>
function _onkyeupstr(o,e){
	if(e.keyCode!=13)return;
	var val=o.value;
	if(val==js.focusval || val<1)return;
	var url = '{!! sprintf($pager['url'], 1)!!}&limit='+val+'';
	js.location(url);
}
</script>
@endif