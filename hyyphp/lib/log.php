<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-06 16:47:13
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-06 17:26:09
 */
namespace hyyphp\lib;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class log {

     public  $obj;
     public  $filename;

     public function __construct() {
          $this->obj = new Logger('name');

     }

     public function writeLog($filename, $msg, $type='error') {
          $data = date('Ymd');
          $file = LOG_PATH.$data;
          if(!is_dir($file)) {
               mkdir(iconv("UTF-8", "GBK", $file),0777,true);
          }

          if($type == 'error') {
               $this->obj->pushHandler(new StreamHandler($file.'/'.$filename.'.txt', Logger::ERROR));
               $this->obj->error($msg);
          }else if($type == 'warning') {
                $this->obj->pushHandler(new StreamHandler(LOG_PATH.$filename, Logger::WARNING));
                $this->obj->warning($msg);
          }

     }

}