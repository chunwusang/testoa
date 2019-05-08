@extends('manage.public')

@section('content')
<div style="padding:0px 15px">
   
	<div>
		<h3>{{ trans('manage/public.menu.wxqyuser') }}</h3>
		<div>系统是根据用户名跟企业微信上的帐号相关联的，一旦设置就不要去随意修改。</div>
		<hr class="head-hr" />
	</div>	
   
	<div class="row">
		<div class="col-sm-2" style="cursor:pointer">
			<ul class="list-group">
			  <li class="list-group-item active">{{ trans('table/usera.depttitle') }}</li>
			  
				@foreach($deptdata as $item)
				<li onclick="tousertr({{ $item->id }}, {{ $item->pid }})" class="list-group-item @if($item->id==$did)list-group-item-info @endif ">
				@for($i=0; $i<$item->level; $i++)
					<i style="opacity:0" class="glyphicon glyphicon-folder-close"></i>
				@endfor
				<i class="glyphicon glyphicon-folder-close"></i>
					{{ $item->name }}
				</li>
				@endforeach

			</ul>
		</div>
		
		<div class="col-sm-10">
		<!-- <div>啦啦啦</div> -->
			<div>
				<form class="form-inline" role="form">
				<table width="100%">
				<tr>
				<td style="padding-right:10px">

					<div class="btn-group">	
					<button type="button" id="dropMenuid8" data-toggle="dropdown" class="btn btn-default dropdown-toggle ">选择用户操作 <span class="caret"></span></button>
					<ul class="dropdown-menu" aria-labelledby="dropMenuid8">
					<li><a href="javascript:;" onclick="c.yjeeng()">一键更新选中的用户</a></li>
					<li><a href="javascript:;" onclick="c.anaytouser()">将企业微信上用户同步到系统</a></li>
					<li><a href="javascript:;" style="color:red" onclick="c.delxuan()">删除选中企业微信用户</a></li>
					</ul>	
					</div>
					
				</td>
				<td style="padding-right:10px"><button class="btn btn-success" onclick="c.getlist();" type="button">获取企业微信上用户</button></td>
				<td>
					<input style="width:150px" class="form-control" type="text" name="keyword" value="{{ Request::get('keyword') }}" placeholder="{{ trans('base.keyword') }}">
				</td>
				
				<td style="padding-left:10px">
					<select style="width:120px" name="souzt" class="form-control">
					<option value="">全部状态</option>
					<option value="1" @if($souzt=='1')selected @endif>已激活</option>
					<option value="0" @if($souzt=='0')selected @endif>未激活</option>
					<option value="2" @if($souzt=='2')selected @endif>已停用</option>
					<option value="3" @if($souzt=='3')selected @endif>微信已激活</option>
					<option value="4" @if($souzt=='4')selected @endif>微信未激活</option>
					<option value="5" @if($souzt=='5')selected @endif>微信未创建</option>
					</select>
				</td>
				<td>
				<button type="submit" class="btn btn-success">{{ trans('base.searchbtn') }}</button>
				</td>
				
				<td width="100%"></td>
				<td width="right" nowrap>
					
					<button class="btn btn-danger" onclick="c.delaluser()" type="button">删除企业微信上系统不存在的用户</button>&nbsp;&nbsp;
					<button type="button" onclick="reloaddata()" class="btn btn-success"><i class="glyphicon glyphicon-refresh"></i> {{ trans('table/usera.reloads') }}</button>
				</td>
				</tr>
				</table>
				</form>
			</div>
				
			<table style="margin-top:10px" class="table table-striped table-bordered table-hover">
				<tr>
					<th><input onclick="js.selall(this, 'selid')" type="checkbox"></th>
					<th>ID</th>
					<th></th>
					<th>{{ trans('table/usera.name') }}</th>
					<th>{{ trans('table/usera.user') }}</th>
					<th>{{ trans('table/usera.deptname') }}</th>
					<th>{{ trans('table/usera.superman') }}</th>
					<th>{{ trans('table/usera.position') }}</th>
					<th>{{ trans('table/usera.mobile') }}</th>
					<th>{{ trans('table/usera.sort') }}</th>
					<th>{{ trans('table/usera.status') }}</th>
					
					<th>UID</th>
					<th>{{ trans('table/usera.wxstatus') }}</th>
				</tr>
				@foreach ($data as $item)
				<tr id="row_{{ $item->id }}" @if ($item->status!=1)style="color:#aaaaaa" @endif>
					<td><input value="{{ $item->id }}" name="selid" type="checkbox"></td>
					<td>{{ $item->id }}</td>
					<td><img src="{{ $item->face }}" width="30"></td>
					<td>{{ $item->name }} </td>
					<td>{{ $item->user }} </td>
					<td>{{ $item->deptname }}</td>
					<td>{{ $item->superman }}</td>
					<td>{{ $item->position }}</td>
					<td>{{ $item->mobilecode }}{{ substr($item->mobile,0,3) }}****{{ substr($item->mobile,-4) }}</td>
					<td>{{ $item->sort }}</td>
					<td >
					@if ($item->status==1)
					<font color="green">{{ trans('table/usera.status1') }}</font>
					@endif
					@if ($item->status==0)
					<font color="red">{{ trans('table/usera.status0') }}</font>
					@endif
					@if ($item->status==2)
					<font color="#aaaaaa">{{ trans('table/usera.status2') }}</font>
					@endif
					</td>
					<td>{{ $item->uid }}</td>
					<td>
					@if($item->wxstatus==0)
						<a href="javascript:;" onclick="c.updateuser({{ $item->id }})">[创建]</a>
						<font color="blue">未创建</font>
					@else
						<a href="javascript:;" onclick="c.updateuser({{ $item->id }})">[更新]</a>
						@if($item->jihuo==1)
							<font color="green">已激活</font>
						@elseif($item->jihuo==2)
							<font color="#aaaaaa">已冻结</font>
						@else
							<font color="red">未激活</font>
						@endif
						@if($item->isgc>0)<font color="red">{{ $item->gxls }}</font>需更新@endif
					@endif
					</td>
				</tr>
				@endforeach
			</table>
			
			@include('layouts.pager')
			
			@if($bucz)
				<div class="alert alert-danger">微信用户中有在系统不存在的用户：<font color=red>{{ $bucz }}</font>，可点按钮删除</div>
			@endif	
		</div>
	</div>
   
