<?php
/**
 * 公共函数库
 * @Author: LongDH
 * @Date:   2017-11-04 21:18:46
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 22:48:29
 */

function demo() {
     echo '成功啦！';
}

/**
 * 浏览器友好的变量输出
 * @param mixed         $var 变量
 * @param boolean       $echo 是否输出 默认为true 如果为false 则返回输出字符串
 * @param string        $label 标签 默认为空
 * @param integer       $flags htmlspecialchars flags
 * @return void|string
 */
function p($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE) {
     $label = (null === $label) ? '' : rtrim($label) . ':';
     ob_start();
     var_dump($var);
     $output = ob_get_clean();
     $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
     if (IS_CLI) {
            $output = PHP_EOL . $label . $output . PHP_EOL;
     } else {
          if (!extension_loaded('xdebug')) {
               $output = htmlspecialchars($output, $flags);
          }
          $output = '<pre>' . $label . $output . '</pre>';
     }
     if ($echo) {
          echo($output);
          return;
     } else {
          return $output;
     }
}


/**
 * [config 获取配置信息助手函数 默认config]
 * @param  string  $name [配置选项]
 * @param  string  $file [配置的文件名]
 * @return array
 */
function config($name=null, $file='config') {
     return \hyyphp\lib\Config::get($name, $file);
}
