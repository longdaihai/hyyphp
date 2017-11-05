<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 22:57:35
 */
namespace app\controller;

use app\model\DemoModel;

class IndexController extends BaseController{

     public function index() {
          $demo = new DemoModel();
          $demo->query("SELECT * FROM test");
          $ret = $demo->exec("SELECT * FROM test");
          $data = [
               'title' => '首页',
               'keywords' => '欢迎来到首页！'
          ];
          $this->assign('hyy', $data);

          p(config(null, 'db'));
          p(config(null, 'db'));

          $this->fetch('index/index');
     }

     public function demo() {
          $this->fetch();
     }
}