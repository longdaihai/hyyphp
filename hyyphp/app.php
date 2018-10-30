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

use hyyphp\lib\Config;

class App {
    protected static $controller; //默认控制器
    protected static $method;     //默认方法
    protected static $pams     = array();   //其他参数
    protected static $classMap = array();   // 已加载的类

    /**
     * 项目的入口方法
     * @throws Exception
     */
    public static function run() {
        self::parseUrl();
        //得到控制器的路径
        $con_dir = APP_PATH . 'controller/' . self::$controller . EXT;
        // echo APP_PATH; exit;
        $controller = '\\' . MODULE . '\\controller\\' . self::$controller;
        // echo $con_dir; exit;
        //判断控制器文件是否存在
        if (file_exists($con_dir)) {
            $c = new $controller;
        } else {
            throw new Exception(self::$controller.' 控制器不存在！');
        }

        //执行方法
        if (method_exists($c, self::$method)) {
            $m = self::$method;
            $c->$m();
        } else {
            throw new Exception(self::$method.' 方法不存在！');
        }
        define('CONTROLLER_NAME', self::$controller);
        define('FUNCTION_NAME', self::$controller);

    }

    /**
     * url重写路由的URL地址解析方法
     */
    protected static function parseUrl() {
        //判断是否传了URL
        // print_r($_SERVER); exit;
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            // 解析 /index/index
            $path = $_SERVER['REQUEST_URI'];

            // 过滤?之后的参数
            $path = preg_replace("/\?.*/", "", $path);
            $url = explode('/', trim($path, '/'));
            $pathInfo = hyyphp\lib\Config::get('PATH_INFO');
            if($pathInfo == 1) {
                //得到控制器名称
                if (isset($url[0])) {
                    self::$controller = ucfirst($url[0]); //首字母大写
                    unset($url[0]);
                }
                //得到方法名
                if (isset($url[1])) {
                    self::$method = $url[1];
                    unset($url[1]);
                }else {
                     self::$method = \hyyphp\lib\Config::get('default_function');
                }
            }else {
                //得到控制器名称
                $controller = @$_GET['c'] ? $_GET['c'] : hyyphp\lib\Config::get('default_controller');
                self::$controller = ucfirst($controller);
                //得到方法名
                self::$method = @$_GET['a'] ? $_GET['a'] : hyyphp\lib\Config::get('default_function');
            }

            //判断是否还其他的参数
            if (isset($url)) {
                self::$pams = array_values($url);
            }
        }else {
            self::$controller = hyyphp\lib\Config::get('default_controller');
            self::$method = hyyphp\lib\Config::get('default_function');
        }
    }

    /**
     * 自动加载类方法
     * @param $className
     * @throws \Exception
     */
    public static function hyyLoader($className) {
        // 如果以加载过无需重复加载
        if(isset(self::$classMap[$className])) {
            return true;
        }else {

            //类文件所在的目录
            $classFile = ROOT_PATH . $className . EXT;
            $classFile = str_replace('\\', '/', $classFile);
            // echo $className;
            //判断类文件在哪个目录中
            if (file_exists($classFile)) {
                require_once $classFile;
                self::$classMap[$className] = $className;

            } else {
                throw new \Exception($classFile . ' 文件不存在！');
            }
        }

    }
}