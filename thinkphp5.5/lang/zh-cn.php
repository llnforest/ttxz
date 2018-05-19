<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 核心中文语言包
return [
    // 系统错误提示
    'Undefined variable'        => '未定义变量',
    'Undefined index'           => '未定义数组索引',
    'Undefined offset'          => '未定义数组下标',
    'Parse error'               => '语法解析错误',
    'Type error'                => '类型错误',
    'Fatal error'               => '致命错误',
    'syntax error'              => '语法错误',

    // 框架核心错误提示
    'dispatch type not support' => '不支持的调度类型',
    'method param miss'         => '方法参数错误',
    'method not exists'         => '方法不存在',
    'module not exists'         => '模块不存在',
    'controller not exists'     => '控制器不存在',
    'class not exists'          => '类不存在',
    'property not exists'       => '类的属性不存在',
    'template not exists'       => '模板文件不存在',
    'illegal controller name'   => '非法的控制器名称',
    'illegal action name'       => '非法的操作名称',
    'url suffix deny'           => '禁止的URL后缀访问',
    'Route Not Found'           => '当前访问路由未定义',
    'Underfined db type'        => '未定义数据库类型',
    'variable type error'       => '变量类型错误',
    'PSR-4 error'               => 'PSR-4 规范错误',
    'not support total'         => '简洁模式下不能获取数据总数',
    'not support last'          => '简洁模式下不能获取最后一页',
    'error session handler'     => '错误的SESSION处理器类',
    'not allow php tag'         => '模板不允许使用PHP语法',
    'not support'               => '不支持',
    'redisd master'             => 'Redisd 主服务器错误',
    'redisd slave'              => 'Redisd 从服务器错误',
    'must run at sae'           => '必须在SAE运行',
    'memcache init error'       => '未开通Memcache服务，请在SAE管理平台初始化Memcache服务',
    'KVDB init error'           => '没有初始化KVDB，请在SAE管理平台初始化KVDB服务',
    'fields not exists'         => '数据表字段不存在',
    'where express error'       => '查询表达式错误',
    'no data to update'         => '没有任何数据需要更新',
    'miss data to insert'       => '缺少需要写入的数据',
    'miss complex primary data' => '缺少复合主键数据',
    'miss update condition'     => '缺少更新条件',
    'model data Not Found'      => '模型数据不存在',
    'table data not Found'      => '表数据不存在',
    'delete without condition'  => '没有条件不会执行删除操作',
    'miss relation data'        => '缺少关联表数据',
    'tag attr must'             => '模板标签属性必须',
    'tag error'                 => '模板标签错误',
    'cache write error'         => '缓存写入失败',
    'sae mc write error'        => 'SAE mc 写入错误',
    'route name not exists'     => '路由标识不存在（或参数不够）',
    'invalid request'           => '非法请求',
    'bind attr has exists'      => '模型的属性已经存在',
    'relation data not exists'  => '关联数据不存在',
    'page_no_found'             => '页面不存在',

    //2.0版系统提示
    'sys_close'              => '系统维护升级中，请稍候再试！',
    'sys_error'              => '网络异常，请稍后再试！',
    'sys_param_error'        => '参数错误',
    'sys_edit_success'       => '修改成功',
    'sys_edit_error'         => '修改失败',
    'sys_add_success'        => '添加成功',
    'sys_add_error'          => '添加失败',
    'sys_upload_success'     => '上传成功',
    'sys_combit_success'     => '申请成功',
    'sys_do_success'         => '操作成功',
    'sys_do_error'           => '操作失败',
    'sys_method_error'       => '请求方式错误',
    'sys_status_success'     => '状态更新成功',
    'sys_status_error'       => '状态更新失败',
    'sys_sort_success'       => '排序更新成功',
    'sys_sort_error'         => '排序更新失败',
    'sys_del_success'        => '删除成功',
    'sys_del_error'          => '删除失败',
    'sys_del_confirm'        => '确认删除么？',
    'sys_page_nodata'        => '还没有数据哦！',
    'sys_no_permission'      => '您没有访问权限！',
    'sys_cache_clear'        => '缓存清除成功',

    'sms_data_error'         => '验证码错误',
    'sms_check_success'      => '验证码正确',
    'sms_check_phone_error'      => '手机号码错误',

];
