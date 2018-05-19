<?php
namespace fanston\third;


use fanston\third\MyCache;
use fanston\mweixin\Wechat;
use think\Config;

class Weixin{
    public static $appid;
    public static $appsecret ;
    public static $expresstel;
    public static $service;
    public static $systemType;
    public static $blacklist = ['15212998586', '13705680023', '18298116281', '15155466688', '18019966168', '13681773807'];

    public static $encodingaeskey = 'iIRR7dfhmYLacDRl7GDfuxxOrKvhJv7f4us7PG3buwD';
    public static $token = 'SHIJIATOKEN29G23G29G2G32';

    public function __construct(){
        self::$appid = Config::get('weixin_b.appid');
        self::$appsecret = Config::get('weixin_b.appsecret');
        self::$systemType = Config::get('weixin_b.systemType');
        self::$expresstel = Config::get('service');
        self::$service = Config::get('service');
    }

    public static function get_access_token(){
        $access_token = MyCache::get(MyCache::$ACCESS_TOKEN);
        if($access_token == ''){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.self::$appid.'&secret='.self::$appsecret;
            $token = json_decode(self::https_request($url));
            if(isset($token->access_token)){
                $access_token = $token->access_token;
                MyCache::set(MyCache::$ACCESS_TOKEN,$access_token);
            }
        }
        return $access_token;
    }

    /**
     * 模板消息——下单
     * @param $order
     * @return array|bool
     */
    public static function createOrder($order){
        if(in_array($order['phone'],self::$blacklist)) return false;
        $time = date('Y-m-d H:i:s',time());
        if($order['recovery_type'] == 1)//帮叫快递
            $info = '实价收客服人员会在一个小时内联系您并为您安排上门服务人员，请保持联系电话畅通。如有疑问，请联系客服:'.self::$expresstel;
        else
            $info = '请尽快将您的机器寄出，收货地址:合肥市蜀山区长江西路189号环球中心A座1204，收件人:实价收，联系电话:0551-66104389。快递寄出后，请在“我的”-“订单中心”-“发货”处选择快递方式并填入快递单号。如有疑问，请联系客服'.self::$service.'。';
        $data = array(
            'touser'       => $order['openid'],
            'template_id'  => 'nBgDhdqk9eHj9z4KMPqX0UeXX5lqwc1Lfu9eNMB1s1U',
            'url'          => 'http://store.shijiashou.cn/index/order/detail/id/'.$order['id'].'.html',
            'data'  => array(
                'first'  =>   array('value'=>'您的订单已提交成功'),
                'keyword1'  =>   array('value'=>$time),
                'keyword2'  =>   array('value'=>'手机回收'),
                'keyword3'  =>   array('value'=>$order['id']),
                'remark'  =>   array('value'=>$info),
                ),
            );
        return self::send($data);

    }

    /**
     * 改变订单状态——模板消息
     * @param $order
     * @return array|bool
     */
    public static function changeOrder($order){
        if(in_array($order['phone'],self::$blacklist)) return false;
        $time = date('Y-m-d H:i:s',time());
        $data = array(
            'keyword1'  =>  array('value'=>$order['id']),
            'keyword2'  =>  array('value'=>$time),
            );
        switch ($order['status']) {
            case 2:
                $data['first'] = array('value'=>'您的订单已收货');
                $data['remark'] = array('value'=>'我们会在24小时内完成质检，请留意订单状态。如有疑问，请联系客服'.self::$service.'。');
                break;
            case 4:
                $data['first'] = array('value'=>'您的订单已成交');
                $data['remark'] = array('value'=>'回收款已打入结算中心，您可申请结款提现。如有疑问，请联系客服'.self::$service.'。');
                break;
            case 5:
                $data['first'] = array('value'=>'您的订单已关闭');
                $data['remark'] = array('value'=>'如有疑问，请联系客服'.self::$service.'。');
                break;
            default:
                # code...
                break;
        }
        $alldata = array(
            'touser'       =>  $order['openid'],
            'template_id'  => 'oVD4e_Bzptn2ACXBKyOj534-Xh8_gynvt4reIU71I2c',
            'url'          => 'http://store.shijiashou.cn/index/order/detail/id/'.$order['id'].'.html',
            'data'  => $data,
            );
        return self::send($alldata);
    }

