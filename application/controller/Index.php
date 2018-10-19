<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-19 16:51:18
 */
namespace application\controller;

use application\model\DemoModel;

class Index extends Base{

     public function index() {
          $demo = new DemoModel();
          print_r($demo->demo());
     }

     public function demo() {}
}