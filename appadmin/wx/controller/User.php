<?php
/**
 * author: Lynn
 * since: 2018/3/23 13:05
 */
namespace admin\wx\controller;


use admin\index\controller\BaseController;
use model\WxJoinRecordModel;
use model\WxShareRecordModel;
use model\WxUserModel;


class User extends BaseController{

    //构造函数
    public function __construct()
    {
        parent::__construct();
    }

    //案例分类列表页
    public function index(){
        $orderBy  = 'create_time desc';
        if(!empty($this->param['order'])) $orderBy = $this->param['order'].' '.$this->param['by'];

        $where  = getWhereParam(['status','phone','nickname'=>'like','create_time'=>['start','end']],$this->param);
        if(empty($where['phone'])) $where['phone'] = ['exp','!= ""'];
        $data['list'] = WxUserModel::where($where)->order($orderBy)
            ->paginate($this->config_page,'',['query'=>$this->param]);
        $data['page']   = $data['list']->render();
        return view('index',$data);
    }

    // 操作用户
    public function switchUser(){
        if($this->request->isPost()) {
            $result = WxUserModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            $data = [$this->param['name'] => $this->param['data']];
            return switchResult($result->save($data),'status');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }

    // 删除用户
    public function userDelete(){
        if($this->request->isPost()) {
            $result = WxUserModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            if(!empty(WxShareRecordModel::get(['user_id'=>$this->id])) || !empty(WxJoinRecordModel::get(['user_id'=>$this->id])))
                return ['code'=>0,'msg'=>'该用户已分享或已有抽奖记录，不能删除'];
            else
                return operateResult($result->delete(),'user/index','del');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }

}