<?php
namespace Home\Controller;

class MemberController extends CommonController{
    public function __construct(){
        parent::__construct();
        // 登录验证  
        // checkLogin() 先判断，如果没有登录，强行登录
        $this->checkLogin();
    }
    public function order(){
        $user_id=session('user_id');
        $data=D('Order')->where('user_id='.$user_id)->select();
        $this->assign('data',$data);
        $this->display();
    }
}