</div>
@endsection

@section('script')
<script>
function tousertr(id, pid){
	var url = '/manage/'+cnum+'/wxqy_user';
	if(pid==0)id=0;
	js.location(''+url+'?did='+id+'');
}
function reloaddata(){
	js.loading();
	js.ajax('/api/unit/'+cnum+'/usera_reload', false, function(ret){
		js.msgok();
		js.reload();
	},'get');
}

var c = {
	getlist:function(){
		this.optchul('getuserlist', false);
	},
	updateuser:function(id){
		this.optchul('updateuser', {id:id});
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
	delxuan:function(bo){
		var sid = js.getchecked('selid');
		if(sid==''){
			js.msgerror('没有选中行用户');
			return;
		}
		if(!bo)js.confirm('确定要将用户在企业微信上删除吗？', function(jg){
			if(jg=='yes')c.delxuan(true);
		});
		if(!bo)return;
		this.optchul('deleteuser', {sid:sid});
	},
	delaluser:function(bo){
		if(!bo)js.confirm('确定要删除企业微信用户在系统不存在的用户吗？', function(jg){
			if(jg=='yes')c.delaluser(true);
		});
		if(!bo)return;
		this.optchul('delaluser');
	},
	yjeeng:function(){
		var sid = js.getchecked('selid');
		if(sid==''){
			js.msgerror('没有选中行用户');
			return;
		}
		this.sidarr = sid.split(',');
		js.loading('更新中('+this.sidarr.length+'/<span id="gengxod">0</span>)...', 120);
		this.yjeengs(0);
	},
	yjeengs:function(oi){
		var len = this.sidarr.length;
		if(oi>=len){
			js.msgok('更新完成');
			js.reload();
			return;
		}
		$('#gengxod').html(''+(oi+1)+'');
		js.ajax('/api/unit/'+cnum+'/wxqy_updateuser', {id:this.sidarr[oi]}, function(){
			c.yjeengs(oi+1);
		},'get', function(msg){
			js.msgerror(msg);
		});
	},
	anaytouser:function(){
		js.confirm('确定要将将企业微信上用户同步到系统上吗？同步了，将覆盖系统原来创建的哦。',function(jg){
			if(jg=='yes')c.anaytousers();
		});
	},
	anaytousers:function(){
		this.optchul('anaytouser', false);
	}
}
</script>
@endsection