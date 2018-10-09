<?php
/**
 * 公共函数库
 * @Author: LongDH
 * @Date:   2017-11-04 21:18:46
 * @Last Modified by:   LongDH
 * @Last Modified time: 2018-10-09 23:20:32
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
