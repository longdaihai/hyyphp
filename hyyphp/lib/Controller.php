<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-05 05:11:30
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 19:38:18
 */
namespace hyyphp\lib;

class Controller {
     public function __construct() {

     }

     public function assign($tpl_var, $var) {
          $template = new \hyyphp\lib\template\Action();
          $template -> assign($tpl_var, $var);
     }

     public function fetch($file) {
          $template = new \hyyphp\lib\template\Action();
          $template -> display($file);
     }
}