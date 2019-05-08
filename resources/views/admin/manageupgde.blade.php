@extends('admin.public')

@section('content')

<div class="container">
	
	<table width="100%"><tr>
		<td style="padding-right:10px"><button type="button" onclick="c.init();" class="btn btn-success">{{ trans('base.reloadtext') }}</button></td>
		<td width="100%">
	
			<div>{!! trans('admin/upgde.desc') !!}{!! $helpstr !!}</div>
		
		</td>
		<td align="right">
			
		</td>
	</tr></table>
	
	<table  style="margin-top:10px" class="table table-striped table-bordered table-hover">
		<tr>
			<th></th>
			<th>{{ trans('admin/upgde.num') }}</th>
			<th>{{ trans('admin/upgde.name') }}</th>
			<th>{{ trans('admin/upgde.explain') }}</th>
			<th>{{ trans('admin/upgde.updatedt') }}</th>
			<th>{{ trans('admin/upgde.price') }}</th>
			<th>{{ trans('admin/upgde.xiang') }}</th>
			<th>{{ trans('admin/upgde.opt') }}</th>
		</tr>
		<tbody id="showneidiv">
		</tbody>
	</table>
	
	
	

	
</div>
@endsection

@section('script')
<script src="/js/base64-min.js"></script>
<script>
var urly = '{{ config('rock.urly') }}';
function initbody(){
	c.init();
}
var c = {
	init:function(){
		var url = '{{ route('apiadmin','upgde') }}';
		$.rockmodelmsg('wait', '{{ trans('base.loading') }}');
		js.ajax(url,false,function(ret){
			c.showdata(ret.data);
			$.rockmodelmsg('none');
		},'get',function(msg){
			$.rockmodelmsg('msg', msg);
		});
	},
	jobbo:true,
	showdata:function(da){
		var i,len=da.length,s='',d,az,zt,v;
		for(i=0;i<len;i++){
			d = da[i];
			v = -1;
			var url = '/admin/manage/upgdefile?mode='+jm.base64encode(d.id)+'';
			if(d.isaz=='0'){
				az = '<font color=#888888>无需安装</font>';
			}else{
				v = d.state;
				if(v==1)az='<font color=green>已安装</font> ';
				if(v==2)az='<a onclick="return c.niopen(this.href, '+d.id+')" class="btn btn-danger btn-sm" href="'+url+'">去升级</a>';
				if(v==0)az='<a onclick="return c.niopen(this.href, '+d.id+')" class="btn btn-info btn-sm" href="'+url+'">去安装</a>';
				if(d.id==25){
					if(v!=1)this.jobbo=false;
				}
			}
			
			s+='<tr>';
			s+='<td>'+(i+1)+'</td>';
			s+='<td>'+d.num+'</td>';
			s+='<td>'+d.name+'</td>';
			s+='<td>'+d.explain+'</td>';
			s+='<td>'+d.updatedt+'</td>';
			
			if(d.price=='0'){
				s+='<td>免费</td>';
			}else{
				s+='<td>'+d.price+'元</td>';
			}
			
			s+='<td><a href="'+urly+'/view_'+d.num+'.html" target="_blank">详情';
			if(d.price>0 && v==0)s+=',<a style="color:red" href="'+urly+'/view_'+d.num+'.html#buy" target="_blank">去购买';
			if(d.price==0 || v>0){
				if(d.isaz=='1')s+=',<a onclick="return c.niopen(this.href, '+d.id+')" href="'+url+'">文件</a>';
			}
			s+='</td>';
			
			s+='<td>'+az+'</td>';
			s+='</tr>';
		}
		$('#showneidiv').html(s);
	},
	niopen:function(url,nid){
		if(nid!=25 && !this.jobbo){
			js.alert('“平台系统基础核心文件”必须先升级最新才能安装升级其他模块');
			return false;
		}
		js.location(url);
		return false;
	}
};
</script>
@endsection