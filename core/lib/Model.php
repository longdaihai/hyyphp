<?php
// +----------------------------------------------------------------------
// | HYYPHP [ WE CAN DO IT JUST HYYPHP ]
// +----------------------------------------------------------------------
// | Copyright (c) HanSheng All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: HanSheng <164897033@qq.com>
// +----------------------------------------------------------------------
namespace core\lib;

use \PDO;

/**
　* 数据库PDO操作
　*/
class Model {

    const DB_SELECT = 0; //查询
    const DB_INSERT = 1; //插入
    const DB_UPDATE = 2; //更新
    const DB_DELETE = 2; //删除

    public static $PDOStatement = null;

    /**
     * 数据库的连接参数配置
     * @var array
     * @access public
     */
    public static $config = array();

    /**
     * 是否使用永久连接
     * @var bool
     * @access public
     */
    public static $pconnect = false;

    /**
     * 错误信息
     * @var string
     * @access public
     */
    public static $error = '';

    /**
     * 单件模式,保存Pdo类唯一实例,数据库的连接资源
     * @var object
     * @access public
     */
    protected static $link;

    /**
     * 是否已经连接数据库
     * @var bool
     * @access public
     */
    public static $connected = false;

    /**
     * 数据库版本
     * @var string
     * @access public
     */
    public static $dbVersion = null;

    /**
     * 当前SQL语句
     * @var string
     * @access public
     */
    public static $queryStr = '';

    /**
     * 最后插入记录的ID
     * @var integer
     * @access public
     */
    public static $lastInsertId = null;

    /**
     * 返回影响记录数
     * @var integer
     * @access public
     */
    public static $numRows = 0;

    // 事务指令数
    public static $transTimes = 0;

    /**
     * 构造函数，
     * @param $dbconfig 数据库连接相关信息，array('ServerName', 'UserName', 'Password', 'DefaultDb', 'DB_Port', 'DB_TYPE')
     */
    public function __construct($dbConfig=''){
        if (!class_exists('PDO')) self::throw_exception("不支持:PDO");

        $dbConfig = Config::getAll('db');

        if(!is_array($dbConfig)) {
            return false;
        }

        $dbhost   = $dbConfig['DB_HOST'];
        $username = $dbConfig['DB_USER'];
        $dbname   = $dbConfig['DB_NAME'];
        $password = $dbConfig['DB_PWD'];
        $port     = $dbConfig['DB_PORT'];
        $charset  = $dbConfig['DB_CHARSET'];
        $dsn      = "mysql:dbname={$dbname};host={$dbhost};port={$port}";

        if(empty($dbhost)) self::throw_exception("没有定义数据库配置");
        self::$config = $dbConfig;
        if(empty(self::$config['params'])) self::$config['params'] = array();

        /*************************************华丽分隔线*******************************************/

        if (!isset(self::$link) ) {
            $configs = self::$config;
            if(self::$pconnect) {
                $configs['params'][constant('PDO::ATTR_PERSISTENT')] = true;
            }
            try {
                self::$link = new PDO($dsn, $username, $password, $configs['params']);
            } catch (PDOException $e) {
                self::throw_exception($e->getMessage());
            }
            if(!self::$link) {
                self::throw_exception('PDO CONNECT ERROR');
                return false;
            }
            self::$link->exec('SET NAMES '.$charset);
            self::$dbVersion = self::$link->getAttribute(constant("PDO::ATTR_SERVER_INFO"));

            // 标记连接成功
            self::$connected = true;

            // 注销数据库连接配置信息
            unset($configs);
        }
        return self::$link;
    }

    /**
     * 释放查询结果
     *
     */
    static function free() {
        self::$PDOStatement = null;
    }

