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
namespace hyyphp\lib;

class Config {
    public static $conf = [];

    /**
    * [config 获取配置信息助手函数 默认config]
    * @param  string  $name [配置选项]
    * @param  string  $file [配置的文件名]
     * @return array
    */
    public static function get($name=null, $file='config') {
        if(isset(self::$conf[$file])) {
            return self::$conf[$file][$name];

        }else {
            $userPath = APP_PATH . 'config/' . $file . '.php'; //用户自定义配置文件
            $path     = CONF_PATH . $file . '.php'; // 系统惯例配置
            if(file_exists($userPath)) {
                $conf = require_once $userPath;
            }else {
                if(file_exists($path)) {
                    $conf = require_once $path;
                }else {
                    throw new \Exception($file . " 配置文件不存在！", 1);
                }
            }
            if(isset($conf[$name])) {
                self::$conf[$file] = $conf;
                return $conf[$name];
            }else {
                throw new \Exception($name . " 配置项不存在！", 1);
            }
        }
    }

     public static function getAll($file='config') {
          if(isset(self::$conf[$file])) {
               return self::$conf[$file];
          }else {
               $path = CONF_PATH . $file . '.php';
               if(file_exists($path)) {
                    $conf = require_once $path;
                    self::$conf[$file] = $conf;
                    return self::$conf[$file];

               }else {
                    throw new \Exception($file . " 配置文件不存在！", 1);
               }
          }
     }

}