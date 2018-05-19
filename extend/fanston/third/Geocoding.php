<?php
namespace fanston\third;
/**
 * 根据地理坐标获取国家、省份、城市，及周边数据类(利用百度Geocoding A$pi实现)
 * 百度密钥获取方法：http://lbsyun.baidu.com/apiconsole/key?application=key（需要先注册百度开发者账号）
 * Date:    2015-07-30
 * Author:  fdipzone
 * Ver: 1.0
 *
 * Func:
 * Public  getAddressComponent 根据地址获取国家、省份、城市及周边数据
 * Private toCurl              使用curl调用百度Geocoding A$pi
 */

class Geocoding {

    // 百度Geocoding A$pi
    const API = 'http://api.map.baidu.com/geocoder/v2/';

    // 不显示周边数据
    const NO_POIS = 0;

    // 显示周边数据
    const POIS = 1; 

    const ak = '2q5j23tiXCUyKibsBxF1OdiObyoujG0M';
    const sk = 'XzCiHIci4LALsvhAgtDUob8CChR9e48v';
    /**
     * 根据地址获取国家、省份、城市及周边数据
     * @param  String  $ak        百度ak(密钥)
     * @param  Decimal $longitude 经度
     * @param  Decimal $latitude  纬度
     * @param  Int     $pois      是否显示周边数据
     * @return Array
     */
    public static function getAddressComponent($longitude, $latitude, $pois=self::NO_POIS){
        // return json_decode('{"status":0,"result":{"location":{"lng":121.40479278564,"lat":31.244165288798},"formatted_address":"上海市普陀区梅川路255弄-11幢","business":"华师大,长风公园,金沙江路","addressComponent":{"country":"中国","country_code":0,"province":"上海市","city":"上海市","district":"普陀区","adcode":"310107","street":"梅川路","street_number":"255弄-11幢","direction":"附近","distance":"20"},"pois":[],"poiRegions":[{"direction_desc":"内","name":"怒江苑","tag":"房地产"}],"sematic_description":"怒江苑内","cityCode":289}}',TRUE);
        $param = array(
                'ak' => self::ak,
                'location' => implode(',', array($latitude, $longitude)),
                'pois' => $pois,
                'output' => 'json'
        );
        // 请求百度api
        $response = self::toCurl(self::API, $param);

        $result = array();

        if($response){
            $result = json_decode($response, true);
        }

        return $result;

    }
    /**
     * 使用curl调用百度Geocoding A$pi
     * @param  String $url    请求的地址
     * @param  Array  $param  请求的参数
     * @return JSON
     */
    private static function toCurl($url, $param=array()){

        $ch = curl_init();

        if(substr($url,0,5)=='https'){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));

        $response = curl_exec($ch);

        if($error=curl_error($ch)){
            return false;
        }

        curl_close($ch);

        return $response;

    }

     //计算两坐标点之间的距离
    public static function GetDistance($lat1, $lng1, $lat2, $lng2, $len_type = 1, $decimal = 2)
    {
        $pi = 3.1415926;
        $earth_radius = 6378.137;
        $radLat1 = $lat1 * $pi / 180.0;
        $radLat2 = $lat2 * $pi / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * $pi / 180.0) - ($lng2 * $pi / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $s = $s * $earth_radius;
        $s = round($s * 1000);
        if ($len_type > 1)
        {
            $s /= 1000;
        }
        return round($s, $decimal);
    }

    public static function wgs84_to_baidu($lon,$lat){
        $url = "http://api.map.baidu.com/geoconv/v1/?";
        $param = array(
            'coords' => $lon.','.$lat,
            'from' => 1,
            'to' => 5,
            'ak' => self::ak,
        );
        //转换坐标
        $response = self::toCurl($url, $param);
        $result = [];
        if($response){
            $result = json_decode($response, true);
            if($result['status'] == 0){
                return ['lng' => $result['result'][0]['x'],'lat' => $result['result'][0]['y']];
            }
        }
        return $result;
    }
}

?>