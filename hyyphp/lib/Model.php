<?php
/**
 * 公共模型类（数据库）
 * @Author: LongDH
 * @Date:   2017-11-05 17:27:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-05 18:24:02
 */
namespace hyyphp\lib;

use PDO;

class Model extends \PDO {
     /**
      * [__construct 初始化连接数据库]
      */
     public function __construct(){
          $dsn = 'mysql:dbname=test;host=118.89.54.158';
          $username = 'root';
          $password = '123456';
          try{
               parent::__construct($dsn, $username, $password);
          }catch (\PDOException $e) {
               throw new \Exception($e->getMessage());
          }
     }
}