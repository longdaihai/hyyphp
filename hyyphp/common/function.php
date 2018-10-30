<?php
/**
 * 公共函数库
 * @Author: LongDH
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-30 14:49:33
 */

/**
 * [config 获取配置信息助手函数 默认config]
 * @param  string  $name [配置选项]
 * @param  string  $file [配置的文件名]
 * @return array
 */
function config($name=null, $file='config') {
    if($name==null) {
        return \hyyphp\lib\Config::getAll($file);
    }else {
        return \hyyphp\lib\Config::get($name, $file);
    }
}

function writeLog($filename, $msg, $type='error') {
    $log = new \hyyphp\lib\Log();
    $log->writeLog($filename, $msg, $type);
}

/**
 * 发起GET请求
 *
 * @param string $url
 * @return string content
 */
function http_get($url, $timeOut = 5, $connectTimeOut = 5) {
    $oCurl = curl_init ();
    if (stripos ( $url, "http://" ) !== FALSE || stripos ( $url, "https://" ) !== FALSE) {
        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
    }
    curl_setopt($oCurl, CURLOPT_URL, $url );
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeOut);
    curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);
    $sContent = curl_exec ( $oCurl );
    $aStatus = curl_getinfo ( $oCurl );
    $error = curl_error( $oCurl );
    curl_close ( $oCurl );
    if (intval ( $aStatus ["http_code"] ) == 200) {
        return array(
                'status' => true,
                'content' => $sContent,
                'code' => $aStatus ["http_code"],
          );
    } else {
        return array(
            'status' => false,
            'content' => json_encode(array("error" => $error, "url" => $url)),
            'code' => $aStatus ["http_code"],
        );
    }
}

/**
 * 发起POST请求
 *
 * @param string $url
 * @param array $param
 * @return string content
 */
function http_post($url, $param, $timeOut = 5, $connectTimeOut = 5) {
    $oCurl = curl_init ();
    if (stripos ( $url, "http://" ) !== FALSE || stripos ( $url, "https://" ) !== FALSE) {
        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
    }
    if (is_string ( $param )) {
        $strPOST = $param;
    } else {
        $aPOST = array ();
        foreach ( $param as $key => $val ) {
            $aPOST [] = $key . "=" . urlencode ( $val );
        }
        $strPOST = join ( "&", $aPOST );
    }
    curl_setopt($oCurl, CURLOPT_URL, $url );
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($oCurl, CURLOPT_POST, true );
    curl_setopt($oCurl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST );
    curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeOut);
    curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $connectTimeOut);
    $sContent = curl_exec ($oCurl );
    $aStatus = curl_getinfo ($oCurl );
    $error = curl_error($oCurl );
    curl_close ($oCurl );
    if (intval ($aStatus ["http_code"] ) == 200) {
        return array(
            'status' => true,
            'content' => $sContent,
            'code' => $aStatus ["http_code"],
        );
    } else {
        return array(
            'status' => false,
            'content' => json_encode(array("error" => $error, "url" => $url)),
            'code' => $aStatus ["http_code"],
        );
    }
}

/**
 * [json description]
 * @param  [type] $arr [description]
 * @return [type]      [description]
 */
function json($arr = []) {
    header('content-type:application/json;charset=utf8');
    if(!$arr || !is_array($arr)) {
        exit('参数出错！');
    }
    $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
    if (JSON_ERROR_NONE !== json_last_error()) {
        throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
    }
    return $data === null ? array() : $data;
}

/**
 * 响应返回JSON 格式数据
 * @param  [type] $arr [description]
 */
function returnJson($arr, $msg='', $data=[]){
    header('content-type:application/json;charset=utf8');
    if(!$arr) {
        exit('参数出错！');
    }
    if(is_array($arr)) {
        $res = json_encode($arr, JSON_UNESCAPED_UNICODE);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }
        exit($res);
    }
    if(is_numeric($arr)) {
        $res = [
            'code' => $arr,
            'msg'  => $msg,
            'data' => $data,
        ];
        $res = json_encode($res, JSON_UNESCAPED_UNICODE);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new RuntimeException('Unable to parse response body into JSON: ' . json_last_error());
        }
        exit($res);
    }
}

/**
 * 获取输入参数 支持过滤和默认值
 * 使用方法:
 * <code>
 * I('id',0); 获取id参数 自动判断get或者post
 * I('post.name','','htmlspecialchars'); 获取$_POST['name']
 * I('get.'); 获取$_GET
 * </code>
 * @param string $name 变量的名称 支持指定类型
 * @param mixed $default 不存在的时候默认值
 * @param mixed $filter 参数过滤方法
 * @param mixed $datas 要获取的额外数据源
 * @return mixed
 */
