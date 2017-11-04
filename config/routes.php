<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-04 18:42:39
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-04 19:05:21
 */
use NoahBuscher\Macaw\Macaw;

// 默认控制器
Macaw::get('', 'IndexController@home');

Macaw::get('fuck', function() {
  echo "成功！";
});

Macaw::get('(:all)', function($fu) {
  echo '未匹配到路由<br>'.$fu;
});



Macaw::dispatch();