<?php
/**
 * @Author: LongDH
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-11-01 13:54:40
 */
namespace application\controller;

class Test extends Base{
    public function index() {
        $this -> fetch('index');
        echo 'test--index';

    }
}
