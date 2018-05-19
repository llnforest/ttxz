<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\wx\controller;

use admin\index\controller\BaseController;
use model\WxShareRecordModel;


class ShareRecord extends BaseController{
    private $roleValidate = ['url|图片' => 'require','sort|排序' => 'number'];
    //构造函数
    public function __construct(){
        parent::__construct();
    }

    //分享记录列表页
    public function index(){
        $orderBy  = 'a.create_time desc';
        $where  = getWhereParam(['a.status','b.phone','b.nickname'=>'like','a.create_time'=>['start','end']],$this->param);
        if(!empty($this->param['order'])) $orderBy = $this->param['order'].' '.$this->param['by'];

        $data['list'] = WxShareRecordModel::alias('a')
            ->join('tp_wx_user b','a.user_id = b.id','left')
            ->where($where)
            ->field('a.*,b.nickname,b.phone')
            ->order($orderBy)
            ->paginate($this->config_page,'',['query'=>$this->param]);
        $data['page']   = $data['list']->render();
        return view('index',$data);
    }

}