<?php
namespace fanston\third;

class CallExpress{

    private static $appkey = 'd29e2ee8bac9de31f12234a9530393b1'; //申请的快递查询APPKEY
    private static $callUrl = 'http://v.juhe.cn/expressonline/expressSend.php';
    private static $cancleUrl = 'http://v.juhe.cn/expressonline/cancleSend.php';
    private static $comUrl = 'http://v.juhe.cn/expressonline/getCarriers.php';
    private static $com = 'sf'; // 快递编号





    public function __construct(){
        // $this->com = $com;
    }
 
    /**
     * 返回支持的快递公司公司列表
     * @return array
     */
    public static function getComs(){
        $params = 'key='.self::$appkey;
        $content = self::juhecurl(self::$comUrl,$params);
        return self::_returnArray($content);
    }
 
    public static function call($order_no,$carrier_code,$member){
        // echo $no;
        $params = array(
            'key'           =>   self::$appkey,
            'send_method'   =>   'addOrderInfoMes',
            'order_no'      =>   $order_no,
            'isWaybill'     =>   1,
            'carrier_code'  =>   $carrier_code,
            'sender_name'           =>   $member['sender_name'],
            'sender_telphone'       =>   $member['sender_telphone'],
            'sender_province_name'  =>   $member['sender_province_name'],
            'sender_city_name'      =>   $member['sender_city_name'],
            'sender_district_name'  =>   $member['sender_district_name'],
            'sender_address'        =>   $member['sender_address'],
            'sender_post_code'      =>   $member['sender_post_code'],
            'receiver_name'           =>   '实价收',
            'receiver_telphone'       =>   '18026990481',
            'receiver_province_name'  =>   '广东省',
            'receiver_city_name'      =>   '深圳市',
            'receiver_district_name'  =>   '福田区',
            'receiver_address'        =>   '华发南路66号飞扬时代大厦A栋805',
            'receiver_post_code'      =>   '518100',
        );
        $content = self::juhecurl(self::$callUrl,$params,1);
        return self::_returnArray($content);
    }
    
    public static function cancle($order_no,$carrier_code){
        // echo $no;
        $params = array(
            'key'           =>   self::$appkey,
            'order_no'      =>   $order_no,
            'carrier_code'  =>   $carrier_code
        );
        $content = self::juhecurl(self::$cancleUrl,$params,1);
        return self::_returnArray($content);
    }
    /**
     * 将JSON内容转为数据，并返回
     * @param string $content [内容]
     * @return array
     */
    public static function _returnArray($content){
        return json_decode($content,true);
    }
 
    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    public static function juhecurl($url,$params=false,$ispost=0){
        $httpInfo = array();
        $ch = curl_init();
 
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
        if( $ispost )
        {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        }
        else
        {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}