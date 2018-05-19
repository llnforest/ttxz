<?php
namespace fanston\third;


class ReadIdCard{

    public static function read($file = ''){
        // return '{"status":"0","msg":"ok","result":{"address":"安徽省合肥市蜀山区长江西路669号高新管委会人才中心集体户2","birth":"1987-11-01","name":"孟凡德","number":"340825198711010279","nation":"汉","sex":"男","portrait":"","retain":""}}';
        // $file = '/Users/mac/Downloads/5a2e52ce2b68e3af0b89346925d1591a_thumb.jpg';
        if(empty($file)) return false;
        $baseImg = urlencode(base64_encode(file_get_contents($file)));
        $host = "http://jisusfzsb.market.alicloudapi.com";
        $path = "/idcardrecognition/recognize";
        $method = "POST";
        $appcode = "e2731eeedfff4d838e5c65d41ba1303f";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "typeid=2";
        $bodys = "pic=".$baseImg;
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $res = curl_exec($curl);
        curl_close($curl);
        return $res;
        // echo base64_encode(file_get_contents($file));
    }
}
?>