<?php
namespace fanston\third;


class DaowayTool{
    public static $appKey = 'f35c0132c7d04354aafc5a9e2839b0d2';
    public static $appSecret = '0b52f81ffab94af1837cf443b8899f4e';
    // public static $serviceId = '475919fa61964569805c1b3a78896227';
    public static $serviceId = '00be8ab03c8344fdba46e6654014df2a';
    public static $notifyPayUrl = 'http://www.daoway.cn/daoway/rest/pay/third_pay';
    public static $orderStatusUrl = 'http://api.daoway.cn/daoway/rest/order_notify ';
    public static $product_id = 101;

    // public static $appKey = '3fb043cbb6d941baa92b089f81c94ed8';
    // public static $appSecret = '3a12a872ef95449db94a459fc10cf084';

    public static function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        // echo $data;
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    }
    public static function checkSign($data){
        $orderSign = isset($data['sign'])?$data['sign']:'';
        // print_r($data);
        // echo $post['sign'].'====';
        unset($data['sign']);
        ksort($data);
        // $post['appointTime'] = '2015-09-15 12:32:12';
        // echo http_build_query($post);
        $data['secret'] = self::$appSecret;
        // print_r($post);
        $mySign = strtoupper(md5(http_build_query($data)));
        if($mySign == $orderSign) return true;
        else false;
    }
    public static function notifyOrderStatus($order_id,$status,$note=''){
        $data = array(
            'appkey' => self::$appKey,
            'oncestr' => Makeid::makeOnceStr(32) ,
            'orderId' => $order_id,
            'status' =>  $status
            );
        if(!empty($note)){
            $data['note'] = $note;
        }
        $data = self::makeSign($data);
        return self::https_request(self::$orderStatusUrl,http_build_query($data));
    }
    public static function makeSign($data){
        ksort($data);
        $data['secret'] = self::$appSecret;
        $sign = strtoupper(md5(http_build_query($data)));
        unset($data['sign']);
        $data['sign'] = $sign;
        return $data;
    }

    public static function makeRsaSign($privatekeyFile,$string){
        $passphrase = '';
 
        // 摘要及签名的算法
        $digestAlgo = 'sha512';
        $algo = OPENSSL_ALGO_SHA1;
        // 加载私钥
        $privatekey = openssl_pkey_get_private(file_get_contents($privatekeyFile), $passphrase);
        // 生成摘要
        // $digest = openssl_digest($string, $digestAlgo);
         
        // 签名
        $signature = '';
        openssl_sign($string, $signature, $privatekey, $algo);
        $signature = base64_encode($signature);
        return $signature;
    }
    public static function resultToArray($s){
        $s = str_replace('\\x', '', $s);
        $s = str_replace('\\n', '', $s);
        // echo urldecode('');
        $result = json_decode($s,true);
        if(isset($result['msg']))
            $result['msg'] = hex2bin($result['msg']);
        else $result['msg'] = '';
        return $result;
    }
}