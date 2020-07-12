<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller{
    // 验证码
    public function verify(){
        $config=array('length'=>3);
        $verify=new \Think\Verify($config);
        $verify->entry();
    }
    public function login(){
        if(IS_GET){
            $this->display();
        }else{
            $username=I('post.username');
            $password=I('post.password');
            $model=D('Admin');
            $res=$model->login($username,$password);
            if(!$res){
                $this->error($model->getError());
            }
            $captcha=I('post.captcha');
            $verify=new \Think\Verify();
            $res=$verify->check($captcha);
            if(!$res){
                $this->error('验证码错误');
            }
            $this->success('登录成功',U('Index/index'));
            
        }
    }
}
