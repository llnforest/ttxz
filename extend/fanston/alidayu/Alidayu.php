<?php
namespace fanston\alidayu;
include "TopSdk.php";
use think\Config;
use TopClient;
use AlibabaAliqinFcSmsNumSendRequest;

class Alidayu{
	public static $appkey;
	public static $secretKey;
	public static $expresstel;
	public static $service;
	public static $blacklist;
	public $c;
	public $req;

	public function __construct(){
	    self::$appkey = Config::get('sms.appkey');
	    self::$secretKey = Config::get('sms.secretKey');
        self::$expresstel = '0551-66104389';
        self::$service = Config::get('service');
        self::$blacklist = ['15212998586', '13705680023', '18298116281', '15155466688', '18019966168', '13681773807'];

        $this->c = new TopClient;
        $this->c->appkey = self::$appkey;
        $this->c->secretKey = self::$secretKey;
        $this->req = new AlibabaAliqinFcSmsNumSendRequest;
        $this->req ->setSmsType("normal");
        $this->req ->setSmsFreeSignName("天天旋转");
    }

    //发送验证码
	public function sendSms($phone,$val){
		$this->req->setSmsParam("{\"number\":\"".$val."\"}");
		$this->req->setRecNum($phone);
		$this->req->setSmsTemplateCode("SMS_71360978");
		$resp = $this->c->execute($this->req);
		return $resp;
	}

}

?>