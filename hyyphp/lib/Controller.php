<?php
/**
 * 基类
 * @Author: LongDH
 * @Date:   2017-11-05 05:11:30
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 21:07:17
 */
namespace hyyphp\lib;

class Controller {

     public function __construct() {
          // 模板解析变量传值
          $this->template = new \hyyphp\lib\template\Action();
     }

     public function assign($tpl_var, $var) {
          if($tpl_var && $var) {
               $this->template->assign($tpl_var,$var);
          }else {
               exit('模板变量名没有设置好');
          }
     }

     public function fetch($file) {
          $this->template->display($file);
     }
}