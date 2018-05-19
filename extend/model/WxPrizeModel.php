<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace model;
use traits\model\SoftDelete;

class WxPrizeModel extends \think\Model
{
    use SoftDelete;
    // 设置完整的数据表（包含前缀）
    protected $name = 'tp_wx_prize';

    //初始化属性
    protected function initialize()
    {
    }

}
?>