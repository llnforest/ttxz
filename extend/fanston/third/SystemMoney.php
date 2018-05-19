<?php
namespace fanston\third;

class SystemMoney{


    public static $systemMemberProfitTopMax = 1000;
    public static $systemMemberProfitMax = 300;
    public static $systemMemberProfitMin = 50;
    public static $systemMemberProfit = 20;

    public static $systemStoreProfitPrice = 50;

    // 代理默认返佣
    public static function getAgentDefaultComs(){
        return 8;
    }

    // 代理奖励返佣最大值
    public static function getAgentMaxComs(){
        return 5;
    }

    //系统利润比
    public static function getSystemProfit(){
        return 0;
    }
    
    

    //价格有效期
    public static function getPriceDay(){
        return 7;
    }

    public static function profitMemberPrice($phone){
        $price = $phone['price'];
        $lowprice = $phone['lowprice'];
        $max = self::$systemMemberProfitMax;
        if($price >= 6000) $max = self::$systemMemberProfitTopMax;
        $profitPrice = (int) (($price*self::$systemMemberProfit)/100);
        if($profitPrice > $max)
            $profitPrice = $max;
        if($profitPrice < self::$systemMemberProfitMin)
            $profitPrice = self::$systemMemberProfitMin;
        $price -= $profitPrice;
        if($price < $lowprice)
            $price = $lowprice;
        return $price;
    }

    //B端利润
    public static function profitStorePrice($phone){
        if($phone['brand_id'] == 1)
            $price = self::profitStoreIosPrice($phone);
        else
            $price = self::profitStoreAndroidPrice($phone);
        return $price;
    }

    //B端Android利润
    public static function profitStoreAndroidPrice($phone){
        $price = $phone['price'];
        $lowprice = $phone['lowprice'];
        //万里行
        if(in_array($phone['sell_id'], array(2))){
            if($price < $lowprice)
                $price = $lowprice;
            return $price;
        }
        $price -= self::$systemStoreProfitPrice;
        if($price < $lowprice) $price = $lowprice;
        return $price;
    }

    //B端IOS利润
    public static function profitStoreIosPrice($phone){
        $price = $phone['price'];
        $lowprice = $phone['lowprice'];
        if($price < $lowprice) $price = $lowprice;
        return $price;
    }
}