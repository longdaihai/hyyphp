<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 23:55:36
 */
namespace app\controller;

use app\model\DemoModel;

class IndexController extends BaseController{

     public function index() {
          $demo = new DemoModel();
          $ret = $demo->query("SELECT * FROM user");
          $data = [
               'title' => '首页',
               'keywords' => '欢迎来到首页！'
          ];

          print_r($ret->fetchAll());

     }

     public function demo() {
          $this->fetch();
     }
}