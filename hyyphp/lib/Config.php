<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-05 22:06:36
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 23:28:34
 */
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
               $path = CONF_PATH . $file . '.php';
               if(file_exists($path)) {
                    $conf = require_once $path;
                    if(isset($conf[$name])) {
                        self::$conf[$file] = $conf;
                        return $conf[$name];

                    }else {
                        throw new \Exception($name . " 配置项不存在！", 1);

                    }
               }else {
                    throw new \Exception($file . " 配置文件不存在！", 1);

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