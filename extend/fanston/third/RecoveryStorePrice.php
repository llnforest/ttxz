<?php
namespace fanston\third;


class RecoveryStorePrice{

    public static function getRecoveryPrice($evaluation,$ModelPrice){
        // 最高价
        $price = $highPrice = $ModelPrice['price'];
        //保底价
        $lowprice = $ModelPrice['lowprice'];
        //利润率
        $profit = $ModelPrice['store_profit']?$ModelPrice['store_profit']:$ModelPrice['brand_store_profit'];

        $evaluationByType = array();
        foreach($evaluation as $val){
            $evaluationByType[$val['type']][$val['level']][]=array('price'=>$val['price'],'proportion'=>$val['proportion']);
        }
        // 核减必须核减项
        $MustPrice = self::getMustPrice($evaluation);
        $price -= $MustPrice;
        // 核减基础项
        $price = self::getDefaultPrice($price,$evaluationByType);
        // 核减功能项
        // $price = self::getFunctionPrice($price,$evaluation);
        $price = SystemMoney::profitStorePrice(array('price'=>$price,'lowprice'=>$lowprice,'sell_id'=>$ModelPrice['sell_id']));
        // if($price <= $lowprice) $price = $lowprice;
        return (int) $price;
    }
    private static function getFunctionPrice($price,$eval){
        foreach($eval as $val){
            if($val['type'] == 10){
                $data[] = $val['price'];
            }
        }
        if(isset($data)){
            // 核减最高值
            rsort($data);
            $price = ($price*(100-$data[0])/100);
        }
        return (int) $price;
    }

    private static function getDefaultPrice($price,$eval){
        $defaultPrice = $price;
        $level_6_num = 0;
        $level_5_num = 0;
        $level_4_num = 0;
        $level_3_num = 0;
        $level_2_num = 0;
        $level_1_num = 0;
        if(isset($eval[4])){
            $data = $eval[4];
            $num = count($data);
            //判断数量
            if(isset($data[6])) $level_6_num = count($data[6]);
            if(isset($data[5])) $level_5_num = count($data[5]);
            if(isset($data[4])) $level_4_num = count($data[4]);
            if(isset($data[3])) $level_3_num = count($data[3]);
            if(isset($data[2])) $level_2_num = count($data[2]);
            if(isset($data[1])) $level_1_num = count($data[1]);
            if($level_6_num){
                $data6 = self::handelDetail($price,$data[6]);
                $price -= $data6[0]['price'];
                if($level_5_num){
                    $data5 = self::handelDetail($defaultPrice,$data[5]);
                    $price -= $data5[0]['price']*0.3;
                }
                return (int) $price;
            }
            if($level_5_num){
                $data5 = self::handelDetail($price,$data[5]);
                $price -= $data5[0]['price'];
                if($level_4_num){
                    $data4 = self::handelDetail($defaultPrice,$data[4]);
                    $price -= $data4[0]['price']*0.3;
                }
                return (int) $price;
            }
            if($level_4_num){
                $data4 = self::handelDetail($price,$data[4]);
                $price -= $data4[0]['price'];
                if($level_3_num){
                    $data3 = self::handelDetail($defaultPrice,$data[3]);
                    $price -= $data3[0]['price']*0.3;
                }else{
                    if($level_2_num){
                        $data2 = self::handelDetail($defaultPrice,$data[2]);
                        $price -= $data2[0]['price']*0.3;
                    }
                }
                return (int) $price;
            }
            if($level_3_num){
                $data3 = self::handelDetail($price,$data[3]);
                $price -= $data3[0]['price'];
                return (int) $price;
            }
            if($level_2_num){
                $data2 = self::handelDetail($price,$data[2]);
                $price -= $data2[0]['price'];
                if($level_1_num){
                    $data1 = self::handelDetail($defaultPrice,$data[1]);
                    $price -= $data1[0]['price'];
                }
                return (int) $price;
            }
            if($level_1_num){
                $data1 = self::handelDetail($price,$data[1]);
                $price -= $data1[0]['price'];
                return (int) $price;
            }
        }
        return (int) $price;
    }
    private static function getMustPrice($eval){
        $price = 0;
        foreach($eval as $key=>$v){
            if($v['type'] == 5){
                $price += $v['price'];
            }
        }
        return $price;
    }
    private static function handelDetail($price,$data){
        foreach ($data as $key => $value) {
            if($value['proportion'])
                $data[$key]['price'] = ($price*$value['price'])/100;
        }
        $temp = array();
        foreach ($data as $key => $value) {
            $temp[] = $value['price'];
        }
        array_multisort($temp, SORT_DESC, $data);
        return $data;
    }
}