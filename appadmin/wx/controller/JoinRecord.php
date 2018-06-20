<?php
/**
 * author: Lynn
 * since: 2018/3/23 13:05
 */
namespace admin\wx\controller;

use admin\index\controller\BaseController;
use model\WxJoinRecordModel;


class JoinRecord extends BaseController{

    //构造函数
    public function __construct()
    {
        parent::__construct();
    }

    //摇奖记录列表页
    public function index(){
        $orderBy  = 'a.create_time desc';
        $where  = getWhereParam(['a.status','b.phone','b.nickname'=>'like','a.create_time'=>['start','end']],$this->param);
        if(!empty($this->param['order'])) $orderBy = $this->param['order'].' '.$this->param['by'];

        $data['list'] = WxJoinRecordModel::alias('a')
            ->join('tp_wx_user b','a.user_id = b.id','left')
            ->where($where)
            ->field('a.*,b.nickname,b.phone')
            ->order($orderBy)
            ->paginate($this->config_page,'',['query'=>$this->param]);
        $data['page']   = $data['list']->render();
        return view('index',$data);
    }

    //中奖记录列表页
    public function win(){
        $orderBy  = 'a.status asc,a.update_time desc';
        $where  = getWhereParam(['a.status','b.phone','b.nickname'=>'like','a.prize_name'=>'like','a.create_time'=>['start','end']],$this->param);
        if(empty($where['a.status'])) $where['a.status'] = ['in','1,2'];
        if(!empty($this->param['order'])) $orderBy = $this->param['order'].' '.$this->param['by'];

        $data['list'] = WxJoinRecordModel::alias('a')
            ->join('tp_wx_user b','a.user_id = b.id','left')
            ->where($where)
            ->field('a.*,b.nickname,b.phone')
            ->order($orderBy)
            ->paginate($this->config_page,'',['query'=>$this->param]);
        $data['page']   = $data['list']->render();
        return view('win',$data);
    }

    // 操作
    public function switchWin(){
        if($this->request->isPost()) {
            $result = WxJoinRecordModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            $data = ['status' => 2];
            return switchResult($result->save($data),'status');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }

}