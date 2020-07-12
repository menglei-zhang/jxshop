<?php
namespace Admin\Controller;

use Think\Controller;

class CommonController extends Controller{
    // 标识是否进行权限认证
    public $is_check_rule=true;
    // 保存用户的信息，包括基本信息、角色ID、权限信息
    public $user=array();
    public function __construct(){
        parent::__construct();
        $admin=cookie('admin');
        if(!$admin){
            $this->error('请登录',U('Login/login'));
        }
        // 读取当前用户对应的文件信息
        $this->user=S('user_'.$admin['id']);
        if(!$this->user){
            echo 'mysql';
            // 将当前的用户信息保存到属性中
            $this->user=$admin;
            // 根据用户的ID获取对应的角色ID
            $role_info=M('AdminRole')->where('admin_id='.$admin['id'])->find();
            // 将角色信息保存到user属性中
            $this->user['role_id']=$role_info['role_id'];
            // 根据角色ID获取对应的权限信息
            $ruleModel=D('Rule');
            if($role_info['role_id']==1){
                // 超级管理员
                $this->is_check_rule=false; // 不设置权限
                $rule_list=$ruleModel->select();
            }else{
                // 普通管理员
                // 根据权限ID获取权限信息
                $rules=D('RoleRule')->getRules($role_info['role_id']);
                // 将权限ID获取到的二维数组转换成一位数组
                foreach ($rules as $key => $value) {
                    $rules_ids[]=$value['rule_id'];
                }
                // 将一位数组转换成字符串格式 方便使用in语法
                $rules_ids=implode(',',$rules_ids);
                // 根据权限ID获取对应的权限信息
                $rule_list=$ruleModel->where("id in($rules_ids)")->select();
            }
            // 将权限信息转换成一位数组保存到user属性中
            foreach ($rule_list as $key => $value) {
                $this->user['rules'][]=strtolower($value['module_name'].'/'.$value['controller_name'].'/'.$value['action_name']);
                // 要考虑导航菜单的显示
                if($value['is_show']==1){
                    $this->user['menus'][]=$value;
                }
            }
            // 读取数据完成之后需要将信息保存到文件中
            S('user_'.$admin['id'],$this->user);
        }
        // 针对超级管理员不进行权限认证
        if($this->user['role_id']==1){
            $this->is_check_rule=false;
        }

        // 设置后台具备访问后台首页的访问权限
        if($this->is_check_rule){
            $this->user['rules'][]='admin/index/index';
            $this->user['rules'][]='admin/index/top';
            $this->user['rules'][]='admin/index/menu';
            $this->user['rules'][]='admin/index/main';
        }
        // 对当前访问的方法进行判断是否有访问权
        if($this->is_check_rule){
            // 普通管理员
            // 获取当前用户访问的URL地址
            $action=strtolower(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME);
            if(!in_array($action,$this->user['rules'])){
                if(IS_AJAX){
                    $this->ajaxReturn(array('status'=>0,'msg'=>'没有权限'));
                }else{
                    echo '没有权限';exit();
                }
            }
        }


    }
}