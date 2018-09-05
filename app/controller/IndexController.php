<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-06 17:20:38
 */
namespace app\controller;

use app\model\DemoModel;

class IndexController extends BaseController{

     public function index() {
          $demo = new DemoModel();
          $ret = $demo->query("SELECT * FROM user");

          writeLog('Index-index', '测试');

          $this->assign('hyy', '海云亿');
          $this->fetch('index/index');

     }

     public function demo() {
          $this->fetch();
     }
}