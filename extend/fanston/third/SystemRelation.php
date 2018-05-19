<?php
namespace fanston\third;


class SystemRelation{
    // Android 参数对应关系
    public static function checkStoreAndroidRelation($rid,$value){
        $relation = [
                '4511' => 'zuzhuangping',
                '4604' => 'wufaxianshi',
                '4605' => 'qingweilaohua',
                '4584' => 'yanzhonglaohua',

        ];
        $relation_keys = array_keys($relation);
        $item = array_intersect($rid,$relation_keys);
        foreach($item as $key) {
            if($key == 4511 || $key == 4604){
                if(($value['zuzhuangping'] === null && $value['neipingbao'] === null) || $value['pingliang'] === null) return false;
            }else if($value[$relation[$key]] === null){
                return false;
            }
        }
        return true;
    }

    // Ios 参数对应关系
    public static function checkStoreIosRelation($rid,$value){
        $relation = [
            '4581' => 'pinghua',
            '4582' => 'waipingsui',
            '4634' => 'wufaxianshi',
            '4604' => 'wufaxianshi',
            '4587' => 'kehua',
            '4588' => 'kekepeng',
            '4606' => 'kesunhuai',
            '4605' => 'qingweilaohua',
            '4584' => 'yanzhonglaohua',
            '4514' => 'shouhouyuanzhuangping',
            '4515' => 'houyaping',
            '4516' => 'zuzhuangping',

        ];
        $result = self::checkRelation($relation,$rid,$value);
        return $result;
    }

    // Android 参数对应关系
    public static function checkMemberAndroidRelation($rid,$value){
        $relation = [
            '4516' => 'zuzhuangping',
            '4604' => 'neipingbao',
            '4634' => 'neipingbao',
            '4605' => 'qingweilaohua',
            '4584' => 'yanzhonglaohua',

        ];
        $relation_keys = array_keys($relation);
        $item = array_intersect($rid,$relation_keys);
        foreach($item as $key) {
            if ($key == 4516 && $value['pingliang'] === null) {
                return false;
            }else if($key == 4604 || $key == 4634){
                if(($value['zuzhuangping'] === null && $value['neipingbao'] === null) || $value['pingliang'] === null) return false;
            }else if($value[$relation[$key]] === null){
                return false;
            }
        }
        return true;
    }

    // Ios 参数对应关系
    public static function checkMemberIosRelation($rid,$value){
        $relation = [
            '4581' => 'pinghua',
            '4582' => 'waipingsui',
            '4634' => 'wufaxianshi',
            '4604' => 'wufaxianshi',
            '4587' => 'kehua',
            '4588' => 'kekepeng',
            '4606' => 'kesunhuai',
            '4605' => 'qingweilaohua',
            '4584' => 'yanzhonglaohua',
            '4514' => 'shouhouyuanzhuangping',
            '4515' => 'houyaping',
            '4516' => 'zuzhuangping',

        ];
        $result = self::checkRelation($relation,$rid,$value);
        return $result;
    }

    private static function checkRelation($relation,$rid,$value){
        $relation_keys = array_keys($relation);
        $item = array_intersect($rid,$relation_keys);
        foreach($item as $key){
            if($value[$relation[$key]] === null) return false;
        }
        return true;
    }
}