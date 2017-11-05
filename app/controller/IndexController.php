<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 20:45:55
 */
namespace app\controller;

use app\model\DemoModel;

class IndexController extends BaseController{

     public function index() {
          $demo = new DemoModel();
          $demo->query("SELECT * FROM test");
          $ret = $demo->exec("SELECT * FROM test");
          // p($ret);
          //
          $this->assign('hyy', 'www.haiyunyi.cn');

          $this->fetch('index/index');
     }

     public function demo() {
          $this->fetch();
     }
}