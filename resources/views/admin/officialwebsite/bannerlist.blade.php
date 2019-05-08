@extends('admin.public')

@section('content')
	<div class="container">

		<div>
			<h3>{{ $pagetitle }}</h3>
			<div>{!! $helpstr !!}</div>
			<hr class="head-hr" />
		</div>

		<div class="tbl-top">
			<table width="20%"><tbody><tr>
					<td>
						<a href="{{ route('jctadmin_website_banneredit', 0) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> 新启banner</a>

					</td>

				</tr></tbody></table>
		</div>

		<table class="table table-striped table-bordered table-hover" style="margin-top:10px" >

			<tr>
				<th>ID</th>
				<th><div  align="center">图片</div></th>

				<th>操作</th>



			</tr>
			@foreach ($data as $item)
				<tr id="row_{{ $item->id }}" >
					<td>{{ $item->id }}</td>
					<td align="center"><img src="{{ Rock::replaceurl($item->img) }}"  height="30"></td>

					<td>
						<a href="{{ route('jctadmin_website_banneredit', $item->id) }}">{{ trans('base.edittext') }}</a>
						<a href="javascript:;"  onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
					</td>
				</tr>
			@endforeach
		</table>


	</div>
@endsection

@section('script')
	<script>
        function initbody(){
            $("td[edata-fields]").dblclick(function(){
                var columns = {};
                columns['contacts'] = {"name":"{{ trans('table/company.contacts') }}"};
                columns['tel'] = {"name":"{{ trans('table/company.tel') }}"};
                columns['flaskm'] = {"name":"{{ trans('table/company.flaskm') }}","type":"number"};
                $.rockmodelediter({
                    'obj':this,
                    'columns':columns,
                    'saveurl':'1',
                    'mtable':'{{ $mtable }}'
                });
            });
        }
        function delconfirm(id){
            $.rockmodeldel({
                delid:id,
                delmsg:'确认删除？',
                delurl:'{{ route('jctadmin_website_articledelete') }}'
            });
        }
	</script>
@endsection