<div style="padding:20px">
	<div class="row">
		@foreach($agenharr as $atype=>$agenha)
		<div class="col-md-3 col-sm-3">
			<div align="left" class="list-group">
				<div class="list-group-item  list-group-item-success"><i class="glyphicon glyphicon-plus"></i> {{ $atype }}({{ count($agenha) }})</div>
				@foreach($agenha as $k=>$item)
				<a href="javascript:;" onclick="openluzhu('{{ $item->name }}','{{ $item->num }}')" class="list-group-item"><img src="{{ $item->face }}" height="20" width="20"> {{ $item->name }}</a>
				@endforeach
			</div>
		</div>
		@endforeach
	</div>
</div>