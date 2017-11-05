<?php
// +----------------------------------------------------------------------
// | HYYPHP [ WE CAN DO IT JUST HYYPHP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017~2018 http://HYYPHPphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: LongDaihai <164897033@qq.com>
// +----------------------------------------------------------------------

define('DS', DIRECTORY_SEPARATOR);
define('HYYPHP_VERSION', '0.0.1Beat');
define('HYYPHP_START_TIME', microtime(true));
define('HYYPHP_START_MEM', memory_get_usage());
define('EXT', '.php');
define('CORE_PATH', 'lib' . DS);
define('COMMON_PATH', 'common' . DS);
defined('DEBUG') or define('DEBUG', false);
defined('HYYPHP_PATH') or define('HYYPHP_PATH', __DIR__ . DS);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('MODULE') or define('MODULE', '/../app' . DS); //项目文件目录
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
defined('RUNTIME_PATH') or define('RUNTIME_PATH', APP_PATH . 'runtime' . DS);
defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'log' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);
defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);
defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录

// Debug
if(DEBUG) {
     ini_set('display_error', 'On');
}else {
     ini_set('display_error', 'Off');
}

// 载入公共函数库
require COMMON_PATH . 'function.php';
require_once 'app.php';
//注册一个用户自定义的自动加载类方法
spl_autoload_register(array('App', 'hyyLoader'));

try {
    App::run();

} catch (Exception $e) {
    echo $e->getMessage();
}