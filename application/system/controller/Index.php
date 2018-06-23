<?php
namespace app\system\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('msg',"132456978");
        return $this->fetch();
    }
}
