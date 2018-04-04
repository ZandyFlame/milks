<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4 0004
 * Time: 16:24
 */

namespace app\system\controller;
use think\Controller;
use think\Db;

class User extends Controller
{
    public function index(){
        return $this->fetch();
    }
}