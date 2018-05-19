<?php
namespace fanston\third;

use think\Cache;

class MyCache{
	// 缓存开关
	public static $CacheOpen = true;
	// 品牌缓存KEY
	public static $BrandKey = 'BrandKey_';
	// 模型缓存KEY
	public static $ModelKey = 'ModelKey_';
	//物流开关
	public static $ExpressKey = 'ExpressKey_';
	//短信内容前缀
	public static $SMSKey = 'SMSKey_';
	//短信条数前缀
	public static $SMSNumKey = 'SMSNumKey_';
	//微信access
	public static $WEIXINTOKEN = 'WEIXINTOKEN_';
	//爱回收采集
	public static $COLLECTION = 'COLLECTION';
	//采集当前标记
	public static $COLLECTION_PAGE = 'COLLECTION_PAGE';
	//微信Ticket
	public static $WEIXIN_TICKET = 'WEIXIN_TICKET';
	//M版微信Ticket
	public static $M_WEIXIN_TICKET = 'M_WEIXIN_TICKET';
	//微信Access
	public static $WEIXIN_ACCESS = 'WEIXIN_ACCESS';
	//微信Access
	public static $M_WEIXIN_ACCESS = 'M_WEIXIN_ACCESS';
	//微信access_token
	public static $ACCESS_TOKEN = 'ACCESS_TOKEN';
	//微信access_token
	public static $M_ACCESS_TOKEN = 'M_ACCESS_TOKEN';
	//临时搜索记录缓存
	public static $SEARCH_TEMP_LOG = 'SEARCH_TEMP_LOG_';
	//临时回收车记录
	public static $RECOVERY_TEMP_LOG = 'RECOVERY_TEMP_LOG_';
	//临时估价记录
    public static $ASSESSMENT_TEMP_LOG = 'ASSESSMENT_TEMP_LOG_';
    //数据价格导入临时机型ID
    public static $INPUTPRICE_MODEL_ID = 'INPUTPRICE_MODEL_ID';




	//后台统计数据缓存
	public static $STATISTICS_ALL = 'STATISTICS_ALL';
	public static $STATISTICS_ASSESSMENT = 'STATISTICS_ASSESSMENT';
	public static $STATISTICS_USER_ASSESSMENT = 'STATISTICS_USER_ASSESSMENT';
	public static $STATISTICS_USER_ASSESSMENT_PAGE = 'STATISTICS_USER_ASSESSMENT_PAGE';


	public static function get($key){
		if(!self::$CacheOpen) return false;
		return Cache::get($key);
	}

	public static function set($key,$data,$expire = 3600){
		if(!self::$CacheOpen) return false;
		if($expire == 0)
			Cache::set($key,$data);
		return Cache::set($key,$data,$expire);
	}

	public static function rm($key){
		if(!self::$CacheOpen) return false;
		return Cache::rm($key);
	}
}
