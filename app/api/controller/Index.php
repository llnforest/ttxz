<?php
namespace app\api\controller;



use fanston\alidayu\Alidayu;
use fanston\common\Tools;
use fanston\third\MyCache;
use fanston\third\SendMsg;
use model\WxBannerModel;
use model\WxPrizeModel;
use model\WxUserModel;
use think\Config;
use think\Validate;

class Index extends BaseController{

    //构造函数
    public function __construct(){
        parent::__construct();
    }

    //授权登陆
    public function login(){
        $param = $this->param;
        if(empty($param['code'])) return json(['code'=>0,'msg'=>'请传入参数code']);
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid={$this->config['appid']}&secret={$this->config['secret']}&js_code={$param['code']}&grant_type=authorization_code";
        $info = file_get_contents($url);//发送HTTPs请求并获取返回的数据，推荐使用curl
        $json = json_decode($info,true);//对json数据解码
        if(empty($json['openid']))  return json(['code'=>0,'msg'=>'参数code错误']);
        $user = WxUserModel::get(['openid'=>$json['openid']]);
        $time = !empty($user)?$user['create_time']:date('Y-m-d H:i:s',time());
        $signArr = [
            'openid' => $json['openid'],
            'session_key' => $json['session_key'],
            'create_time' => $time,
            'nonce_str' => Tools::genRandomString()
        ];
        $this->data['token'] = Tools::getSign($signArr,Config::get('secret_key'));
        if(empty($user)){
            WxUserModel::create(['openid'=>$json['openid'],'token'=>$this->data['token'],'session_key'=> $json['session_key']]);
        }else{
            $user->save(['token'=>$this->data['token'],'session_key'=> $json['session_key']]);
        }
        return json(['code'=>1,'msg'=>'授权登陆成功','data'=>$this->data]);

    }

    //用户注册
    public function register(){
        $param = $this->param;
        if (!empty($param['phone']) && !empty($param['usms'])) {
            $check = self::sCheckSms($param['phone'],$param['usms']);
            if($check['code'] != 1) return json($check);
            $roleValidate = ['nickname|昵称'=>'require','phone|手机号码' => 'require|mobile','headimgurl|头像'=>'require','sex|性别'=>'require'];
            $validate = new Validate($roleValidate);
            if(!$validate->check($param))  return json(['code' => 0, 'msg' => $validate->getError()]);
            $data = ['nickname'=>$param['nickname'],'phone'=>$param['phone'],'headimgurl'=>$param['headimgurl'],'sex'=>$param['sex']];
            $user = WxUserModel::get(['token'=>$this->token]);
            if(empty($user)) return json(['code'=>0,'msg'=>'token 错误']);
            $user->save($data);
            self::removeSms($param['phone']);
            return json(['code'=>1,'msg'=>'登录成功']);
        }else{
            return json(['code'=>0,'msg'=>lang('sys_param_error')]);
        }
    }

    //发送验证码
    public function sendSms(){
        $roleValidate = ['phone|手机号码' => 'require|mobile'];
        $validate = new Validate($roleValidate);
        if(!$validate->check($this->param))  return json(['code' => 0, 'msg' => $validate->getError()]);
        $phone = $this->param['phone'];
        //判断该手机号是否注册
        $user = WxUserModel::get(['phone'=>$phone]);
        if(!empty($user)) return json(['code'=>0,'msg'=>'该手机号码已经注册']);
        //判断发送的时间间隔
        $valCache = MyCache::get(MyCache::$SMSKey.$phone);
        $time = isset($valCache['time'])?$valCache['time']:0;
        if(time()-$time <= Config::get('sms.SMSTime')) return json(['code'=>0,'msg'=>lang('sms_phone_time_error')]);

        //判断当日发送量
        $numCache = MyCache::get(MyCache::$SMSNumKey.$phone);
        $day = isset($numCache['day'])?$numCache['day']:'';
        $num = isset($numCache['num'])?$numCache['num']:0;
        if($day == date('Y-m-d',time())){
            if($num >= Config::get('sms.SMSNum')) return json(['code'=>0,'msg'=>lang('sms_phone_num_error')]);
        }
        $code = rand(100000,999999);

        //阿里云短信接口
//        $sms = new Alidayu();
//        $result = $sms->sendSms($phone,$code);
        //雪豹云短信接口
        $content = SendMsg::getTemplate(1,['[0]' => $code]);
        $result = SendMsg::send($phone,$content);
        if($result){
            MyCache::set(MyCache::$SMSNumKey.$phone,array('day'=>date('Y-m-d',time()),'num'=>$num+1),3600*24);
            MyCache::set(MyCache::$SMSKey.$phone,array('sms'=>$code,'time'=>time()),60*6);
            return json(['code' => 1,'msg'=>'发送成功']);
        }else{
            return json(['code' => 0,'msg'=>'发送失败']);
        }

    }

    //删除已用短信验证码
    public static function removeSms($phone){
        MyCache::rm(MyCache::$SMSKey.$phone);
    }

    //静态验证短信验证码
    public static function sCheckSms($phone,$usms){
        if(empty($phone))
            return array('code'=>2001,'msg'=>lang('sms_check_phone_error'));
        if(empty($usms))
            return array('code'=>2001,'msg'=>lang('sms_data_error'));
        $valCache = MyCache::get(MyCache::$SMSKey.$phone);
        $sms = isset($valCache['sms'])?$valCache['sms']:'';
        if($usms != $sms)
            return array('code'=>2001,'msg'=>lang('sms_data_error'));
        MyCache::rm(MyCache::$SMSKey.$phone);
        return array('code'=>1,'msg'=>lang('sms_check_success'));
    }

    //获取奖品
    public function getPrizeList(){
        $this->data['prizeList'] = WxPrizeModel::where(['status'=>1])->order('sort asc')->limit(8)->select();
        return json(['code'=>1,'data'=>$this->data]);
    }

    //获取客服二维码
    public function getServiceInfo(){
        $service_url = $this->config['service_url'];
        $this->data['service_weixin'] = $this->config['service_weixin'];
        if(!empty($service_url))
            $this->data['service_url'] = $this->imgHost.str_replace('\\','/',$service_url);
        return json(['code'=>1,'data'=>$this->data]);
    }

    //获取剩余抽奖次数
    public function getLeftJoinTimes(){
        $token = !empty($this->token)?$this->token:'';
        if($token){
            $user = WxUserModel::get(['token'=>$this->token]);
            if(empty($user)){
                return json(['code' =>1,'data'=>['times'=>0]]);
            }
            else{
                $times = ['times'=>$user['left_per_use']+$user['left_share_use']];
                return json(['code' =>1,'data'=>$times]);
            }
        }else{
            return json(['code' =>1004,'msg'=>'请传入参数token']);
        }
    }

    //获取banner列表
    public function getBannerList(){
        $this->data['bannerList'] = WxBannerModel::where(['status'=>1])->field('url')->order('sort asc')->limit(5)->select();
        foreach($this->data['bannerList'] as $v){
            $v['url'] = $this->imgHost.str_replace('\\','/',$v['url']);
        }
        return json(['code'=>1,'data'=>$this->data]);
    }

    public function test(){
        return die(json_encode(['code' =>1001,'msg'=>'系统维护升级中，请稍候再试！'],JSON_UNESCAPED_UNICODE));
    }
}