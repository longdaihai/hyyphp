<?php
/**
 * 路由配置
 * @Author: LongDH
 * @Date:   2017-11-04 20:36:13
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 05:12:44
 */
namespace hyy;

class Route {
    public $ctrl;
    public $action;

     /**
     * 1. 隐藏index.php
     * 2. 获取URL参数部分
     * 3. 返回对应的控制器和方法
     */
    public function __construct(){
        //教程中使用$_SERVER['REQUEST_URI']
        // p($_SERVER); exit;
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != '/'){
            // 解析 /index/index
            $path = $_SERVER['REQUEST_URI'];
            // 过滤?之后的参数
            $path = preg_replace("/\?.*/", "", $path);

            $patharr = explode('/', trim($path, '/'));
            p($path);
            // 控制器名称
            if(isset($patharr[0])){
                $this->ctrl = ucfirst($patharr[0]);
            }
            unset($patharr[0]);
            // 方法名
            if(isset($patharr[1])){
                $this->action = $patharr[1];
                unset($patharr[1]);
            }else{
                $this->action = 'index';
            }
            //url多余部分转换成 GET
            //id/1/str/2
            $count = count($patharr) + 2;
            $i = 2;
            while($i < $count){
                if(isset($patharr[$i + 1])){
                    $_GET[$patharr[$i]] = $patharr[$i + 1];
                }
                $i = $i + 2;
            }
            // p($_GET);
        }else{
            $this->ctrl = 'Index';
            $this->action = 'index';
        }
    }
}
