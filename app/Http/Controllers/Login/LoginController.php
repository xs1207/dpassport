<?php

namespace App\Http\Controllers\Login;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserModel;
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    //

    //注册
    public function register()
    {
        $redirect=$_GET['redirect'] ?? env('SHOP_URL');
        $data=[
            'redirect'=>$redirect
        ];
        return view('nimei.register',$data);
    }

    public function doRegister(Request $request)
    {
        $name=$request->input('uname');
        if(empty($name)){
            die("账号不能为空");
        }
        $upwd=$request->input('upwd');
        if(empty($upwd)){
            die("密码不能为空");
        }
        $upwd1=$request->input('upwd1');
        if(empty($upwd1)){
            die("确认 密码不能为空");
        }
        if($upwd!==$upwd1){
            die("密码不一致");
        }
        $age=$request->input('uage');
        if(empty($age)){
            die("年龄不能为空");
        }
        $uemail=$request->input('uemail');
        if(empty($uemail)){
            die("邮箱不能为空");
        }
        $r=$request->input('redirect')?? env('SHOP_URL');
        $pwd=password_hash($upwd1,PASSWORD_BCRYPT);
        $data=[
            'name'=>$name,
            'pwd'=>$pwd,
            'age'=>$age,
            'email'=>$uemail,
            'reg_time'  => time(),
        ];

        $u=UserModel::where(['name'=>$request->input('uname')])->first();
//		echo $u;die;
        if($u){
            echo "用户名已存在";
            header("refresh:1;/user/register");
        }else{
            $uid=UserModel::insertGetId($data);
            //var_dump($uid);
            if($uid){
                $token = substr(md5(time() . mt_rand(1,99999)), 10, 10);
//                echo $token;die;
                setcookie('uid', $uid, time() + 86400, '/', 'tactshan.com', false, true);
                setcookie('name', $name, time() + 86400, '/', 'tactshan.com', false, true);
                setcookie('token', $token, time() + 86400, '/', 'tactshan.com', false, true);
//                $request->session()->put('u_token',$token);
//                $request->session()->put('uid',$uid);
                //存到redis
                $redis_key_web_token='str:u:token:'.$uid;
                Redis::del($redis_key_web_token);
                Redis::hset($redis_key_web_token,'web',$token);
                echo '注册成功,正在跳转';
                header("Refresh:1;$r");
            }else{
                echo "注册失败";
                header('refresh:1;/user/register');
            }
        }

    }



    public function login()
    {
        $redirect=$_GET['redirect'] ?? env('SHOP_URL');
        $data=[
            'redirect'=>$redirect
        ];
        return view('nimei.login',$data);
    }
    public function doLogin(Request $request)
    {
        $uname=$request->input("uname");
        $upwd=$request->input("upwd");
        
        $url=$request->input('redirect')?? env('SHOP_URL');

        $res=UserModel::where(['name'=>$uname])->first();
        if($res){
            if(password_verify($upwd,$res->pwd)) {
                $token = substr(md5(time() . mt_rand(1, 99999)), 10, 10);
//                echo $token;die;
                setcookie('uid', $res->uid, time() + 86400, '/', 'tactshan.com', false, true);
                setcookie('name', $res->name, time() + 86400, '/', 'tactshan.com', false, true);
                setcookie('token', $token, time() + 86400, '/', 'tactshan.com', false, true);
//                $request->session()->put('u_token',$token);
//                $request->session()->put('uid',$res->uid);

                $redis_key_web_token='str:u:token:'.$res->uid;
                Redis::del($redis_key_web_token);
                Redis::hSet($redis_key_web_token,'web',$token);

//                echo $redis_key_web_token;die;
                echo "登陆成功";
                header("refresh:1;$url");
            }else{
                echo "账号或密码有误";
                header("refresh:1;/user/login");
            }
        }else{
            echo "账号或密码有误";
            header("refresh:1;/user/login");
        }
    }


    public function lgn(Request $request)
    {
        $name=$request->input('name');
        $pwd=$request->input('pwd');

        $res=UserModel::where(['name'=>$name])->first();
        if($res){
            if(password_verify($pwd,$res->pwd)) {
                $token = substr(md5(time() . mt_rand(1, 99999)), 10, 10);
                $redis_key_web_token='str:u:token:'.$res->uid;
                Redis::del($redis_key_web_token);
                Redis::hSet($redis_key_web_token,'app',$token);

//                echo $redis_key_web_token;die;
                $response=[
                    'errno'=>0,
                    'msg'=>'登陆成功',
                    'token'=>$token
                ];

                return $response;
            }else{
                $response=[
                    'errno'=>500,
                    'msg'=>'登陆失败'
                ];
            }
        }else{
            $response=[
                'errno'=>500,
                'msg'=>'登陆失败'
            ];
        }
        return $response;
    }

}
