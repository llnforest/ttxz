<?php
/**
 * author: Lynn
 * since: 2018/3/23 13:05
 */
namespace admin\wx\controller;

use admin\index\controller\BaseController;
use model\WxJoinRecordModel;
use model\WxPrizeModel;
use think\Config;
use think\Validate;


class Prize extends BaseController{

    private $roleValidate = ['name|奖品名称' => 'require'];
    //构造函数
    public function __construct()
    {
        parent::__construct();
    }

    //奖品列表页
    public function index(){
        $orderBy  = 'sort asc';
        if(!empty($this->param['order'])) $orderBy = $this->param['order'].' '.$this->param['by'];
        $where  = getWhereParam(['status'],$this->param);
        $data['list'] = WxPrizeModel::where($where)
            ->order($orderBy)
            ->paginate($this->config_page,'',['query'=>$this->param]);
        $data['page']   = $data['list']->render();
        return view('index',$data);
    }

    //添加奖品
    public function prizeAdd(){
        if($this->request->isPost()){
            $validate = new Validate($this->roleValidate);
            if(!$validate->check($this->param)) return ['code' => 0, 'msg' => $validate->getError()];
            return operateResult(WxPrizeModel::create($this->param),'prize/index','add');
        }
        return view('prizeAdd');
    }

    //修改奖品
    public function prizeEdit(){
        $data['info'] = WxPrizeModel::get($this->id);
        if(!$data['info']) $this->error(lang('sys_param_error'));
        if($this->request->isPost()){
            $validate = new Validate($this->roleValidate);
            if(!$validate->check($this->param)) return ['code' => 0,'msg' => $validate->getError()];
            return operateResult($data['info']->save($this->param),'prize/index','edit');
        }
        return view('prizeEdit',$data);
    }

    //删除奖品
    public function prizeDelete(){
        if($this->request->isPost()) {
            $result = WxPrizeModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            return operateResult($result->delete(),'Prize/index','del');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }

    // 排序奖品
    public function inputPrize(){
        if($this->request->isPost()) {
            $result = WxPrizeModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            $data = [$this->param['name'] => $this->param['data']];
            return inputResult($result->save($data),'sort');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }

    // 操作奖品
    public function switchPrize(){
        if($this->request->isPost()) {
            $result = WxPrizeModel::get($this->id);
            if (empty($result)) return ['code' => 0, 'msg' => lang('sys_param_error')];
            $data = [$this->param['name'] => $this->param['data']];
            return switchResult($result->save($data),'status');
        }
        return ['code'=>0,'msg'=>lang('sys_method_error')];
    }
}