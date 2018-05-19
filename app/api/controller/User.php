<?php
namespace app\api\controller;

use model\WxJoinRecordModel;
use model\WxShareRecordModel;
use model\WxUserModel;
use think\Config;
use think\Validate;

class User extends DefaultController {

    //构造函数
    public function __construct(){
        parent::__construct();
    }

    //判断是否登录
    public function isLogin(){
        return json(['code' => 1,'msg'=>'登陆成功']);
    }

    //个人信息和次数
    public function getUserInfo(){
        $this->data['user'] = $this->userData;
        return json(['code'=>1,'data'=>$this->data]);
    }

    //修改个人昵称
    public function editUserNickname(){
        $roleValidate = ['nickname|昵称' => 'require'];
        $validate = new Validate($roleValidate);
        if(!$validate->check($this->param)) return json(['code' => 0,'msg' => $validate->getError()]);
        return json(operateResult($this->userData->save(['nickname'=>$this->param['nickname']]),'edit'));
    }

    //修改个人性别
    public function editUserSex(){
        $roleValidate = ['sex|性别' => 'require|number'];
        $validate = new Validate($roleValidate);
        if(!$validate->check($this->param)) return json(['code' => 0,'msg' => $validate->getError()]);
        return json(operateResult($this->userData->save(['sex'=>$this->param['sex']]),'edit'));
    }

    //获取中奖记录
    public function getWinList(){
        $this->data['winList'] = WxJoinRecordModel::alias('a')
            ->join('tp_wx_prize b','a.prize_id = b.id','left')
            ->where(['a.status'=>['in','1,2'],'a.user_id'=>$this->userData['id']])
            ->field('a.create_time,b.name')
            ->select();
        return json(['code'=>1,'data'=>$this->data]);
    }

    //是否允许抽奖
    public function isCanJoin(){
        $times = $this->userData['left_per_use'] + $this->userData['left_share_use'];
        if($times >= 1){
            return json(['code'=>1,'msg'=>'可以抽奖','times' =>$times]);
        }else{
            return json(['code'=>0,'msg'=>'没有抽奖次数了','times'=>0]);
        }
    }

    //参与抽奖
    public function joinActive(){
        $param = $this->param;
        $roleValidate = ['prize_id|奖品id' => 'require|number','status|中奖状态'=>'require'];
        $validate = new Validate($roleValidate);
        if(!$validate->check($param))  return json(['code' => 0, 'msg' => $validate->getError()]);
        $data = ['prize_id'=>$param['prize_id'],'status'=>$param['status'],'user_id'=>$this->userData['id']];
        $times = $this->userData['left_per_use'] + $this->userData['left_share_use'];
        if($times < 1) return json(['code'=>0,'msg'=>'无抽奖次数']);
        if(WxJoinRecordModel::create($data)){
            if($this->userData['left_per_use'] > 0){
                $this->userData->save(['left_per_use' => $this->userData['left_per_use']-1]);
            }else{
                $this->userData->save(['left_share_use' => $this->userData['left_share_use']-1]);
            }
            if($param['status'] == 1) WxUserModel::where(['token'=>$this->token])->setInc('win_times',1);
            return json(['code'=>0,'msg'=>'添加抽奖成功','data'=>['times'=>$this->userData['left_per_use'] + $this->userData['left_share_use']]]);
        }else{
            return json(['code'=>0,'msg'=>'添加抽奖失败']);
        }
    }

    //分享成功
    public function shareSuccess(){
        $param = $this->param;
        $roleValidate = ['scene|分享场景' => 'require'];
        $validate = new Validate($roleValidate);
        if(!$validate->check($param))  return json(['code' => 0, 'msg' => $validate->getError()]);
        $share_date = date('Y-m-d',time());
        $data = ['scene'=>$param['scene'],'user_id'=>$this->userData['id'],'share_date'=>$share_date];
        if(empty(WxShareRecordModel::get($data))){
            if(WxShareRecordModel::create($data)){
                if($this->userData['is_share'] == 0){
                   $share_count =  WxShareRecordModel::where(['user_id'=>$this->userData['id'],'share_date'=>$share_date])->count();
                   if($share_count >= 2) $this->userData->save(['is_share' =>1,'left_share_use' => $this->userData['left_share_use'] + 1]);
                }
                return json(['code'=>0,'msg'=>'添加分享成功','data'=>['times'=>$this->userData['left_per_use'] + $this->userData['left_share_use']]]);
            }else{
                return json(['code'=>0,'msg'=>'添加分享失败']);
            }
        }else{
            return json(['code'=>0,'msg'=>'今日分享对象重复']);
        }
    }

}