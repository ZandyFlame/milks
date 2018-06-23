<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/28 0028
 * Time: 10:50
 */

namespace app\system\controller;
use think\Db;
use think\Controller;

class Login extends Controller
{
    public function login(){
        /*if("POST" == request()->method()){
            return $this->redirect('index/index');
        }else */if("GET" == request()->method()){
            return $this->fetch();
        }
    }

    public function check(){
        $captcha = input('captcha');
        $username = input('username');
        $password = input('password');
        if(!captcha_check($captcha)){
            return back_data('1','验证码错误，请重新输入',$captcha);
        }
       $user = Db::name('admin')->where('admin_username',$username)->find();
        if(!$user){
            return back_data('2','用户名不存在，请重新输入',$username);
        }else if($user['admin_password'] != $password){
            return back_data('3','密码错误，请重新输入');
        }
        session('admin_id',$user['admin_id']);
        return back_data('0','验证成功');
    }
}