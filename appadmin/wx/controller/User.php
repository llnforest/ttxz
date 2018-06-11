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
use think\Loader;


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

    //表单导出
    public function download(){

        $where  = getWhereParam(['status','phone','nickname'=>'like','create_time'=>['start','end']],$this->param);
        if(empty($where['phone'])) $where['phone'] = ['exp','!= ""'];

        $orderBy  = 'create_time desc';

        $list = WxUserModel::where($where)
            ->order($orderBy)
            ->select();

        Loader::import('PHPExcel.Classes.PHPExcel');
        Loader::import('PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory');
        Loader::import('PHPExcel.Classes.PHPExcel.Reader.Excel2007');
        Loader::import('PHPExcel.Classes.PHPExcel.Writer.Excel2007');
        $objPHPExcel = new \PHPExcel();
        $name = '用户数据';
        $objPHPExcel->getProperties()->setCreator("天天旋转")
            ->setLastModifiedBy("天天旋转")
            ->setTitle($name . "EXCEL导出")
            ->setSubject($name . "EXCEL导出")
            ->setDescription("备份数据")
            ->setKeywords("excel")
            ->setCategory("result file");
        $objPHPExcel->setActiveSheetIndex(0)
            //Excel的第A列，uid是你查出数组的键值，下面以此类推
            ->setCellValue('A1', '昵称')
            ->setCellValue('B1', '手机号')
            ->setCellValue('C1', '抽奖次数')
            ->setCellValue('D1', '中奖次数')
            ->setCellValue('E1', '分享次数')
            ->setCellValue('F1', '性别')
            ->setCellValue('G1', '注册时间');
        foreach ($list as $key => $v) {
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A' . ($key + 2), ' ' . $v['nickname'])
                ->setCellValue('B' . ($key + 2), ' ' . $v['phone'])
                ->setCellValue('C' . ($key + 2), ' ' . $v['join_times'])
//                ->setCellValueExplicit('C' . ($key + 2), ' ' . $v['imei'], \PHPExcel_Cell_DataType::TYPE_STRING)
                ->setCellValue('D' . ($key + 2), $v['win_times'])
                ->setCellValue('E' . ($key + 2), $v['share_times'])
                ->setCellValue('F' . ($key + 2), $v['sex'] == 1?'男':'女')
                ->setCellValue('G' . ($key + 2), $v['create_time']);
        }
        $name = $name . time();
        $objPHPExcel->getActiveSheet()->setTitle('User');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }
}