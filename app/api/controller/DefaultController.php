<?php
namespace app\api\controller;


use model\UserModel;
use model\WxUserModel;
use think\Config;
use think\Controller;
use think\Request;


class DefaultController extends BaseController
{
    protected $userData;
    //构造函数
    public function __construct()
    {
        parent::__construct();
        if($this->token){
            $this->userData = WxUserModel::get(['token'=>$this->token]);
            if(empty($this->userData['phone'])) die(json_encode(['code' =>1005,'msg'=>'用户尚未注册，请先注册！'],JSON_UNESCAPED_UNICODE));
            elseif($this->userData['status'] == 0)  die(json_encode(['code' =>1006,'msg'=>'用户已被停用！'],JSON_UNESCAPED_UNICODE));
        }else{
            die(json_encode(['code' =>1004,'msg'=>'token不能为空'],JSON_UNESCAPED_UNICODE));
        }
    }


}
