<?php
// +----------------------------------------------------------------------
// | HYYPHP [ WE CAN DO IT JUST HYYPHP ]
// +----------------------------------------------------------------------
// | Copyright (c) HanSheng All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: HanSheng <164897033@qq.com>
// +----------------------------------------------------------------------
namespace controller;

use model\DemoModel;

class Index extends Base{

    public function index() {
        $this->fetch('index/index');
    }

    public function demo() {
        $demo = new DemoModel();
        dump($demo->testDB());
//        session('name', 'ldhsadas');
//        dump(session('name'));

    }
}