    /*********************************************************************************************************/
    /* 数据库操作 */
    /*********************************************************************************************************/
    /**
     * 执行SQL方法 强制使用预处理
     * @param $str_sql 执行的SQL语句
     * @param array $arr_data 数据数组
     * @param int $status 0代表查询语句  1代表增删除修改语句
     * @return array|bool 查询成功返回数据 失败返回NULL  新增成功返回插入id  失败false  修改返回受影响条数
     */
    public function executeSQL($str_sql, $arr_data = [], $int_type = 0,$str_dbname=''){
        try {
            $obj_pdo = self::$link;
            if ($int_type == self::DB_SELECT) {//判断是查询语句
                if (strpos($str_sql, '?') && !empty($arr_data) && count($arr_data) > 0) {
                    $obj_tate = $obj_pdo->prepare($str_sql);
                    $arr_data = self::validateParng($arr_data);//数据存储前的转译
                    $obj_tate->execute($arr_data);
                    $obj_tate->setFetchMode(PDO::FETCH_ASSOC);
                    return $obj_tate->fetchAll();
                } else {
                    $obj_res = $obj_pdo->query($str_sql);
                    return $obj_res->fetchAll(PDO::FETCH_ASSOC);
                }
            }
            if (strpos($str_sql,'?') !==false && !empty($arr_data) && count($arr_data) > 0) {
                $obj_tate = $obj_pdo->prepare($str_sql);
                $arr_data = self::validateParng($arr_data);
                $obj_execteRes =  $obj_tate->execute($arr_data);
                if($obj_execteRes > 0 && $int_type == self::DB_INSERT){
                    return $obj_pdo->lastInsertId();
                }
                return $obj_tate->rowCount();
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
            exit;
        }
    }

    /**
     * 校验存入数据的数据 必须经过转译
     */
    public static function validateParng($arr_data){
        if(is_array($arr_data)&&count($arr_data)>0){
            $arr_data = array_values($arr_data);
            $arr_validateData = array();
            foreach($arr_data as $value){
                array_push($arr_validateData,htmlspecialchars($value));
            }
            return $arr_validateData;
        }else{
            throw new \Exception('参数不匹配');
        }
    }

    /**
     * 获得所有的查询数据
     *
     * @return array
     */
    public function getAll($sql=null) {
        if($sql != null)
        {
            self::query($sql);
        }
        //返回数据集
        $result = self::$PDOStatement->fetchAll(constant('PDO::FETCH_ASSOC'));
        return $result;
    }

    /**
     * 获得一条查询结果
     *
     * @param string $sql SQL指令
     * @param integer $seek 指针位置
     * @return array
     */
    static function getRow($sql=null) {
        if($sql != null)
        {
            self::query($sql);
        }
        // 返回数组集
        $result = self::$PDOStatement->fetch(constant('PDO::FETCH_ASSOC'),constant('PDO::FETCH_ORI_NEXT'));
        return $result;
    }

    /**
     * 执行sql语句，自动判断进行查询或者执行操作
     *
     * @param string $sql SQL指令
     * @return mixed
     */
    static function doSql($sql='') {
        if(self::isMainIps($sql)) {
            return self::execute($sql);
        }else {
            return self::getAll($sql);
        }
    }

    /**
     * 根据指定ID查找表中记录(仅用于单表操作)
     *
     * @param integer $priId 主键ID
     * @param string $tables 数据表名
     * @param string $fields 字段名
     * @return ArrayObject 表记录
     */
    static function findById($tabName,$priId,$fields='*'){
        $sql = 'SELECT %s FROM %s WHERE id=%d';
        return self::getRow(sprintf($sql, self::parseFields($fields), $tabName, $priId));
    }

    /**
     * 查找记录
     *
     * @param string $tables 数据表名
     * @param mixed $where 查询条件
     * @param string $fields 字段名
     * @param string $order 排序
     * @param string $limit 取多少条数据
     * @param string $group 分组
     * @param string $having
     * @param boolean $lock 是否加锁
     * @return ArrayObject
     */
    static function find($tables,$where="",$fields='*',$order=null,$limit=null,$group=null,$having=null) {
        $sql = 'SELECT '.self::parseFields($fields)
            .' FROM '.$tables
            .self::parseWhere($where)
            .self::parseGroup($group)
            .self::parseHaving($having)
            .self::parseOrder($order)
            .self::parseLimit($limit);
        $dataAll = self::getAll($sql);
        if(count($dataAll)==1){$rlt=$dataAll[0];}else{$rlt=$dataAll;}
        return $rlt;
    }

    /**
     * 插入（单条）记录
     *
     * @param mixed $data 数据
     * @param string $table 数据表名
     * @return false | integer
     */
    function add($data,$table) {
        //过滤提交数据
        $data=self::filterPost($table,$data);
        foreach ($data as $key=>$val){
            if(is_array($val) && strtolower($val[0]) == 'exp') {
                $val = $val[1]; // 使用表达式 ???
            }elseif (is_scalar($val)){
                $val = self::fieldFormat($val);
            }else{
                // 去掉复合对象
                continue;
            }
            $data[$key] = $val;
        }
        $fields = array_keys($data);
        array_walk($fields, array($this, 'addSpecialChar'));
        $fieldsStr = implode(',', $fields);
        $values = array_values($data);
        $values = self::validateParng($values); //数据存储前的转译
        $valuesStr = implode(',', $values);
        $sql = 'INSERT INTO '.$table.' ('.$fieldsStr.') VALUES ('.$valuesStr.')';
        return self::execute($sql);
    }

    /**
     * 更新记录
     *
     * @param mixed $sets 数据
     * @param string $table 数据表名
     * @param string $where 更新条件
     * @param string $limit
     * @param string $order
     * @return false | integer
     */
    static function update($sets,$table,$where,$limit=0,$order='') {
        $sets = self::filterPost($table,$sets);
        $sql = 'UPDATE '.$table.' SET '.self::parseSets($sets).self::parseWhere($where).self::parseOrder($order).self::parseLimit($limit);
        return self::execute($sql);
    }

    /**
     * 保存某个字段的值
     *
     * @param string $field 要保存的字段名
     * @param string $value 字段值
     * @param string $table 数据表
     * @param string $where 保存条件
     * @param boolean $asString 字段值是否为字符串
     * @return void
     */
    static function setField($field, $value, $table, $condition="", $asString=false) {
        // 如果有'(' 视为 SQL指令更新 否则 更新字段内容为纯字符串
        if(false === strpos($value,'(') || $asString) $value = '"'.$value.'"';
        $sql = 'UPDATE '.$table.' SET '.$field.'='.$value.self::parseWhere($condition);
        return self::execute($sql);
    }

    /**
     * 删除记录
     *
     * @param mixed $where 为条件Map、Array或者String
     * @param string $table 数据表名
     * @param string $limit
     * @param string $order
     * @return false | integer
     */
    static function remove($where,$table,$limit='',$order='') {
        $sql = 'DELETE FROM '.$table.self::parseWhere($where).self::parseOrder($order).self::parseLimit($limit);
        return self::execute($sql);
    }

    /**
    +----------------------------------------------------------
     * 修改或保存数据(仅用于单表操作)
     * 有主键ID则为修改，无主键ID则为增加
     * 修改记录：
    +----------------------------------------------------------
     *
    +----------------------------------------------------------
     * @param $tabName 表名
     * @param $aPost 提交表单的 $_POST
     * @param $priId 主键ID
     * @param $aNot 要排除的一个字段或数组
     * @param $aCustom 自定义的一个数组，附加到数据库中保存
     * @param $isExits 是否已经存在 存在：true, 不存在：false
    +----------------------------------------------------------
     * @return Boolean 修改或保存是否成功
    +----------------------------------------------------------
     */
    function saveOrUpdate($tabName, $aPost, $priId="", $aNot="", $aCustom="", $isExits=false) {
        if(empty($tabName) || !is_array($aPost) || is_int($aNot)) return false;
        if(is_string($aNot) && !empty($aNot)) $aNot = array($aNot);
        if(is_array($aNot) && is_int(key($aNot))) $aPost = array_diff_key($aPost, array_flip($aNot));
        if(is_array($aCustom) && is_string(key($aCustom))) $aPost = array_merge($aPost,$aCustom);
        if (empty($priId) && !$isExits) { //新增
            $aPost = array_filter($aPost, array($this, 'removeEmpty'));
            return self::add($aPost, $tabName);
        } else { //修改
            return self::update($aPost, $tabName, "id=".$priId);
        }
    }

    /**
     * 获取最近一次查询的sql语句
     *
     * @param
     * @return String 执行的SQL
     */
    static function getLastSql() {
        $link = self::$link;
        if ( !$link ) return false;
        return self::$queryStr;
    }

    /**
     * 获取最后插入的ID
     *
     * @param
     * @return integer 最后插入时的数据ID
     */
    static function getLastInsId(){
        $link = self::$link;
        if ( !$link ) return false;
        return self::$lastInsertId;
    }

    /**
     * 获取DB版本
     *
     * @param
     * @return string
     */
    static function getDbVersion(){
        $link = self::$link;
        if ( !$link ) return false;
        return self::$dbVersion;
    }

    /**
     * 取得数据库的表信息
     *
     * @return array
     */
    static function getTables() {
        $info = array();
        if(self::query("SHOW TABLES")) {
            $result = self::getAll();
            foreach ($result as $key => $val) {
                $info[$key] = current($val);
            }
        }
        return $info;
    }

    /**
     * 取得数据表的字段信息
     *
     * @return array
     */
    static function getFields($tableName) {
        // 获取数据库联接
        $link = self::$link;
        $sql = "SELECT
        ORDINAL_POSITION ,COLUMN_NAME, COLUMN_TYPE, DATA_TYPE,
        IF(ISNULL(CHARACTER_MAXIMUM_LENGTH), (NUMERIC_PRECISION + NUMERIC_SCALE), CHARACTER_MAXIMUM_LENGTH) AS MAXCHAR,
        IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY, EXTRA, COLUMN_COMMENT
        FROM
        INFORMATION_SCHEMA.COLUMNS
        WHERE
        TABLE_NAME = :tabName AND TABLE_SCHEMA='".DB_NAME."'";
        self::$queryStr = sprintf($sql, $tableName);
        $sth = $link->prepare($sql);
        $sth->bindParam(':tabName', $tableName);
        $sth->execute();
        $result = $sth->fetchAll(constant('PDO::FETCH_ASSOC'));
        $info = array();
        foreach ($result as $key => $val) {
            $info[$val['COLUMN_NAME']] = array(
                'postion' => $val['ORDINAL_POSITION'],
                'name' => $val['COLUMN_NAME'],
                'type' => $val['COLUMN_TYPE'],
                'd_type' => $val['DATA_TYPE'],
                'length' => $val['MAXCHAR'],
                'notnull' => (strtolower($val['IS_NULLABLE']) == "no"),
                'default' => $val['COLUMN_DEFAULT'],
                'primary' => (strtolower($val['COLUMN_KEY']) == 'pri'),
                'autoInc' => (strtolower($val['EXTRA']) == 'auto_increment'),
                'comment' => $val['COLUMN_COMMENT']
            );
        }
        // 有错误则抛出异常
        self::haveErrorThrowException();
        return $info;
    }

    /**
     * 关闭数据库
     *
     */
    static function close() {
        self::$link = null;
    }

    /**
     * SQL指令安全过滤
     *
     * @param string $str SQL指令
     * @return string
     */
    static function escape_string($str) {
        return addslashes($str);
    }

    /*********************************************************************************************************/
    /* 内部操作方法 */
    /*********************************************************************************************************/
    /**
     * 有出错抛出异常
     *
     * @return
     */
    static function haveErrorThrowException() {
        $obj = empty(self::$PDOStatement) ? self::$link : self::$PDOStatement;
        $arrError = $obj->errorInfo();
        if($arrError[0] !== '00000') { // 有错误信息
            self::$error = $arrError[0]."|".$arrError[2]. "<br/>[ SQL ] : ".self::$queryStr."<br/>";
            self::throw_exception(self::$error);
            return false;
        }
        //主要针对execute()方法抛出异常
        if(self::$queryStr=='')self::throw_exception('Query was empty<br/><br/>[ SQL语句 ] :');
    }

    /**
     * where分析
     *
     * @param mixed $where 查询条件
     * @return string
     */
    static function parseWhere($where) {
        $whereStr = '';
        if(is_string($where) || is_null($where)) {
            $whereStr = $where;
        }
        return empty($whereStr)?'':' WHERE '.$whereStr;
    }

    /**
     * order分析
     *
     * @param mixed $order 排序
     * @return string
     */
    static function parseOrder($order) {
        $orderStr = '';
        if(is_array($order))
            $orderStr .= ' ORDER BY '.implode(',', $order);
        else if(is_string($order) && !empty($order))
            $orderStr .= ' ORDER BY '.$order;
        return $orderStr;
    }

    /**
     * limit分析
     *
     * @param string $limit
     * @return string
     */
    static function parseLimit($limit) {
        $limitStr = '';
        if(is_array($limit)) {
            if(count($limit)>1)
                $limitStr .= ' LIMIT '.$limit[0].' , '.$limit[1].' ';
            else
                $limitStr .= ' LIMIT '.$limit[0].' ';
        } else if(is_string($limit) && !empty($limit)) {
            $limitStr .= ' LIMIT '.$limit.' ';
        }
        return $limitStr;
    }

    /**
     * group分析
     *
     * @param mixed $group
     * @return string
     */
    static function parseGroup($group) {
        $groupStr = '';
        if(is_array($group))
            $groupStr .= ' GROUP BY '.implode(',', $group);
        else if(is_string($group) && !empty($group))
            $groupStr .= ' GROUP BY '.$group;
        return empty($groupStr)?'':$groupStr;
    }

    /**
     * having分析
     *
     * @param string $having
     * @return string
     */
    static function parseHaving($having) {
        $havingStr = '';
        if(is_string($having) && !empty($having))
            $havingStr .= ' HAVING '.$having;
        return $havingStr;
    }

    /**
     * fields分析
     *
     * @param mixed $fields
     * @return string
     */
    function parseFields($fields) {
        if(is_array($fields)) {
            array_walk($fields, array($this, 'addSpecialChar'));
            $fieldsStr = implode(',', $fields);
        }else if(is_string($fields) && !empty($fields)) {
            if( false === strpos($fields,'`') ) {
                $fields = explode(',',$fields);
                array_walk($fields, array($this, 'addSpecialChar'));
                $fieldsStr = implode(',', $fields);
            }else {
                $fieldsStr = $fields;
            }
        }else $fieldsStr = '*';
        return $fieldsStr;
    }

    /**
     * sets分析,在更新数据时调用
     *
     * @param mixed $values
     * @return string
     */
    private function parseSets($sets) {
        $setsStr = '';
        if(is_array($sets)){
            foreach ($sets as $key=>$val){
                $key = self::addSpecialChar($key);
                $val = self::fieldFormat($val);
                $setsStr .= "$key = ".$val.",";
            }
            $setsStr = substr($setsStr,0,-1);
        }else if(is_string($sets)) {
            $setsStr = $sets;
        }
        return $setsStr;
    }

    /**
     * 字段格式化
     *
     * @param mixed $value
     * @return mixed
     */
    static function fieldFormat(&$value) {
        if(is_int($value)) {
            $value = intval($value);
        } else if(is_float($value)) {
            $value = floatval($value);
        } elseif(preg_match('/^\w∗(\+|\-|\*|\/)?\w∗$/i',$value)){
            // 支持在字段的值里面直接使用其它字段
            // 例如 (score+1) (name) 必须包含括号
            $value = $value;
        }else if(is_string($value)) {
            $value = '\''.self::escape_string($value).'\'';
        }
        return $value;
    }

    /**
     * 字段和表名添加` 符合
     * 保证指令中使用关键字不出错 针对mysql
     *
     * @param mixed $value
     * @return mixed
     */
    static function addSpecialChar(&$value) {
        if( '*' == $value || false !== strpos($value,'(') || false !== strpos($value,'.') || false !== strpos($value,'`')) {
            //如果包含* 或者 使用了sql方法 则不作处理
        } elseif(false === strpos($value,'`') ) {
            $value = '`'.trim($value).'`';
        }
        return $value;
    }

    /**
    +----------------------------------------------------------
     * 去掉空元素
    +----------------------------------------------------------
     *
    +----------------------------------------------------------
     * @param mixed $value
    +----------------------------------------------------------
     * @return mixed
    +----------------------------------------------------------
     */
    static function removeEmpty($value){
        return !empty($value);
    }

    /**
     * 执行查询 主要针对 SELECT, SHOW 等指令
     *
     * @param string $sql sql指令
     * @return mixed
     */
    static function query($sql='') {
        // 获取数据库联接
        $link = self::$link;
        if ( !$link ) return false;
        self::$queryStr = $sql;
        //释放前次的查询结果
        if ( !empty(self::$PDOStatement) ) self::free();
        self::$PDOStatement = $link->prepare(self::$queryStr);
        $bol = self::$PDOStatement->execute();
        // 有错误则抛出异常
        self::haveErrorThrowException();
        return $bol;
    }

    /**
     * 数据库操作方法
     *
     * @param string $sql 执行语句
     * @param boolean $lock 是否锁定(默认不锁定)
     * @return void
    public function execute($sql='',$lock=false) {
    if(empty($sql)) $sql = $this->queryStr;
    return $this->_execute($sql);
    }*/
    /**
     * 执行语句 针对 INSERT, UPDATE 以及DELETE
     *
     * @param string $sql sql指令
     * @return integer
     */
    static function execute($sql='') {
        // 获取数据库联接
        $link = self::$link;
        if ( !$link ) return false;
        self::$queryStr = $sql;
        //释放前次的查询结果
        if ( !empty(self::$PDOStatement) ) self::free();
        $result = $link->exec(self::$queryStr);
        // 有错误则抛出异常
        self::haveErrorThrowException();
        if ( false === $result) {
            return false;
        } else {
            self::$numRows = $result;
            self::$lastInsertId = $link->lastInsertId();
            return self::$numRows;
        }
    }
    /**
     * 是否为数据库更改操作
     * @access private
     * @param string $query SQL指令
     * @return boolen 如果是查询操作返回false
     */
    static function isMainIps($query) {
        $queryIps = 'INSERT|UPDATE|DELETE|REPLACE|CREATE|DROP|LOAD DATA|SELECT .* INTO|COPY|ALTER|GRANT|REVOKE|LOCK|UNLOCK';
        if (preg_match('/^\s*"?(' . $queryIps . ')\s+/i', $query)) {
            return true;
        }
        return false;
    }

    /**
     * 过滤POST提交数据
     * @access private
     * @param mixed $data POST提交数据
     * @param string $table 数据表名
     * @return mixed $newdata
     */
    static function filterPost($table,$data) {
        $table_column = self::getFields($table);
        $newdata=array();
        foreach ($table_column as $key=>$val){
            if(array_key_exists($key,$data) && ($data[$key])!==''){
                $newdata[$key] = $data[$key];
            }
        }
        return $newdata;
    }

    /**
     * 启动事务
     *
     * @return void
     */
    static function startTrans() {
        //数据rollback 支持
        $link = self::$link;
        if ( !$link ) return false;
        if (self::$transTimes == 0) {
            $link->beginTransaction();
        }
        self::$transTimes++;
        return;
    }

    /**
     * 用于非自动提交状态下面的查询提交
     *
     * @return boolen
     */
    static function commit() {
        $link = self::$link;
        if ( !$link ) return false;
        if (self::$transTimes > 0) {
            $result = $link->commit();
            self::$transTimes = 0;
            if(!$result){
                self::throw_exception(self::$error());
                return false;
            }
        }
        return true;
    }

    /**
     * 事务回滚
     *
     * @return boolen
     */
    public function rollback() {
        $link = self::$link;
        if ( !$link ) return false;
        if (self::$transTimes > 0) {
            $result = $link->rollback();
            self::$transTimes = 0;
            if(!$result){
                self::throw_exception(self::$error());
                return false;
            }
        }
        return true;
    }

    /**
     * 错误处理
     *
     * @return void
     */
    static function throw_exception($err){
        echo '<div style="width:500px;background-color:#CDCDCD; color:#A00;font-size:14px;border:1px #D40000 solid; margin:2px;padding:6px;">ERROR:'.$err.'</div>';
    }
}