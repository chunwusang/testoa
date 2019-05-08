@extends('admin.public')

@section('content')
<div class="container">
	<div>
		<h3>{{ $pagetitle }}</h3>
		<div>{{ trans('table/anstall.pagemsg') }}{!! $helpstr !!}</div>
		<hr class="head-hr" />
	</div>	
	
	

	
</div>
@endsection

@section('script')
<script>

</script>
@endsection