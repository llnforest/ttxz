<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */

if(!function_exists('getYearNum')){
    /**
     * 获取使用年数
     * @param $start_date 2017-12-23 00:00:00
     * @return string 2017-12-23
     */
    function getYearNum($start_date){
        if(!$start_date) return '';
        $end_date = date('Y-m-d',time());
        $startArr = explode('-',$start_date);
        $endArr = explode('-',$end_date);
        if($endArr[2] - $startArr[2] <= 0) $startArr[1] ++;
        if($endArr[1] - $startArr[1] <= 0) $endArr[0] ++;
        $year = $endArr[0] - $startArr[0];
        return $year;
    }
}

if(!function_exists('excelTime')){
    /**
     * 转换excel时间
     * @param $date 2017/03/09
     * @return string 2017-03-09
     */
    function excelTime($date) {
        if (function_exists('GregorianToJD')) {
            if (is_numeric($date)) {
                $jd = GregorianToJD(1, 1, 1970);
                $gregorian = JDToGregorian($jd + intval($date) - 25569);
                $date = explode('/', $gregorian);
                $date_str = str_pad($date[2], 4, '0', STR_PAD_LEFT) . "-" . str_pad($date[0], 2, '0', STR_PAD_LEFT) . "-" . str_pad($date[1], 2, '0', STR_PAD_LEFT);
                return $date_str;
            }
        } else {
            $date = $date > 25568 ? $date + 1 : 25569; /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/
            $ofs = (70 * 365 + 17 + 2) * 86400;
            $date = date("Y-m-d", ($date * 86400) - $ofs);
        }
        return $date;
    }
}

if(!function_exists('operateResult')){
    /**
     * 操作结果
     * @param boolean $default
     * @param string $url
     * @param string $operate
     * @return array
     */
    function operateResult($default,$url,$operate = 'add'){
        if ($default) {
            return ['code' => 1, 'msg' => lang('sys_'.$operate.'_success'), 'url' => url($url)];
        } else {
            return ['code' => 0, 'msg' => lang('sys_'.$operate.'_error')];
        }
    }
}

if(!function_exists('inputResult')){
    /**
     * 输入框输入结果
     * @param boolean $default
     * @param string $operate
     * @return array
     */
    function inputResult($default,$operate = 'sort'){
        if ($default) {
            return ['code' => 1, 'msg' => lang('sys_'.$operate.'_success')];
        } else {
            return ['code' => 0, 'msg' => lang('sys_'.$operate.'_error'),'text'=>$default[$operate]];
        }
    }
}

if(!function_exists('switchResult')){
    /**
     * switch操作结果
     * @param boolean $default
     * @param string $operate
     * @return array
     */
    function switchResult($default,$operate = 'status'){
        if ($default) {
            return ['code' => 1, 'msg' => lang('sys_'.$operate.'_success')];
        } else {
            return ['code' => 0, 'msg' => lang('sys_'.$operate.'_error')];
        }
    }
}


?>
