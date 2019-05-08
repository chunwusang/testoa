@extends('admin.public')

@section('content')
<div class="container" align="center">
    <div align="left" style="max-width:500px;margin-top:50px">
        
            <div class="panel panel-default">
                <div class="panel-heading">后台登录</div>

                <div class="panel-body">
                    <form class="form-horizontal" name="myform" method="POST" onsubmit="return onsubmitbu()" action="{{ route('adminlogincheck') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label">邮箱地址</label>

                            <div class="col-md-7">
                                <input type="email" class="form-control" name="email" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-3 control-label">密码</label>

                            <div class="col-md-7">
								<input name="password" id="pass1" type="hidden">
                                <input type="password" onkeydown="get('pass1').value=this.value" onblur="get('pass1').value=this.value" class="form-control" required>
                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="rememberpass">记住密码
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-3">
                                <button type="submit" class="btn btn-primary">
                                    登录
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
    </div>
</div>
@endsection

@section('script')
<script>
function onsubmitbu(){
	js.setoption('houtaiuser', form('email').value);
	js.setoption('rememberpass', form('rememberpass').checked ? '1' : '2');
	if(form('rememberpass').checked){
		js.setoption('houtaipass', get('pass1').value);
	}else{
		js.setoption('houtaipass', '');
	}
	return true;
}

function getpassobj(){
	return $('input[type=password]');
}

function initbody(){
	form('email').value = js.getoption('houtaiuser');
	get('pass1').value = js.getoption('houtaipass');
	getpassobj().val(js.getoption('houtaipass')) ;
	if(js.getoption('rememberpass')=='1')form('rememberpass').checked=true;
}


</script>
@endsection