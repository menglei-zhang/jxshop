<?php
    namespace Home\Model;

    use Think\Model;

    class UserModel extends Model{
        // 自定义字段
        protected $fields=array('id','username','password','salt');
        // 数据入库
        public function regist($username,$password){
            $info=$this->where("username = '$username'")->find();
            if($info){
                $this->error="用户名重复";
                return false;
            }
            $salt=rand(100000,999999);
            $db_password=md5(md5($password).$salt);
            $data=array(
                'username'=>$username,
                'password'=>$db_password,
                'salt'=>$salt
            );
            return $this->add($data);
        }
        // 登录
        public function login($username,$password){
            $info=$this->where("username='$username'")->find();
            if(!$info){
                $this->error="用户不存在";
                return false;
            }
            $pwd=md5(md5($password).$info['salt']);
            if($pwd!=$info[password]){
                $this->error="密码错误";
                return false;
            }
            session('user',$info);
            session('user_id',$info['id']);
            D('Cart')->cookieToDb();
            return true;
        }
    }




?>