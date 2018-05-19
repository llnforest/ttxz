<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\wx\controller;

use admin\index\controller\BaseController;
use model\WxConfigModel;
use think\Validate;


class Config extends BaseController{
    private $roleValidate = ['service_weixin|客服微信号' => 'require','status|活动状态' => 'require','appid|小程序appid' => 'require','secret|小程序secret' => 'require'];
    //构造函数
    public function __construct(){
        parent::__construct();
    }

    //网站管理页
    public function index(){
        $this->id = $this->id ? $this->id : 1;
        $data['info'] = WxConfigModel::get($this->id);
        if(!$data['info']) $this->error(lang('sys_param_error'));
        if($this->request->isPost()){
            $validate = new Validate($this->roleValidate);
            if(!$validate->check($this->param)) return ['code' => 0,'msg' => $validate->getError()];
            return operateResult($data['info']->save($this->param),'config/index','edit');
        }
        return view('index',$data);
    }


}