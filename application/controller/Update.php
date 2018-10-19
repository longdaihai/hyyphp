<?php
/**
 * @Author=> LongDH
 * @Date=>   2018-10-19 16=>07=>15
 * @Last Modified by=>   LongDH
 * @Last Modified time=> 2018-10-19 16=>12=>44
 */
namespace application\controller;

class Update extends Base {
     public function index() {

          $data = [
               'wgtURL' => 'http://wx.netup.vip/weixin/demoldh.php',
               'apkURL' => 'http://wx.netup.vip/weixin/demoldh.php',
               'ipaURL' => 'appStore中下载的地址',
               'version' => '1.0.0',
               'iOS'     => 1,
               'Android' => 1,
               'isforce' => 1,
          ];
          echo json($data);
     }

}