<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:42:39
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-04 20:54:24
 */
use hyyphp\hyyphp_core\core;

// 默认控制器
Route::get('', 'IndexController@home');

Route::get('fuck', function() {
  echo "成功！";
});

Route::get('(:all)', function($fu) {
  echo '未匹配到路由<br>'.$fu;
});



Route::dispatch();