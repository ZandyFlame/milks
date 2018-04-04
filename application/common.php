<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 格式化数据输出
 * @param int $state 状态值
 * @param string $msg 消息
 * @param array $data 数据
 * @return array
 */
function back_data($state=1,$msg='',$data=[]){
    return [
        'state'=>$state,
        'msg'=>$msg,
        'data'=>$data
    ];
}