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

/*
 * ------------------------------------------------------
 *  定义常量
 * ------------------------------------------------------
 */
define('DS', DIRECTORY_SEPARATOR);
define('HYYPHP_VERSION', '0.0.1-Beat');
define('HYYPHP_START_TIME', microtime(true));
define('HYYPHP_START_MEM', memory_get_usage());
define('EXT', '.php');
define('CORE_PATH', 'lib' . DS);
define('COMMON_PATH', 'common' . DS);
defined('DEBUG') or define('DEBUG', false);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('MODULE') or define('MODULE', '/../app' . DS); //项目文件目录
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);
defined('LOG_PATH') or define('LOG_PATH', ROOT_PATH . 'logs' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);
defined('CONF_PATH') or define('CONF_PATH', ROOT_PATH . 'config' . DS); // 配置文件目录
/*
 * ------------------------------------------------------
 *  环境常量 Windows / Liunx
 * ------------------------------------------------------
 */
define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

/*
 * ------------------------------------------------------
 *  载入Composer第三方库
 * ------------------------------------------------------
 */
$autoloadPath = VENDOR_PATH . 'autoload.php';
if(file_exists($autoloadPath)){
     require_once $autoloadPath;
}


/*
 * ------------------------------------------------------
 *  载入公共函数库
 * ------------------------------------------------------
 */
require_once COMMON_PATH . 'function.php';

/*
 * ------------------------------------------------------
 *  载入核心入口
 * ------------------------------------------------------
 */
require_once 'app.php';

/*
 * ------------------------------------------------------
 *  Debug
 * ------------------------------------------------------
 */
if(DEBUG) {
     ini_set('display_error', 'On');

}else {
     ini_set('display_error', 'Off');
}

/*
 * ------------------------------------------------------
 *  注册一个用户自定义的自动加载类方法
 * ------------------------------------------------------
 */
spl_autoload_register(array('App', 'hyyLoader'));


/*
 * ------------------------------------------------------
 *  启动框架
 * ------------------------------------------------------
 */
try {
    App::run();

} catch (Exception $e) {
    echo $e->getMessage();
}
