<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-05 16:11:40
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-24 23:23:48
 */
namespace application\model;

use core\lib\Model;

class DemoModel extends BaseModel{
     public function demo() {
          $data = self::executeSQL('SELECT * FROM test', [], BD::DB_SELECT);
          return $data;
     }

}