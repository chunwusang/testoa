@extends('manage.public')

@section('content')
<div class="container">
	<div>
		<h3>{{ trans('manage/public.menu.wxqydept') }}</h3>
		<hr class="head-hr" />
	</div>
	
	<div>
		<input type="button" value="获取企业微信上部门" onclick="c.getwdept()" class="btn btn-default">&nbsp;
		<input type="button" value="将企业微信上的部门同步到系统上"  onclick="c.anaytosys()" class="btn btn-default">&nbsp;
		<input type="button" value="刷新" onclick="js.reload()" class="btn btn-default">&nbsp;
	</div>
	
	
    <div style="margin-top:15px" class="row">
	
	<div class="col-sm-6">
	
		<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">企业微信上部门</h3>
		</div>
		<div class="panel-body" style="padding:0px">
				
			<table style="margin:0" class="table table-striped table-bordered table-hover">
				<tr>
					<th>名称</th>
					<th>上级ID</th>
					<th>排序号</th>
					<th>状态</th>
					<th>ID</th>
				</tr>
				@foreach($wdeptdata as $item)
					<tr>
						<td>
						@for($i=0; $i<$item->level; $i++)
							<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i>
						@endfor
						<i class="glyphicon glyphicon-folder-close"></i>
						{{ $item->name }}
						</td>
						<td>{{ $item->parentid }}</td>
						<td>{{ $item->order }}</td>
						<td>
						@if(!$item->iscz)<font color="red">不存在</font><a href="javascript:;" onclick="c.deldept({{ $item->id }})">[删]</a>@endif
						</td>
						<td>{{ $item->id }}</td>
					</tr>
					@endforeach
			</table>	
		</div>
		</div>

	
	</div>
	<div class="col-sm-6">
	
		<div class="panel panel-default">
			<div class="panel-heading">
			<h3 class="panel-title">系统上的部门</h3>
			</div>
			<div class="panel-body" style="padding:0px;">	
				<table style="margin:0" class="table table-striped table-bordered table-hover">
					<tr>
						<th>名称</th>
						<th>负责人</th>
						<th>上级ID</th>
						<th>排序号</th>
						<th>状态</th>
						<th>ID</th>
					</tr>
					
					@foreach($deptdata as $item)
					<tr>
						<td>
						@for($i=0; $i<$item->level; $i++)
							<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i>
						@endfor
						<i class="glyphicon glyphicon-folder-close"></i>
						{{ $item->name }}
						</td>
						<td>{{ $item->headman }}</td>
						<td>{{ $item->pid }}</td>
						<td>{{ $item->sort }}</td>
						<td>
						@if($item->istb>0)
							@if($item->istb==2)<font color="red">未同步</font>@endif
							@if($item->istb==1)<font color="green">已同步</font>@endif
							@if($item->istb==3)<font color="red">需更新</font>@endif
							<a href="javascript:;" onclick="c.updatedept({{ $item->id }})">[更新]</a>
						@endif	
						</td>
						<td>{{ $item->id }}</td>
					</tr>
					@endforeach
				</table>		
			</div>
		</div>
		
		
	</div>
		
	</div>	
</div>
@endsection


@section('script')

<script>
function initbody(){
	
}
var c = {
	getwdept:function(){
		this.optchul('getwdept', false);
	},
	optchul:function(act,can){
		js.loading();
		js.ajax('/api/unit/'+cnum+'/wxqy_'+act+'', can, function(){
			js.msgok();
			js.reload();
		},'get', function(msg){
			js.msgerror(msg);
		});
	},
	anaytosys:function(bo){
		if(!bo){
			js.confirm('确定要将将企业微信上组织结构同步到系统上吗？同步了，将覆盖系统原来创建的哦。', function(jg){if(jg=='yes')c.anaytosys(true)});
			return;
		}
		this.optchul('anaytosys', false);
	},
	deldept:function(id){
		this.optchul('deldept', {id:id});
	},
	updatedept:function(id){
		this.optchul('updatedept', {id:id});
	}
}
</script>
@endsection