@extends('users.public')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('users/manage.createcompany') }}</div>

                <div class="panel-body">
                    <table class="table table-striped table-bordered table-hover">
					<tr>
					<th>ID</th>
					<th></th>
					<th>{{ trans('table/company.name') }}</th>
					<th></th>
					</tr>
					@foreach( $createcompany as $item )
					<tr>
					<td>{{ $item->id }}</td>
					<td  align="center"><img src="{{ $item->logo }}" height="30"></td>
					<td>{{ $item->name }}</td>
					<td><a class="btn btn-primary btn-xs" href="{{ route('manage', [$item->num,'home']) }}"><i class="glyphicon glyphicon-wrench"></i> {{ trans('users/manage.guanli') }}</a></td>
					</tr>
					@endforeach
					</table>
					
					@if(Auth::user()->flaskm>0)
					<div><a class="btn btn-success" href="{{ route('userscompanyadd') }}">{{ trans('users/manage.creareadd') }}</a></div>
					@endif
                </div>
            </div>
        </div>
		<div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">{{ trans('users/manage.jiarucompany') }}</div>

                <div class="panel-body">
                    
					<table class="table table-striped table-bordered table-hover">
					<tr>
					<th></th>
					<th>{{ trans('table/company.name') }}</th>
					<th>{{ trans('table/usera.status') }}</th>
					</tr>
					@foreach( $joincompany as $item )
					<tr>
					<td align="center"><img src="{{ $item->companylogo }}" height="30"></td>
					<td>{{ $item->companyname }}</td>
					<td>
					@if ($item->status==1)
					<span class="label label-success">{{ trans('table/usera.status1') }}</span>
					<a class="btn btn-default btn-xs" type="button" href="{{ route('usersindexs', $item->company->num) }}">{{ trans('table/usera.status14') }}</a>
					@endif
					@if ($item->status==0)
					<span class="label label-danger">{{ trans('table/usera.status0') }}</span>
					<a class="btn btn-success btn-xs" type="button" href="{{ route('usersactive', $item->id) }}">{{ trans('table/usera.status13') }}</a>
					@endif
					@if ($item->status==2)
					<span class="label label-warning">{{ trans('table/usera.status2') }}</span>
					@endif
					</td>
					</tr>
					@endforeach
					
					
					</table>
					
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
