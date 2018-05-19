<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\index\controller;

class Auth extends BaseController
{
    public function _empty($name)
    {
        $auth =  new \thinkcms\auth\Auth();
        $auth = $auth->autoload($name);
        if($auth){
            if(isset($auth['code'])){
                return json($auth);
            }elseif(isset($auth['file'])){
                return $auth['file'];
            }
            $this->view->engine->layout(false);
            return $this->fetch($auth[0],$auth[1]);
        }
        return abort(404,lang('page_no_found'));
    }



}

