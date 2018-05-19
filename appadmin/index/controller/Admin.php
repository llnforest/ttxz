<?php
/**
 * author: Lynn
 * since: 2018/3/23 12:05
 */
namespace admin\index\controller;


use \model\AdminModel;
use thinkcms\auth\model\AuthRole;
use thinkcms\auth\model\AuthRoleUser;
use thinkcms\auth\Auth;


class Admin extends BaseController
{

    protected $id,$post,$adminModel;
    protected $url  = 'admin/index';
    protected static $authRoleTable     = 'auth_role';
    protected static $authRoleUserTable = 'auth_role_user';


    public function __construct(\think\Request $request)
    {
        parent::__construct();
        $this->post         = $request->post();
        $this->adminModel   = new AdminModel();
        $this->id           = isset($this->param['id'])?intval($this->param['id']):'';
    }

    /**
     * 列表
     */
    public function index()
    {
        $list = AdminModel::paginate($this->config_page,'',['query'=>$this->param]);
        $this->assign([
            'list'  => $list,
            'page'  => $list->render()
        ]);
        return $this->fetch();
    }

    /**
     * 增加
     */
    public function add()
    {
        if($this->request->isPost()){
            $post           = $this->post;
            //验证
            $result = $this->validate($post,[
                ['name|账户','require|unique:tp_admin,name='.$post['name']],
                ['email|邮箱','require|email'],
                ['password|密码','require'],
                ['phone|手机','require|mobile'],
                ['role|角色','require'],
            ]);

            if (true !== $result) {
                return ['code' =>0,'msg'=>$result,'url' => url('admin/index')];
            }
            $role           = $post['role'];
            $post['password']  = md5($post['password']);
            $post['role']           = implode(',',$role);
            $insert = $this->adminModel->create($post);//增加

            if($insert){
                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($role,$insert['id']);
                return ['code' =>1,'msg'=>lang('sys_add_success'),'url' => url('admin/index')];
            }else{
                return ['code' =>0,'msg'=>lang('sys_add_error'),'url' => url('admin/index')];
            }
        }
        $info['role'] = $this->role();
        $this->assign('info', $info);
        return $this->fetch();
    }

    /**
     * 修改
     */
    public function edit()
    {
        $id = $this->id;
        $info = $this->adminModel->get($id);
        if($this->request->isPost()){
            $post           = $this->post;
            //验证
            $result = $this->validate($post,[
                ['email|邮箱','require|email'],
                ['phone|手机','require|mobile'],
                ['role|角色','require'],
            ]);
            if (true !== $result) {
                return ['code' =>0,'msg'=>$result,'url' => url('admin/index')];
            }
            $role = $post['role'];

            $password = $post['password'];
            if(!empty($password)){
                $post['password'] = md5($password);
            }else{
                unset($post['password']);
            }
            $post['role'] = implode(',',$role);
            if($info->save($post)){//修改
                //加入角色
                $authRoleUser = new AuthRoleUser();
                $authRoleUser->authRoleUserAdd($role,$id);
                return ['code' =>1,'msg'=>lang('sys_edit_success'),'url' => url('admin/index')];
            }else{
                return ['code' =>0,'msg'=>lang('sys_edit_success'),'url' => url('admin/index')];
            }
        }
        $info['role'] = $this->role($info['role']);
        $this->assign('info',$info);
        return $this->fetch();
    }
    public function password(){
        if($this->request->isPost()){
            $post           = $this->post;
            $admin = AdminModel::get($this->uid);
            if(empty($admin)){
                return ['code'=>0,'msg'=>lang('sys_param_error')];
            }
            $result = $this->validate($post,[
                ['old_password|原密码','require'],
                ['password|新密码','require'],
                ['c_password|确认密码','require']
            ]);

            if (true !== $result) {
                return ['code'=>0,'msg'=>$result];
            }
            if(md5($post['c_password']) != md5($post['password'])){
                return ['code'=>0,'msg'=>'新密码与确认密码不一致'];
            }
            if(md5($post['old_password']) != $admin['password']){
                return ['code'=>0,'msg'=>'原密码不一致'];
            }
            $admin->save(['password'=>md5($post['password'])]);
            Auth::logout();
            return ['code'=>1,'msg'=>lang('sys_edit_success'),'parents_url'=>url('publics/login')];
        }
        return view('password');
    }

    /**
     * 删除
     */
    public function delete($id)
    {
        if(!$this->request->isAjax()){
            return abort(404, lang(lang('sys_method_error')));
        }else if($id==1){
            return $this->error('超级管理员不能删除');
        }

        if(AdminModel::where(['id'=>$id])->delete()){

            //删除角色权限
            $authRoleUser = new AuthRoleUser();
            $authRoleUser->authRoleUserDelete($id);

            return $this->success(lang('sys_del_success'),url($this->url));
        }else{
            return $this->error(lang('sys_del_error'));
        }

    }

    /**
     * 管理员状态修改
     */
    public function status(){
        if($this->request->isPost()){
            $id     = isset($this->param['id'])?$this->param['id']:0;
            $param  = isset($this->param['data'])?$this->param['data']:0;
            $ratify = $this->adminModel->allowField(['status'])->save(['status'=>$param],['id'=>$id]);
            if($ratify){
                return ['code' => 1,'msg' => lang('sys_status_success')];
            }else{
                return ['code' => 0,'msg' => lang('sys_status_error')];
            }
        }
        return ['code' => 0,'msg' => lang('sys_method_error')];
    }

    private function role($roleid = ''){
        $roleid = explode(',',$roleid);
        $role = AuthRole::column('name','id');
        $html = '';
        foreach($role as $k=>$v){
            $selected = in_array($k, $roleid)?'selected':'';

            $html   .= ' <option '.$selected.' value="'.$k.'">'.$v.'</option>';
        }

        return $html;
    }

    //渲染用户列表
    public function adminList(){
        if(empty($this->param['name'])) return ['code'=>0,'data'=>[]];
        $adminList = AdminModel::field('id,nick_name as name')->where(['nick_name'=>['like','%'.$this->param['name'].'%'],'status'=>1,'is_agent'=>1])->order('create_time desc')->select();
        if(count($adminList) == 0) return ['code'=>2,'data'=>$adminList];
        else return ['code'=>1,'data'=>$adminList];
    }

}

?>