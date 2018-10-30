<?php
/**
 * 基本配置文件
 * @Author: LongDH
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-30 14:41:23
 */

return [
    'PATH_INFO'             => 2,         //路由模式 1rewrite模式(不支持二级目录) 2普通模式

    'default_controller'    => 'Index',   // 默认控制器
    'default_function'      => 'index',   // 默认方法

    'URL_PATHINFO_DEPR'     =>  '/',     // PATHINFO模式下，各参数之间的分割符号
    'VAR_AUTO_STRING'       =>  false,   // 输入变量是否自动强制转换为字符串 如果开启则数组变量需要手动传入变量修饰符获取变量
    'DEFAULT_FILTER'        =>  'htmlspecialchars', // 默认参数过滤方法 用于I函数
];