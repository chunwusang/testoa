<div class="r-tabs" tabid="a">
	<div index="0" class="r-tabs-item active">
	客户资料
	</div>
	<div index="1" class="r-tabs-item">
	跟进记录
	</div>
	<div index="2" class="r-tabs-item">
	客户订单
	</div>
	<div index="3" class="r-tabs-item">
	客户合同
	</div>
	<div index="4" class="r-tabs-item">
	收款单
	</div>
	<div index="5" class="r-tabs-item">
	付款单
	</div>
</div>
<table border="0" tabitem="0" tabid="a" width="100%" cellspacing="0" cellpadding="0">
	@foreach($fieldsarr as $frs)
	@if($frs->iszb==0 && $frs->fieldstype!='subtable')
	<tr>
	<td class="ys1" nowrap><div class="ys0div">{{ $frs->name }}</div></td>
	<td class="ys2 ysb r-wrap">{!! $data->{$frs->fields} !!}</td>
	</tr>
	
	@endif
	@endforeach
</table>
<div tabitem="1" tabid="a" style="display:none"></div>
<div tabitem="2" tabid="a" style="display:none"></div>
<div tabitem="3" tabid="a" style="display:none"></div>
<div tabitem="4" tabid="a" style="display:none"></div>
<div tabitem="5" tabid="a" style="display:none"></div>