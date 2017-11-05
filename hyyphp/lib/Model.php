<?php
/**
 * 公共模型类（数据库）
 * @Author: LongDH
 * @Date:   2017-11-05 17:27:03
 * @Last Modified by:   LongDH
 * @Last Modified time: 2017-11-06 00:04:23
 */
namespace hyyphp\lib;

use PDO;

class Model extends \PDO {
     /**
      * [__construct 初始化连接数据库]
      */
     public function __construct(){
          $config = Config::getAll('db');
          $dsn = "mysql:dbname={$config['DB_NAME']};host={$config['DB_HOST']}";
          $username = $config['DB_USER'];
          $password = $config['DB_PWD'];
          $charset = $config['DB_CHARSET'];
          $opts = [PDO::MYSQL_ATTR_INIT_COMMAND => 'set names '.$charset];

          try{
               parent::__construct($dsn, $username, $password, $opts);
          }catch (\PDOException $e) {
               throw new \Exception($e->getMessage());
          }
     }
}