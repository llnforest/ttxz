<?php
namespace app\api\controller;


use model\WxConfigModel;
use think\Config;
use think\Controller;
use think\Request;


class BaseController extends Controller
{
    protected $data;
    protected $request;
    protected $param;
    protected $id;
    protected $imgHost;
    protected $config;
    protected $token;
    //构造函数
    public function __construct()
    {
        $this->request = Request::instance();
        $this->param = $this->request->param();
//        if(!$this->request->isPost()) die(json_encode(['code' =>1002,'msg'=>'请求方式错误！']));
        $this->token = !empty($this->param['token'])?$this->param['token']:'';
        $this->id = !empty($this->param['id'])?$this->param['id']:'';
        $this->imgHost = Config::get('upload.img_url');

        if(!Config::get('sys_open')){
            return die(json_encode(['code' =>1001,'msg'=>'系统维护升级中，请稍候再试！']));
        }

        if(!$this->isOpen()){
            return die(json_encode(['code' =>1003,'msg'=>'对不起，活动已结束！']));
        }
        parent::__construct();
    }

    public function isOpen(){
        $this->config = WxConfigModel::get(['id'=>1]);
        if(!empty($this->config) && $this->config['status'] == 1)
            return true;
        else
            return false;
    }


}
