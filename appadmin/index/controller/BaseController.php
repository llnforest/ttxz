<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\index\controller;

use think\Config;
use think\Controller;
use think\Request;
use thinkcms\auth\Auth;


class BaseController extends Controller
{

    protected $admin;
    protected $config_page = 50;
    protected $param;
    protected $id;
    public function __construct()
    {
        parent::__construct();
        if(!Config::get('sys_open')) $this->error(lang('sys_close'));
        $this->request      = Request::instance();
        $this->param        = $this->request->param();
        $this->id               = isset($this->param['id'])?$this->param['id']:'';
        $auth                   = new Auth();
        $auth->noNeedCheckRules = ['index/index/index','index/index/home'];
        $auth->log              = true;                 // v1.1版本  日志开关默认true
        $user                   = $auth::is_login();
        if($user){//用户登录状态
            $this->admin = $user;
            if(!$auth->auth()){
                $this->error(lang('sys_no_permission'));
            }
        }else{
            $this->redirect('index/publics/login');
        }
    }




}
