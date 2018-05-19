<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\index\controller;


use thinkcms\auth\Auth;
use thinkcms\auth\library\Tree;

class Index extends BaseController
{
    public $auth;
    public function __construct(){
        parent::__construct();
    }

    /**
     * 后台首页布局页面
     */
    public function index(){
        $this->view->engine->layout('layouts/index');
        $this->assign([
            'menu'      => self::menu(),
            'username'      => Auth::sessionGet('user.nick_name'),
            'systemname'    => Auth::sessionGet('user.nick_name')

        ]);
        return view();
    }

    /**
     * 内容页
     */
    public function home(){
        return view();
    }

    public function getNewOrder(){
        $starttime = date("Y-m-d H:i:s",time()-60);
        $where = array();
        $where['create_time'] = array('egt',$starttime);
        if(!checkAdmin()) $where['user_id']  = array('not in',$this->other_uid);
        $userData = OrderModel::where($where)->find();
        $memberData = MemberOrderModel::where(['create_time' => array('egt',$starttime)])->find();
        if(!empty($userData) || !empty($memberData))
            return json(['code'=>1]);
        return json(['code'=>0]);
    }
    public function getNewReg(){
        $starttime = date("Y-m-d H:i:s",time()-60);
        $data = UserModel::where(['create_time' => array('egt',$starttime),'status' => 0])->find();
        if(!empty($data))
            return json(['code'=>1]);
        return json(['code'=>0]);
    }
    public function getNewAuthentication(){
        // $starttime = date("Y-m-d H:i:s",time()-60);
        // $data = UserModel::where(['authentication' => 3])->find();
        $userData = UserModel::where(['authentication' => 3])->find();
        $memberData = MemberModel::where(['authentication' => 3])->find();
        if(!empty($userData) || !empty($memberData))
            return json(['code'=>1]);
        return json(['code'=>0]);
    }
    public function getNewTake(){
        $starttime = date("Y-m-d H:i:s",time()-60);
        $where = array();
        $where['create_time'] = array('egt',$starttime);
        $where['status'] = 0;
        if(!checkAdmin()) $where['user_id']  = array('not in',$this->other_uid);
        $userData = TakeModel::where($where)->find();
        $memberData = MemberTakeModel::where(['create_time' => array('egt',$starttime),'status'=>0])->find();
        if(!empty($userData) || !empty($memberData))
            return json(['code'=>1]);
        return json(['code'=>0]);
    }

    /**
     * 组织menu的html样式
     */
    private function menu(){
        $menu       = Auth::menuCheck();
        $menuName   = '';
        $tree       = new Tree();
        $num        = 1;
        foreach ($menu as $k => $v) {
            if($v['parent_id']==0){
                $class       = $num==1?'class="active"':'';
                $menuName   .= ' <li '.$class.' aria-controls="nav'.$v['id'].'">
                                <a href="#"  role="tab" data-toggle="tab"  aria-expanded="true">
                                    '.$v['name'].'
                                </a>
                            </li>';
                $menu[$k]['display']    = $num==1?'black':'none';
                $num++;
            }

            $url    = $v['url_param']?'?'.$v['url_param']:'';
            $url    = url("{$v['app']}/{$v['model']}/{$v['action']}").$url;
            $url    = 'onclick="javascript:openapp(\''.$url.'\',\''.$v['id'].$v['model'].'\',\''.$v['name'].'\',true,this);"';
            $menu[$k]['icon']    = !empty($v['icon'])?$v['icon']:'fa-list';
            $menu[$k]['level']    = $tree->get_level($v['id'], $menu);
            $menu[$k]['url']      = $url;
        }

        $tree->init($menu);
        $tree->text =[
            'other' => "<li>
                            <a \$url> &nbsp;
                                <i class='fa fa-angle-double-right'></i>
                                <span class='menu-text'> \$name </span>
                            </a>
                        </li>",
            '0' => [
                '0' =>"<ul class='nav nav-list' style='display: \$display' id='nav\$id'>",
                '1' =>"</ul>",
            ],
            '1' => [
                '0' => "<li>
                        <a  \$url class='dropdown-toggle'>
                            <i class='fa \$icon '></i>
                            <span class='menu-text normal'> \$name </span>
                            <b class='arrow fa fa-angle-right normal'></b>
                        </a>
                        <ul  class='submenu'>",
                '1' => "</ul></li>",
            ],
            '3' => [
                '0' => "<li>
                            <a \$url class='dropdown-toggle'> <i class='fa fa-caret-right'>
                                </i> <span class='menu-text'> \$name </span>
                                <b class='arrow fa fa-angle-right'></b>
                            </a>
                            <ul  class='submenu'>",
                '1' => "</ul></li>",
            ]

        ];
        return ['menuName'=>$menuName,'menuHtml'=>$tree->get_authTree(0)];
    }


}
