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
						<a href="{{ route('jctadmin_website_articleedit', 0) }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> 新增文章</a>
					</td>

				</tr></tbody></table>
		</div>

		<table class="table table-striped table-bordered table-hover" style="margin-top:10px" >

			<tr>
				<th>ID</th>
				<th>标题</th>
				<th><div  align="center">图片</div></th>
				<th>分类名</th>
				<th>添加时间</th>

				<th>操作</th>

			</tr>
			@foreach ($data as $item)
				<tr id="row_{{ $item->id }}" >
					<td>{{ $item->id }}</td>
					<td>{{ $item->title }}</td>
					<td align="center"><img src="{{ Rock::replaceurl($item->img) }}" height="30"></td>
					<td>{{ $item->cat_name}}</td>
					<td>{{date('Y-m-d H:i:s', $item->add_time)}}</td>
					<td>
						<a href="{{ route('jctadmin_website_articleedit', $item->id) }}">{{ trans('base.edittext') }}</a>
						<a href="javascript:;" onclick="delconfirm({{ $item->id }})">{{ trans('base.deltext') }}</a>
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
                delmsg:'确认删除文章？',
                delurl:'{{ route('jctadmin_website_articledelete') }}'
            });
        }
	</script>
@endsection