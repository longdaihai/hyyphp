<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:56:37
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-07 16:56:20
 */
namespace app\controller;

use hyyphp\lib\Controller;

class BaseController extends Controller{

     public function index() {
          dump($_SERVER); die();
          echo 'Base/index';
     }
}