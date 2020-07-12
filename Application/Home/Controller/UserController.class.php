<?php
namespace Home\Controller;


class UserController extends CommonController
{
    // 生成验证码
    public function code(){
        $config=array('length'=>3);
        $obj=new \Think\Verify($config);
        $obj->entry();
    }
    // 注册
    public function regist()
    {
        if(IS_GET){
            $this->display();
        }else{
            $username=I('post.username');
            $password=I('post.password');
            $checkcode=I('post.checkcode');
            $obj=new \Think\Verify();
            if(!$obj->check($checkcode)){
                $this->ajaxReturn(array('status'=>0,'msg'=>验证码错误));
            }
            $model=D('user');
            $res=$model->regist($username,$password);
            if(!$res){
                $this->ajaxReturn(array('status'=>0,'msg'=>$model->getError()));
            }
            $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
        }
    }
    // 登录
    public function login(){
        if(IS_GET){
            $this->display();
        }else{
            $username=I('post.username');
            $password=I('post.password');
            $checkcode=I('post.checkcode');
            $obj=new \Think\Verify();
            if(!$obj->check($checkcode)){
                $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));
            }
            $model=D('User');
            $res=$model->login($username,$password);
            if(!$res){
                $this->ajaxReturn(array('status'=>0,'msg'=>$model->getError()));
            }
            $this->ajaxReturn(array('status'=>1,'msg'=>'ok'));
        }
    }
    public function logout(){
        session('user',null);
        session('user_id',null);
        $this->redirect('/');
    }
}