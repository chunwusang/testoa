<div class="r-tabs" tabid="a">
	<div index="0" class="r-tabs-item active">
	基本资料
	</div>
	<div index="1" class="r-tabs-item">
	工作经历
	</div>
	<div index="2" class="r-tabs-item">
	教育经历
	</div>
	<div index="3"  aid="{{ $data->aid }}" class="r-tabs-item">
	员工合同
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
<div tabitem="1" tabid="a" style="display:none">
{!! $data->jingliwork !!}
</div>
<div tabitem="2" tabid="a" style="display:none">
{!! $data->jinglijiaoyu !!}
</div>
<div tabitem="3" tabid="a" style="display:none"></div>