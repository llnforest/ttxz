<?php
namespace fanston\third;

class Scws{
    public static function get_words($str){
        $so = scws_new();
        $so->set_charset('utf8');
        // 这里没有调用 set_dict 和 set_rule 系统会自动试调用 ini 中指定路径下的词典和规则文件
        $so->send_text($str);
        $words = array();
        while ($tmp = $so->get_result())
        {
          foreach ($tmp as $key => $v) {
              $words[] = $v['word'];
          }
        }
        return $words;
        $so->close();
    }
}