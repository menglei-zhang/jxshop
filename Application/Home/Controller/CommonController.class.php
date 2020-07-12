<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller
{
    public function __construct(){
        parent::__construct();
        // 获取分类信息
        $cate=D('Admin/Category')->getCateTree();
        $this->assign('cate',$cate);
    }
    // 检查用户是否登录，如果没有登录，直接跳转到登录页面
    public function checkLogin(){
        $user_id=session('user_id');
        if(!$user_id){
            $this->error('请先登录',U('User/login'));
        }
    }
}