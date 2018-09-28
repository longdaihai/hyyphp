<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:58:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-09-29 02:06:40
 */
namespace app\controller;

use app\model\DemoModel;

class IndexController extends BaseController{

     public function index() {
          // $demo = new DemoModel();
          // $ret = $demo->query("SELECT * FROM user");

          // writeLog('Index-index', '测试');
          $data = [
               ['name' => 'zhans', 'age'=>18],
               ['name' => 'lus', 'age'=>12],
               ['name' => 'asd', 'age'=>21],
          ];
          $this->assign('hyy', $data);
          $this->assign('name', '海云亿');
          // $this->assign('hyy', '海云亿');
          $this->fetch('index/index');

     }

     public function demo() {
          $this->fetch('index/demo');
     }

     public function test() {
          echo 11;
     }
}