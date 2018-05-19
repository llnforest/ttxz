<?php
namespace fanston\third;

use fanston\third\Weixin;
use think\Config;

class Jssdk {

  private static $appId = '';
  private static $appSecret = '';

  // public function __construct($appId, $appSecret) {
  //   $this->appId = $appId;
  //   $this->appSecret = $appSecret;
  // }

  public static function getSignPackage() {
    $app_name = Config::get('app_namespace');
    if($app_name == 'm'){
        self::$appSecret = Config::get('weixin_c.appsecret');
        self::$appId = Config::get('weixin_c.appid');
    }else{
        self::$appSecret = Config::get('weixin_b.appsecret');
        self::$appId = Config::get('weixin_b.appid');
    }
    $jsapiTicket = self::getJsApiTicket();
    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = self::createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => self::$appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private static function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private static function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    //$data = json_decode($this->get_php_file("jsapi_ticket.php"));
    $data = MyCache::get(MyCache::$WEIXIN_TICKET);
    if(empty($data)){
      $data = array('expire_time'=>0,'jsapi_ticket'=>'');
    }
    if ($data['expire_time'] < time() || $data['jsapi_ticket'] == '') {
      $accessToken = self::getAccessToken();
      // 如果是企业号用以下 URL 获取 ticket
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=".$accessToken;
      $res = json_decode(self::httpGet($url));
      $ticket = isset($res->ticket)?$res->ticket:'';
      // $ticket = 'kgt8ON7yVITDhtdwci0qeZ_wE-j6Nv7CeS4LCsolatLf6nEP9KY-PD3hESM0GLFXuLpI2GohTSvuvg-4oKddXw';
      if ($ticket) {
        $data['expire_time'] = time() + 7000;
        $$data['jsapi_ticket'] = $ticket;
        MyCache::set(MyCache::$WEIXIN_TICKET,$data);
      }
    } else {
      $ticket = $data['jsapi_ticket'];
    }

    return $ticket;
  }

  private static function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = MyCache::get(MyCache::$WEIXIN_ACCESS);if(empty($data)){
      $data = array('expire_time'=>0,'access_token'=>'');
    }
    if ($data['expire_time'] < time() || $data['access_token'] == '') {
      // 如果是企业号用以下URL获取access_token
      // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".self::$appId."&secret=".self::$appSecret;
      $res = json_decode(self::httpGet($url));
      $access_token = isset($res->access_token)?$res->access_token:'';
      if ($access_token) {
        $data['expire_time'] = time() + 7000;
        $data['access_token'] = $access_token;
        MyCache::set(MyCache::$WEIXIN_ACCESS,$data);
      }
    } else {
      $access_token = $data['access_token'];
    }
    return $access_token;
  }

  private static function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }

  private function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
  }
  private function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }
}

