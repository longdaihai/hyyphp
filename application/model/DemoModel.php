<?php
/**
 * @Author: LongDH
 * @Date:   2017-11-05 16:11:40
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-19 16:50:44
 */
namespace application\model;

use core\lib\Model;

class DemoModel extends BaseModel{
     public function demo() {
          $data = self::executeSQL('SELECT * FROM udo_wx_udo_wx_material_img', [], BD::DB_SELECT);
          return $data;
     }

}