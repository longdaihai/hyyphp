<?php
// +----------------------------------------------------------------------
// | HYYPHP [ WE CAN DO IT JUST HYYPHP ]
// +----------------------------------------------------------------------
// | Copyright (c) HanSheng All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: HanSheng <164897033@qq.com>
// +----------------------------------------------------------------------

return [
     'DB_TYPE'     => 'mysql',      // 数据库类型
     'DB_HOST'     => '172.30.0.2', // 服务器地址
     'DB_NAME'     => 'test',       // 数据库名
     'DB_USER'     => 'root',       // 用户名
     'DB_PWD'      => '123456',     // 密码
     'DB_PORT'     => '3306',       // 端口
     'DB_PREFIX'   => 'hyy_',       // 数据库表前缀
     'DB_CHARSET'  => 'utf8',       // 数据库编码默认采用utf8
     'DB_LOG'      => true,
     'option'      => [ //read more from http://www.php.net/manual/en/pdo.setattribute.php
         PDO::ATTR_CASE => PDO::CASE_NATURAL
     ],
];