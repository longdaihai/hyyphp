<?php
/**
 * 公共函数库
 * @Author: LongDH
 * @Date:   2017-11-04 21:18:46
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 03:35:08
 */

function demo() {
     echo '成功啦！';
}

function p($var){
     if(is_bool($var)){
          var_dump($var);
     }else if(is_null($var)){
          var_dump(NULL);
     }else{
          echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>".print_r($var, true)."</pre>";
     }
}