function I($name, $default = '', $filter = null, $datas = null) {
    static $_PUT = null;
    if (strpos($name, '/')) {
        // 指定修饰符
        list($name, $type) = explode('/', $name, 2);
    } elseif (\hyyphp\lib\Config::get('VAR_AUTO_STRING')) {
        // 默认强制转换为字符串
        $type = 's';
    }
    if (strpos($name, '.')) {
        // 指定参数来源
        list($method, $name) = explode('.', $name, 2);
    } else {
        // 默认为自动判断
        $method = 'param';
    }
    switch (strtolower($method)) {
        case 'get':
            $input = &$_GET;
            break;
        case 'post':
            $input = &$_POST;
            break;
        case 'put':
            if (is_null($_PUT)) {
                parse_str(file_get_contents('php://input'), $_PUT);
            }
            $input = $_PUT;
            break;
        case 'param':
            switch ($_SERVER['REQUEST_METHOD']) {
                case 'POST':
                    $input = $_POST;
                    break;
                case 'PUT':
                    if (is_null($_PUT)) {
                        parse_str(file_get_contents('php://input'), $_PUT);
                    }
                    $input = $_PUT;
                    break;
                default:
                    $input = $_GET;
            }
            break;
        case 'path':
            $input = array();
            if (!empty($_SERVER['PATH_INFO'])) {
                $depr  = \hyyphp\lib\Config::get('URL_PATHINFO_DEPR');
                $input = explode($depr, trim($_SERVER['PATH_INFO'], $depr));
            }
            break;
        case 'request':
            $input = &$_REQUEST;
            break;
        case 'session':
            $input = &$_SESSION;
            break;
        case 'cookie':
            $input = &$_COOKIE;
            break;
        case 'server':
            $input = &$_SERVER;
            break;
        case 'globals':
            $input = &$GLOBALS;
            break;
        case 'data':
            $input = &$datas;
            break;
        default:
            return null;
    }
    if ('' == $name) {
        // 获取全部变量
        $data    = $input;
        $filters = isset($filter) ? $filter : \hyyphp\lib\Config::get('DEFAULT_FILTER');
        if ($filters) {
            if (is_string($filters)) {
                $filters = explode(',', $filters);
            }
            foreach ($filters as $filter) {
                $data = array_map_recursive($filter, $data); // 参数过滤
            }
        }
    } elseif (isset($input[$name])) {
        // 取值操作
        $data    = $input[$name];
        $filters = isset($filter) ? $filter : \hyyphp\lib\Config::get('DEFAULT_FILTER');
        if ($filters) {
            if (is_string($filters)) {
                if (0 === strpos($filters, '/')) {
                    if (1 !== preg_match($filters, (string) $data)) {
                        // 支持正则验证
                        return isset($default) ? $default : null;
                    }
                } else {
                    $filters = explode(',', $filters);
                }
            } elseif (is_int($filters)) {
                $filters = array($filters);
            }

            if (is_array($filters)) {
                foreach ($filters as $filter) {
                    $filter = trim($filter);
                    if (function_exists($filter)) {
                        $data = is_array($data) ? array_map_recursive($filter, $data) : $filter($data); // 参数过滤
                    } else {
                        $data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));
                        if (false === $data) {
                            return isset($default) ? $default : null;
                        }
                    }
                }
            }
        }
        if (!empty($type)) {
            switch (strtolower($type)) {
                case 'a': // 数组
                    $data = (array) $data;
                    break;
                case 'd': // 数字
                    $data = (int) $data;
                    break;
                case 'f': // 浮点
                    $data = (float) $data;
                    break;
                case 'b': // 布尔
                    $data = (boolean) $data;
                    break;
                case 's': // 字符串
                default:
                    $data = (string) $data;
            }
        }
    } else {
        // 变量默认值
        $data = isset($default) ? $default : null;
    }
    is_array($data) && array_walk_recursive($data, 'think_filter');
    return $data;
}

function C($name=null, $file='config') {
    if($name==null) {
        return \hyyphp\lib\Config::getAll($file);
    }else {
        return \hyyphp\lib\Config::get($name, $file);
    }
}

/**
 * 浏览器友好的变量输出
 * @param mixed $var 变量
 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
 * @param string $label 标签 默认为空
 * @param boolean $strict 是否严谨 默认为true
 * @return void|string
 */
function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}


