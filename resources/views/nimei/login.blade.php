@extends('layouts.bst')

@section('content')
    <h2 class="form-signin-heading" style="margin-left: 100px">请登录</h2>
    <form class="form-horizontal" action="/user/login" method="post" style="margin-top: 30px">
        {{csrf_field()}}
        <input type="hidden" value="{{$redirect}}" name="redirect">
        <div class="form-group" >
            <label for="inputEmail3" class="col-sm-2 control-label">用户名：</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="uname" style="width: 300px">
            </div>
        </div>
        <div class="form-group" >
            <label for="inputPassword3" class="col-sm-2 control-label">密码：</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="upwd" style="width: 300px">
            </div>
        </div>
        <div class="form-group" >
            <label for="inputPassword3" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <input type="checkbox" value="remember-me"> Remember me
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-info" style="width: 80px">登录</button>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="/tests/register">没有账号？>>去注册</a>
            </div>
        </div>
    </form>
@endsection