    /**
     * 质检——模板消息
     * @param $order
     * @return array|bool
     */
    public static function checkOrder($order,$status){
        if(in_array($order['phone'],self::$blacklist)) return false;
        $time = date('Y-m-d H:i:s',time());
        $data = array(
            'keyword1'  =>  array('value'=>$order['id']),
            'keyword2'  =>  array('value'=>$time),
        );
        switch ($status) {
            case 0:
                $data['first'] = array('value'=>'您的订单部分质检完毕');
                $data['remark'] = array('value'=>'您的订单部分手机已质检完毕。请及时查看质检报告并确认质检结果。剩余手机会在订单到货后24小时内质检完毕，请留意订单状态或短信通知。如有疑问，请联系客服'.self::$service.'。');
                break;
            case 1:
                $data['first'] = array('value'=>'您的订单已全部质检完毕');
                $data['remark'] = array('value'=>'您的订单已全部质检完毕。请及时查看质检报告并确认质检结果。如有疑问，请联系客服'.self::$service.'。');
                break;
            default:
                # code...
                break;
        }
        $alldata = array(
            'touser'       =>  $order['openid'],
            'template_id'  => 'oVD4e_Bzptn2ACXBKyOj534-Xh8_gynvt4reIU71I2c',
            'url'          => 'http://store.shijiashou.cn/index/order/detail/id/'.$order['id'].'.html',
            'data'  => $data,
        );
        return self::send($alldata);
    }

    /**
     * 退货——模板消息
     * @param $order        订单详情
     * @return array|bool
     */
    public static function changeBackOrder($order){
        if(in_array($order['phone'],self::$blacklist)) return false;
        $time = date('Y-m-d H:i:s',time());
        $data = array(
            'first'     =>  array('value'=>'您的订单机器已退回'),
            'keyword1'  =>  array('value'=>$order['id']),
            'keyword2'  =>  array('value'=>$time),
            'remark'    =>  array('value'=>'物流名称:'.$order['express'].'，运单号:'.$order['back_express'].',请您保持手机畅通，注意查收。')
            );
        
        $alldata = array(
            'touser'       =>  $order['openid'],
            'template_id'  => 'oVD4e_Bzptn2ACXBKyOj534-Xh8_gynvt4reIU71I2c',
            'url'          => 'http://store.shijiashou.cn/index/order/detail/id/'.$order['id'].'.html',
            'data'  => $data,
            );
        return self::send($alldata);
    }

    /**
     * 提现成功
     * @param $take
     * @return array|bool
     */
    public static function takeDone($take){
        if(in_array($take['phone'],self::$blacklist)) return false;
        $data = array(
            'first'     =>  array('value'=>'提现申请已打款'),
            'keyword1'  =>  array('value'=>$take['phone']),
            'keyword2'  =>  array('value'=>$take['money']),
            'keyword4'  =>  array('value'=>date('Y-m-d H:i:s',time())),
            'remark'    =>  array('value'=>'该提现申请已成功打款，流水号为:'.$take['serial_number'].',请注意查收。如有疑问，请联系客服'.self::$service.'。')
            );
        switch ($take['type']) {
            case '0':
                $data['keyword3'] = array('value'=>'银行卡:'.$take['account']);
                break;
            case '1':
                $data['keyword3'] = array('value'=>'支付宝:'.$take['account']);
                break;
            
            default:
                $data['keyword3'] = array('value'=>'微信:'.$take['account']);
                # code...
                break;
        }
        $alldata = array(
            'touser'       =>  $take['openid'],
            'template_id'  => 'OXIWihlxqernwPr9AmwccBvAB_76RILDnImSg5WEWiI',
            'url'          => 'http://store.shijiashou.cn/index/user/wallet.html',
            'data'  => $data,
            );
        return self::send($alldata);
    }

    public static function send($data){
        $options = array(
            'token'=> self::$token, //填写你设定的key
            'encodingaeskey'=> self::$encodingaeskey, //填写加密用的EncodingAESKey
            'appid'=>self::$appid, //填写高级调用功能的app id
            'appsecret'=>self::$appsecret, //填写高级调用功能的密钥
            'systemType'=>self::$systemType
        );
        $weObj = new Wechat($options);
        $result = $weObj->sendTemplateMessage($data);
        return $result;
    }
    
    public static function https_request($url, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
    } 
}