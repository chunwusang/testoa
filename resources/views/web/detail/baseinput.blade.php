@foreach($fieldsarr as $k=>$item)
@if($item->iszb==0)
	@if($item->fieldstype=='hidden')
		{!! $inputobj->show($item, $data) !!}
	@elseif($item->fieldstype=='jiange')
	<tr>
		<td colspan="2"><div style="background:#EDF7FC;line-height:30px;border-radius:5px" align="center"><b>{{ $item->name }}</b></div><input name="{{ $item->fields }}" type="hidden" ></td>
	</tr>
	@elseif($item->fieldstype=='subtable')
	<tr inputname="{{ $item->fields }}" @if($item->mstyle)style="{{ $item->mstyle }}" @endif>
		<td style="padding:5px" colspan="2">
		<div align="center">
		<div align="left"><b>{{ $item->name }}</b></div>
		<div lesub="subtable" style="overflow:auto;">{!! $inputobj->showsubtable($item, $fieldsarr, $subdata, $store, $isedit) !!}</div>
		</div>
		</td>
	</tr>	
	@else
	<tr inputname="{{ $item->fields }}" @if($item->mstyle)style="{{ $item->mstyle }}" @endif>
		<td class="ys1" nowrap>
			@if($item->isbt==1)<font color=red>*</font>@endif{{ $item->name }}</td>
		<td class="ys2 ysb">
			{!! $inputobj->show($item, $data, $store) !!}
		</td>	
	</tr>
	@endif
@endif
@endforeach