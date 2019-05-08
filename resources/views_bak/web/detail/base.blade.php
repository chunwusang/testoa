<table border="0" width="100%" cellspacing="0" cellpadding="0">
	@foreach($fieldsarr as $frs)
	
	@if($frs->iszb==0)
	@if($frs->fieldstype=='subtable')
	<tr>
	<td class="ys0" colspan="2">
	<b>{{ $frs->name }}</b>
	{!! $data->{$frs->fields} !!}
	</td>	
	</tr>	
	@else
	<tr>
	<td class="ys1" nowrap><div class="ys0div">{{ $frs->name }}</div></td>
	<td class="ys2 ysb r-wrap">{!! $data->{$frs->fields} !!}</td>
	</tr>		
	@endif
	
	@endif
	@endforeach
</table>