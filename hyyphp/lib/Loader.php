<?php
/**
 *
 * @Author: LongDH
 * @Date:   2017-11-04 23:27:58
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 05:12:20
 */
namespace hyy;

class Loader {
     public static $classMap = array();

     static public function run(){
          $route = new \hyy\Route();
          $ctrlClass = $route->ctrl;
          $action = $route->action;
          $ctrlFile = APP_PATH.'controller/'.$ctrlClass.'Controller.php';
          $ctrlClass = '\\'.MODULE.'\controller\\'.$ctrlClass.'Controller';
          echo $ctrlClass;
          if(is_file($ctrlFile)){
               include $ctrlFile;
               $ctrl = new $ctrlClass();
               $ctrl->$action();
          }else{
            // throw new \Excetion('找不到控制器'.$ctrlClass);
            echo '找不到控制器'; exit;
          }

     }
    /**
     * 自动加载类库
     *  new \hyy\lib\Route();
     *  $class = '\core\route';
     */
     static public function load($class){
          if(isset($classMap[$class])){
               return true;
          }else{
               $class = str_replace('\\', '/', $class);
               $file = APP_PATH.'controller/'.$class.'.php';
               if(is_file($file)){
                    include $file;
                    self::$classMap[$class] = $class;
               }else{
                    return false;
               }
          }
     }

}
