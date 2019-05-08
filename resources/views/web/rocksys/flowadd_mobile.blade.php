<?php $count=0;?>
@foreach($agenharr as $atype=>$agenha)
	<div class="weui_cells_title">{{ $atype }}({{ count($agenha) }})</div>
	<div class="weui_cells weui_cells_access">
	@foreach($agenha as $k=>$item)
		<a class="weui_cell" href="/input/{{ $companyinfo->num }}/{{ $item->num }}"  >
			<div class="weui_cell_bd weui_cell_primary"><img src="{{ $item->face }}" align="absmiddle" height="20" width="20"> {{ $item->name }}
			</div>
			<div class="weui_cell_ft"></div>
		</a>
		<?php $count++;?>
	@endforeach
	</div>
@endforeach

<div align="center" class="weui_cells_title">总共可申请<?=$count?>个</div>